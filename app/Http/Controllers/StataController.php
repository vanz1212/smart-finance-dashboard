<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class StataController extends BaseController
{
    private const COMMANDS = [
        'describe',
        'summarize',
        'list',
        'missing',
        'correlate',
        'tabulate',
        'sort',
        'regress',
    ];

    public function index(Request $request)
    {
        $isApi = $request->expectsJson() || $request->is('api/*');
        $dataset = $isApi ? Cache::get('stata_dataset_' . auth()->id()) : $request->session()->get('stata_dataset');
        $output = $isApi ? Cache::get('stata_output_' . auth()->id()) : $request->session()->get('stata_output');

        if ($isApi) {
            return response()->json([
                'dataset' => $dataset,
                'output' => $output,
            ]);
        }

        return view('stata', [
            'stataDataset' => $dataset,
            'stataOutput' => $output,
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'stata_file' => [
                'required',
                'file',
                'max:25600',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (strtolower((string) $value->getClientOriginalExtension()) !== 'dta') {
                        $fail(__('stata.file_must_be_dta'));
                    }
                },
            ],
        ]);

        $file = $request->file('stata_file');
        $relativePath = 'stata/'.(string) $request->user()->id.'/'.Str::uuid().'.dta';
        Storage::disk('local')->putFileAs(
            dirname($relativePath),
            $file,
            basename($relativePath)
        );

        try {
            $inspection = $this->runTool(Storage::disk('local')->path($relativePath), [
                'command' => 'inspect',
            ]);
        } catch (Throwable $exception) {
            Storage::disk('local')->delete($relativePath);
            Log::warning('Stata import failed.', ['exception' => $exception]);

            if (str_contains($exception->getMessage(), 'ModuleNotFoundError')) {
                $message = __('stata.parser_dependency_missing');
            } elseif (str_contains($exception->getMessage(), 'Python')) {
                $message = $exception->getMessage();
            } else {
                $message = __('stata.file_unreadable');
            }

            return back()->withErrors([
                'stata_file' => $message,
            ]);
        }

        $isApi = $request->expectsJson() || $request->is('api/*');
        $oldPath = data_get($isApi ? Cache::get('stata_dataset_' . auth()->id()) : $request->session()->get('stata_dataset'), 'path');

        if (is_string($oldPath) && $oldPath !== $relativePath) {
            Storage::disk('local')->delete($oldPath);
        }

        $datasetData = [
            'path' => $relativePath,
            'name' => $file->getClientOriginalName(),
            'size' => (int) $file->getSize(),
            'summary' => $inspection['summary'],
            'variables' => $inspection['variables'],
            'preview' => $inspection['table'],
        ];

        if ($isApi) {
            Cache::put('stata_dataset_' . auth()->id(), $datasetData, now()->addHours(2));
            Cache::forget('stata_output_' . auth()->id());
            
            return response()->json([
                'message' => __('stata.import_success'),
                'dataset' => $datasetData
            ]);
        }

        $request->session()->put('stata_dataset', $datasetData);
        $request->session()->forget('stata_output');

        return redirect()->to(route('stata').'#stata-workbench')
            ->with('stata_status', __('stata.import_success'));
    }

    public function run(Request $request)
    {
        $rules = [
            'command' => ['nullable', Rule::in(self::COMMANDS)],
            'variables' => ['nullable', 'array', 'max:30'],
            'variables.*' => ['string', 'max:128'],
            'direction' => ['nullable', Rule::in(['asc', 'desc'])],
            'stata_prompt' => ['nullable', 'string', 'max:255'],
        ];

        $validated = $request->validate($rules);

        $command = $validated['command'] ?? null;
        $variables = $validated['variables'] ?? [];
        $direction = $validated['direction'] ?? 'asc';

        if (!empty($validated['stata_prompt'])) {
            $parts = preg_split('/\s+/', trim($validated['stata_prompt']));
            $promptCommand = strtolower(array_shift($parts));
            
            // Handle 'misstable summarize' as 'missing'
            if ($promptCommand === 'misstable' && isset($parts[0]) && strtolower($parts[0]) === 'summarize') {
                $command = 'missing';
                array_shift($parts);
            } else {
                $command = $promptCommand;
            }

            if (!in_array($command, self::COMMANDS)) {
                return back()->withErrors(['stata_command' => 'Command tidak didukung via prompt.']);
            }
            $variables = $parts;
        }

        if (empty($command)) {
            return back()->withErrors(['stata_command' => 'The command field is required.']);
        }

        $isApi = $request->expectsJson() || $request->is('api/*');
        $dataset = $isApi ? Cache::get('stata_dataset_' . auth()->id()) : $request->session()->get('stata_dataset');
        $relativePath = data_get($dataset, 'path');

        if (! is_string($relativePath) || ! Storage::disk('local')->exists($relativePath)) {
            if ($isApi) {
                return response()->json(['errors' => ['stata_command' => [__('stata.import_first')]]], 422);
            }
            return back()->withErrors(['stata_command' => __('stata.import_first')]);
        }

        try {
            $output = $this->runTool(Storage::disk('local')->path($relativePath), [
                'command' => $command,
                'variables' => array_values($variables),
                'direction' => $direction,
            ]);
        } catch (Throwable $exception) {
            Log::warning('Stata command failed.', ['exception' => $exception]);

            if ($isApi) {
                return response()->json(['errors' => ['stata_command' => [$exception->getMessage()]]], 422);
            }
            return redirect()->to(route('stata').'#stata-workbench')
                ->withErrors(['stata_command' => $exception->getMessage()]);
        }

        if ($isApi) {
            Cache::put('stata_output_' . auth()->id(), $output, now()->addHours(2));
            return response()->json([
                'output' => $output
            ]);
        }

        $request->session()->put('stata_output', $output);

        return redirect()->to(route('stata').'#stata-output');
    }

    public function clear(Request $request)
    {
        $isApi = $request->expectsJson() || $request->is('api/*');
        $relativePath = data_get($isApi ? Cache::get('stata_dataset_' . auth()->id()) : $request->session()->get('stata_dataset'), 'path');

        if (is_string($relativePath)) {
            Storage::disk('local')->delete($relativePath);
        }

        if ($isApi) {
            Cache::forget('stata_dataset_' . auth()->id());
            Cache::forget('stata_output_' . auth()->id());
            return response()->json(['message' => __('stata.dataset_closed')]);
        }

        $request->session()->forget(['stata_dataset', 'stata_output']);

        return redirect()->route('stata')->with('stata_status', __('stata.dataset_closed'));
    }

    private function runTool(string $filePath, array $payload): array
    {
        $process = new Process([
            ...$this->pythonCommand(),
            base_path('scripts/stata_tool.py'),
            $filePath,
        ]);
        $process->setInput(json_encode($payload, JSON_THROW_ON_ERROR));
        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            $failedResult = json_decode($process->getOutput(), true);
            $message = data_get($failedResult, 'error')
                ?: trim($process->getErrorOutput())
                ?: __('stata.parser_failed');

            if (str_contains(strtolower($message), 'not recognized') || str_contains(strtolower($message), 'not found')) {
                $message = __('stata.python_not_available');
            }

            throw new RuntimeException(Str::limit($message, 500));
        }

        $result = json_decode($process->getOutput(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($result['error'])) {
            throw new RuntimeException((string) $result['error']);
        }

        return $result;
    }

    private function pythonCommand(): array
    {
        $configured = trim((string) config('services.stata.python_binary'));

        if ($configured !== '') {
            if ((str_contains($configured, '/') || str_contains($configured, '\\')) && ! is_file($configured)) {
                throw new RuntimeException(__('stata.python_not_found', ['path' => $configured]));
            }

            return [$configured];
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $launcher = rtrim((string) getenv('SystemRoot'), '\\/').'\\py.exe';

            if (is_file($launcher)) {
                return [$launcher, '-3'];
            }
        }

        return ['python'];
    }
}

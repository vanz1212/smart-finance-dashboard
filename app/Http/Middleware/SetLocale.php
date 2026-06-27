<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Read the 'locale' value from the session and set
     * the application locale accordingly.
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session('locale', config('app.locale', 'id'));

        if (in_array($locale, ['id', 'en'])) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}

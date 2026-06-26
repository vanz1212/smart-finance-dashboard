<?php

namespace Database\Seeders;

use App\Models\ExpenseCategoryTemplate;
use Illuminate\Database\Seeder;

class ExpenseCategoryTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = ExpenseCategoryTemplate::getDefaults();

        foreach ($defaults as $index => $template) {
            ExpenseCategoryTemplate::updateOrCreate(
                ['name' => $template['name']],
                array_merge($template, [
                    'is_default' => true,
                    'order' => $index,
                ])
            );
        }
    }
}

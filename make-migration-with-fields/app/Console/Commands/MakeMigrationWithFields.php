<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeMigrationWithFields extends Command
{
    protected $signature = 'abe:make-migration-with-fields {table} {fields*}';
    protected $description = 'Create a new migration with specified fields';

    public function handle()
    {
        $table = $this->argument('table');
        $fields = $this->argument('fields');
        $fieldDefinitions = '';

        foreach ($fields as $field) {
            list($name, $type) = explode(':', $field);
            $fieldDefinitions .= "                \$table->{$type}('{$name}');\n";
        }

        $className = 'Create' . Str::studly($table) . 'Table';
        $filename = date('Y_m_d_His') . "_create_{$table}_table.php";

        $stub = file_get_contents(base_path('stubs/migration.stub'));
        $stub = str_replace(['{{ class }}', '{{ table }}', '{{ fields }}'], [$className, $table, $fieldDefinitions], $stub);

        file_put_contents(base_path("database/migrations/{$filename}"), $stub);

        $this->info("Migration created successfully: {$filename}");
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeHelper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:helper {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new helper function file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $filePath = base_path("app/Helpers/{$name}.php");

        if (File::exists($filePath)) {
            $this->error("Helper '{$name}' already exists!");
            return;
        }

        $template = <<<EOT
<?php

if (!function_exists('{$name}')) {
    function {$name}()
    {
        // TODO: Add your helper logic here
    }
}
EOT;

        File::ensureDirectoryExists(base_path('app/Helpers'));
        File::put($filePath, $template);

        $this->info("Helper function '{$name}' created successfully at app/Helpers/{$name}.php");
    }
}

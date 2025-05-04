<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetDbName extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:get_db_name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'to get the current database name';

    /**
     * Execute the console command.
     */
    public function handle()
    {
     
        $dbName = DB::connection()->getDatabaseName();
        $this->info("the current db name is: $dbName");
    }
}

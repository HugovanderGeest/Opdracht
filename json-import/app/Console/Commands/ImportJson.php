<?php

namespace App\Console\Commands;

use App\Jobs\ProcessJsonImport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import json file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $usersData = json_decode(file_get_contents(storage_path() . "/json/challenge2.json"), true);
        ProcessJsonImport::dispatch($usersData);
    }
}

<?php

namespace Exceedone\Exment\Console;

use Illuminate\Console\Command;
use Exceedone\Exment\Services\RefreshDataService;
use Exceedone\Exment\Model\CustomTable;

/**
 * Refresh custom data.
 */
class RefreshTableDataCommand extends Command
{
    use CommandTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'exment:refreshtable {table_name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh custom data selecting custom table.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->initExmentCommand();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $table_names = $this->argument("table_name");
        if (!isset($table_names)) {
            throw new \Exception('parameter table name is empty');
        }
        $table_names = stringToArray($table_names);

        // check table exists
        $notExistsTables = collect($table_names)->filter(function($table_name){
            return !CustomTable::getEloquent($table_name);
        });
        if($notExistsTables->count() > 0){
            $this->error('Table ' . $notExistsTables->implode(",") . " are not found.");   
            return;
        }

        if (!$this->confirm('Really refresh data? All refresh custom data.')) {
            return;
        }

        RefreshDataService::refreshTable($table_names);
        
        return 0;
    }
}

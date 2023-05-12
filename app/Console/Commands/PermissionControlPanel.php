<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PermissionControlPanel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bfv:control';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permission Control Panel';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('
            ----------------------------------------------------------------------------------
            |                                                                                |
            |                  Welcome to Bridge for Vendor Command Panel                    |
            |                                                                                |
            ----------------------------------------------------------------------------------
            |                                                                                |
            | PHP ARTISAN COMMANDS                                                           |
            |                                                                                |
            | 1. Clear config and cache                                                      |
            | 2. Create new permission                                                       |
            | 3. Create new role                                                             |
            |                                                                                |
            | X for exit                                                                     |
            ----------------------------------------------------------------------------------

        ');

        $option = $this->ask('Please choose option');

        switch($option){
            case 1:
                $this->info('Clearing config and cache...');
                $this->call('optimize:clear');
                $this->call('config:clear');
                $this->call('cache:clear');
                $this->call('bfv:control');
                break;
            case 2:
                $this->info('Creating new permission...');
                $permission_name = $this->ask('Please enter permission name');
                $guard = 'web'; //  web or api
                $this->call('permission:create-permission' , ['name' => $permission_name, 'guard' => $guard]);
                $this->call('bfv:control');
                break;
            case 3:
                $this->info('Creating new role...');
                $role_name = $this->ask('Please enter role name');
                $guard = 'web'; //  web or api
                $this->call('permission:create-role' , ['name' => $role_name, 'guard' => $guard]);
                $this->call('bfv:control');
                break;

            case 'x':
            case 'X':
                $this->info('Exit');
                break;
            default:
                $this->info('Invalid option');
                $this->call('bfv:control');
                break;
        }
    }
}

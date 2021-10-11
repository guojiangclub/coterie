<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Console;

use DB;
use iBrand\Coterie\Backend\Database\MenuTablesSeeder;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    protected $signature = 'ibrand:coterie-install';

    protected $description = 'ibrand:coterie-install.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('key:generate');

        //$this->call('vendor:publish',[0]);

        $this->call('storage:link');

        $this->call('migrate');

        $this->call('ibrand:coterie-backend-install');

        $this->call('passport:keys');

        if (!DB::table(config('admin.database.menu_table'))->where('title', '圈子管理')->first()) {
            $this->call('db:seed', ['--class' => MenuTablesSeeder::class]);
        }

        $this->info('ibrand:coterie-install successfully.');
    }
}

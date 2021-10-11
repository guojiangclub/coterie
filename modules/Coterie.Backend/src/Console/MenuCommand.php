<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Console;

use iBrand\Coterie\Backend\Database\MenuTablesSeeder;
use Illuminate\Console\Command;

class MenuCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ibrand-saas:coterie-backend-menu';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ibrand saas coterie backend menu';

    /**
     * Install directory.
     *
     * @var string
     */
    protected $directory = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //$this->call('admin:install');

        $this->reBuildMenu();
    }

    /**
     * Create tables and seed it.
     */
    public function reBuildMenu()
    {
        $this->call('db:seed', ['--class' => MenuTablesSeeder::class]);
    }
}

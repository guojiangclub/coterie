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

use DB;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'ibrand-saas:coterie-backend-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the ibrand-saas coterie backend';

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

        $admin = config('admin.database.users_model');
        $admin_roles = config('admin.database.roles_model');
        $permission = config('admin.database.permissions_model');
        $menu = config('admin.database.menu_model');

        $permission::truncate();
        $permission::insert([
            [
                'name' => '所有权限',
                'slug' => '*',
                'http_method' => '',
                'http_path' => '*',
            ],
            /*, [
                'name' => '首页',
                'slug' => 'dashboard',
                'http_method' => '',
                'http_path' => '/',
            ], [
                'name' => '个人设置',
                'slug' => 'auth.setting',
                'http_method' => '',
                'http_path' => '/auth/setting',
            ], [
                'name' => '登录权限',
                'slug' => 'auth.login',
                'http_method' => '',
                'http_path' => "/login\r\n/logout",
            ], [
                'name' => '管理员管理',
                'slug' => 'auth.users',
                'http_method' => '',
                'http_path' => '/auth/users*',
            ], [
                'name' => '角色管理',
                'slug' => 'auth.roles',
                'http_method' => '',
                'http_path' => '/auth/roles*',
            ], [
                'name' => '权限管理',
                'slug' => 'auth.permissions',
                'http_method' => '',
                'http_path' => '/auth/permissions*',
            ]*/
        ]);

        $admin_roles::truncate();
        $administrator = $admin_roles::create([
            'name' => '系统管理员',
            'slug' => 'administrator',
        ]);

        /*$advancedManager = $admin_roles::create([
            'name' => '高级管理员',
            'slug' => 'advancedManager',
        ]);

        $manager = $admin_roles::create([
            'name' => '普通管理员',
            'slug' => 'manager',
        ]);*/

        //$admin::first()->roles()->save($admin_roles::first());

        $this->call('ibrand:backend-install');
        $menu::truncate();
        $menu::insert([
            [
                'parent_id' => 0,
                'order' => 1,
                'title' => '系统管理',
                'icon' => 'fa-tasks',
                'uri' => 'coterie/mini/version',
            ],

            /*[
                'parent_id' => 1,
                'order' => 1,
                'title' => 'Index',
                'icon' => 'fa-bar-chart',
                'uri' => '/',
            ],*/
            [
                'parent_id' => 1,
                'order' => 2,
                'title' => '管理员管理',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],

            /*[
                'parent_id' => 1,
                'order' => 2,
                'title' => 'Admin',
                'icon' => 'fa-tasks',
                'uri' => '',
            ],
            [
                'parent_id' => 3,
                'order' => 3,
                'title' => 'Users',
                'icon' => 'fa-users',
                'uri' => 'auth/users',
            ],
            [
                'parent_id' => 3,
                'order' => 4,
                'title' => 'Roles',
                'icon' => 'fa-user',
                'uri' => 'auth/roles',
            ],
            [
                'parent_id' => 3,
                'order' => 5,
                'title' => 'Permission',
                'icon' => 'fa-ban',
                'uri' => 'auth/permissions',
            ],
            [
                'parent_id' => 3,
                'order' => 7,
                'title' => 'Operation log',
                'icon' => 'fa-history',
                'uri' => 'auth/logs',
            ]*/
        ]);

        // add role to menu.
        /* $menu::find(2)->roles()->save($admin_roles::first());*/

        DB::table(config('admin.database.role_menu_table'))->truncate();

//        $menu::where('title', 'Operation log')->first()->roles()->save($administrator);

        if (!$administrator->can('*')) {
            $administrator->permissions()->save($permission::first());
        }

        //高级管理员菜单显示
        /*$menus = $menu::where('parent_id', 0)->whereNotIn('title', ['Operation log'])->get();
        $menu::where('title', 'Admin')->first()->roles()->save($advancedManager);
        foreach ($menus as $item) {
            $item->roles()->save($advancedManager);
        }*/

        //高级管理员权限分配
        /* $permissions = $permission::whereNotIn('slug', ['*', 'auth.logs'])->get();
         foreach ($permissions as $permission) {
             if (!$advancedManager->can($permission->slug)) {
                 $advancedManager->permissions()->save($permission);
             }
         }*/

        //普通管理员菜单显示
        /*$menus = $menu::where('parent_id', 0)->whereNotIn('title', ['Operation log', 'Admin'])->get();
        foreach ($menus as $item) {
            $item->roles()->save($manager);
        }*/

        //普通管理员权限分配
        /*$permissions = $permission::whereNotIn('slug', ['*', 'auth.logs', 'auth.users', 'auth.roles', 'auth.permissions', 'auth.menu', 'auth.logs'])->get();
        foreach ($permissions as $permission) {
            if (!$manager->can($permission->slug)) {
                $manager->permissions()->save($permission);
            }
        }*/

        return true;
    }
}

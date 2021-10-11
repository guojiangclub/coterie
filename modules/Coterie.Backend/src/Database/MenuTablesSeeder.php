<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Database;

use Encore\Admin\Auth\Database\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $lastOrder = DB::table(config('admin.database.menu_table'))->max('order');

        //小程序管理
        /*$parent_wechat = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => 0,
            'order' => $lastOrder++,
            'title' => '小程序管理',
            'icon' => 'fa',
            'blank' => 1,
            'uri' => 'coterie',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $mini = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent_wechat,
            'order' => $lastOrder++,
            'title' => '数据圈',
            'icon' => 'fa-wechat',
            'blank' => 1,
            'uri' => 'coterie/mini',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);*/

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => 1,
            'order' => $lastOrder++,
            'title' => '版本发布',
            'icon' => 'fa-history',
            'blank' => 1,
            'uri' => 'coterie/mini/version',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $parent = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => 0,
            'order' => $lastOrder++,
            'title' => '圈子管理',
            'icon' => '',
            'blank' => 1,
            'uri' => 'coterie/list',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '支付设置',
            'icon' => 'fa-tasks',
            'blank' => 1,
            'uri' => 'coterie/setting/paySetting',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '用户管理',
            'icon' => 'iconfont icon-yuangongguanli',
            'blank' => 1,
            'uri' => 'coterie/users',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '圈子管理',
            'icon' => 'iconfont  icon-dingdanguanli',
            'blank' => 1,
            'uri' => 'coterie/list',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        $contentID = DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $parent,
            'order' => $lastOrder++,
            'title' => '动态管理',
            'icon' => 'iconfont icon-cuxiaoguanli',
            'blank' => 1,
            'uri' => '',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $contentID,
            'order' => $lastOrder++,
            'title' => '动态列表',
            'icon' => '',
            'blank' => 1,
            'uri' => 'coterie/content/list?status=audited',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);

        DB::table(config('admin.database.menu_table'))->insertGetId([
            'parent_id' => $contentID,
            'order' => $lastOrder++,
            'title' => '提问列表',
            'icon' => '',
            'blank' => 1,
            'uri' => 'coterie/question/list?status=unanswered',
            'created_at' => date('Y-m-d H:i:s', time()),
            'updated_at' => date('Y-m-d H:i:s', time()),
        ]);
    }
}

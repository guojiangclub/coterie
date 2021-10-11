<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Http\Controllers;

use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/13
 * Time: 13:27.
 */
class DashBoardController extends Controller
{
    public function index()
    {
        return LaravelAdmin::content(function (Content $content) {
            $name = request()->cookie('ibrand_log_application_name');

            $appid = request()->cookie('ibrand_log_appid');

            $content->header($name);

            $content->description($appid);

            $content->breadcrumb(
                ['text' => '小程序管理', 'url' => '/coterie/mini', 'no-pjax' => 1, 'left-menu-active' => '小程序管理']
            );

            $content->body(view('account-backend::index', compact('appid')));
        });
    }
}

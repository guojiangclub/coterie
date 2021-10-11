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

class SettingController extends Controller
{
    public function paySetting()
    {
        return LaravelAdmin::content(function (Content $content) {
            $content->header('支付设置');

            $content->breadcrumb(
                ['text' => '支付设置', 'url' => '', 'no-pjax' => 1],
                ['text' => '支付设置', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '支付设置']
            );

            $content->body(view('account-backend::settings.pay'));
        });
    }

    public function savePay()
    {
        //1. 保存配置进入到数据库
        $data = request()->except('_token');

        settings()->setSetting($data);

        $this->ajaxJson();
    }
}

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
use iBrand\Coterie\Backend\Repositories\UserRepositoryEloquent;

class UsersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryEloquent $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    //用户管理
    public function index()
    {
        $where = [];
        if (!empty(request('mobile'))) {
            $where['mobile'] = ['like', '%'.request('mobile').'%'];
        }
        $users = $this->userRepository->getUserPaginate($where);

        return LaravelAdmin::content(function (Content $content) use ($users) {
            $content->header('用户管理');

            $content->breadcrumb(
                ['text' => '用户管理', 'url' => 'coterie/users', 'no-pjax' => 1, 'left-menu-active' => '用户管理']
            );

            $content->body(view('account-backend::users.index', compact('users')));
        });
    }
}

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

use Carbon\Carbon;
use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use iBrand\Coterie\Backend\Models\Members;
use iBrand\Coterie\Backend\Repositories\CoterieRepository;

class CoterieController extends Controller
{
    protected $coterieRepository;

    public function __construct(CoterieRepository $coterieRepository)
    {
        $this->coterieRepository = $coterieRepository;
    }

    //圈子列表
    public function index()
    {
        $where = [];
        $status = request('status');
        switch ($status) {
            case '':
                break;
            case 'recommend':
                $where['recommend'] = 1;
                break;
            case 'forbidden':
                $where['forbidden'] = 1;
                break;
        }

        $lists = $this->coterieRepository->getCoteriesPaginated($where);

        return LaravelAdmin::content(function (Content $content) use ($lists) {
            $content->header('圈子列表');

            $content->breadcrumb(
                ['text' => '圈子管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '圈子列表', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '圈子管理']
            );

            $content->body(view('account-backend::coteries.index', compact('lists')));
        });
    }

    //圈子详情
    public function show($id)
    {
        $coterie = $this->coterieRepository->getCoterieByID($id);

        if (!$coterie) {
            return new \Exception('圈子不存在');
        }

        return LaravelAdmin::content(function (Content $content) use ($coterie) {
            $content->header($coterie->name.' 详情');

            $content->breadcrumb(
                ['text' => '圈子管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '圈子详情', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '圈子管理']
            );

            $content->body(view('account-backend::coteries.show', compact('coterie')));
        });
    }

    //圈子成员列表
    public function members($id)
    {
        $coterie = $this->coterieRepository->find($id);

        if (!$coterie) {
            return new \Exception('圈子不存在');
        }

        $type = request('type');
        if ('all' == $type) {
            $title = '圈子成员列表';
            $members = Members::where('coterie_id', $id)->paginate(15);
        } else {
            $title = '圈子嘉宾列表';
            $members = Members::where('coterie_id', $id)->where('user_type', 'guest')->paginate(15);
        }

        return LaravelAdmin::content(function (Content $content) use ($members, $coterie, $title) {
            $content->header($coterie->name.$title);

            $content->breadcrumb(
                ['text' => '圈子管理', 'url' => '', 'no-pjax' => 1],
                ['text' => $title, 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '圈子列表']
            );

            $content->body(view('account-backend::coteries.members', compact('members')));
        });
    }

    /**
     * 更改推荐状态
     *
     * @return mixed
     */
    public function switchRecommend()
    {
        $id = request('id');
        $action = request('action');

        $coterie = $this->coterieRepository->find($id);
        if ('cancel' == $action) {
            $coterie->recommend_at = null;
        } else {
            $coterie->recommend_at = Carbon::now();
        }
        $coterie->save();

        return $this->ajaxJson();
    }

    /**
     * 删除圈子.
     *
     * @return mixed
     */
    public function delete()
    {
        $id = request('id');

        $coterie = $this->coterieRepository->find($id);
        $coterie->delete();

        return $this->ajaxJson();
    }

    /**
     * 恢复圈子.
     *
     * @return mixed
     */
    public function restore()
    {
        $id = request('id');
        $coterie = $this->coterieRepository->getCoterieByID($id);
        $coterie->restore();

        return $this->ajaxJson();
    }
}

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
use iBrand\Coterie\Backend\Repositories\ContentRepository;

class ContentController extends Controller
{
    protected $contentRepository;

    public function __construct(ContentRepository $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    //动态管理
    public function index()
    {
        $where['style_type'] = 'default';

        switch (request('status')) {
            case 'audited':
                $where['status'] = 1;
                break;
            case 'unaudited':
                $where['status'] = 0;
                break;
            case 'forbidden':
                $where['forbidden'] = 1;
                break;
        }

        $relationWhere = [];
        if (request('value')) {
            $relationWhere['name'] = ['like', '%'.request('value').'%'];
        }

        $lists = $this->contentRepository->getContentPaginate($where, $relationWhere);

        return LaravelAdmin::content(function (Content $content) use ($lists) {
            $content->header('圈子动态管理');

            $content->breadcrumb(
                ['text' => '动态管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '圈子动态管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '动态列表']
            );

            $content->body(view('account-backend::content.index', compact('lists')));
        });
    }

    /**
     * 动态详情.
     *
     * @param $id
     */
    public function show($id)
    {
        $detail = $this->contentRepository->getContentByID($id);

        return LaravelAdmin::content(function (Content $content) use ($detail) {
            $content->header('动态详情');

            $content->breadcrumb(
                ['text' => '圈子动态管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '动态详情', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '动态列表']
            );

            $content->body(view('account-backend::content.show', compact('detail')));
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

        $content = $this->contentRepository->find($id);
        if ('cancel' == $action) {
            $content->recommended_at = null;
        } else {
            $content->recommended_at = Carbon::now();
        }
        $content->save();

        return $this->ajaxJson();
    }

    /**
     * 切换置顶状态
     *
     * @return mixed
     */
    public function switchStick()
    {
        $id = request('id');
        $action = request('action');

        $content = $this->contentRepository->find($id);
        if ('cancel' == $action) {
            $content->stick_at = null;
        } else {
            $content->stick_at = Carbon::now();
        }
        $content->save();

        return $this->ajaxJson();
    }

    /**
     * 删除动态
     *
     * @return mixed
     */
    public function delete()
    {
        $id = request('id');

        $content = $this->contentRepository->find($id);
        $content->delete();

        return $this->ajaxJson();
    }

    /**
     * 恢复动态
     *
     * @return mixed
     */
    public function restore()
    {
        $id = request('id');
        $content = $this->contentRepository->getContentByID($id);
        $content->restore();

        return $this->ajaxJson();
    }

    /**
     * 审核.
     *
     * @return mixed
     */
    public function audited()
    {
        $id = request('id');
        $content = $this->contentRepository->getContentByID($id);
        $content->status = 1;
        $content->save();

        return $this->ajaxJson();
    }
}

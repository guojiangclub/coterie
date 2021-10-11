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
use iBrand\Coterie\Backend\Repositories\QuestionRepository;

class QuestionController extends Controller
{
    protected $questionRepository;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    //提问管理
    public function index()
    {
        $where = [];
        switch (request('status')) {
            case 'unanswered':
                $where['content_id'] = 0;  //待回答
                break;
            case 'answered':
                $where['content_id'] = ['>', 0];  //已回答
                break;
            case 'forbidden':
                $where['forbidden'] = 1;  //已删除
                break;
        }

        if (request('value')) {
            $where['content'] = ['like', '%'.request('value').'%'];
        }

        $lists = $this->questionRepository->getQuestionPaginate($where);

        return LaravelAdmin::content(function (Content $content) use ($lists) {
            $content->header('圈子提问管理');

            $content->breadcrumb(
                ['text' => '动态管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '圈子提问管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '提问列表']
            );

            $content->body(view('account-backend::question.index', compact('lists')));
        });
    }

    public function show($id)
    {
        $detail = $this->questionRepository->getQuestionByID($id);

        return LaravelAdmin::content(function (Content $content) use ($detail) {
            $content->header('提问详情');

            $content->breadcrumb(
                ['text' => '动态管理', 'url' => '', 'no-pjax' => 1],
                ['text' => '圈子提问管理', 'url' => '', 'no-pjax' => 1, 'left-menu-active' => '提问列表']
            );

            $content->body(view('account-backend::question.show', compact('detail')));
        });
    }

    /**
     * 删除问题.
     *
     * @return mixed
     */
    public function delete()
    {
        $id = request('id');

        $question = $this->questionRepository->find($id);
        $question->delete();

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
        $question = $this->questionRepository->getQuestionByID($id);
        $question->restore();

        return $this->ajaxJson();
    }
}

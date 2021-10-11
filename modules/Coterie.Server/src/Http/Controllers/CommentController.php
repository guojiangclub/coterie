<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Http\Controllers;

use DB;
use iBrand\Coterie\Core\Auth\User as AuthUser;
use iBrand\Coterie\Core\Notifications\CommentContent;
use iBrand\Coterie\Core\Notifications\PraiseComment;
use iBrand\Coterie\Core\Repositories\CommentRepository;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Services\CommentService;
use iBrand\Coterie\Core\Services\ContentService;
use iBrand\Coterie\Server\Resources\Comment;
use Validator;

class CommentController extends Controller
{
    protected $contentRepository;

    protected $commentService;

    protected $memberRepository;

    protected $contentService;

    protected $commentRepository;

    public function __construct(
        CommentRepository $commentRepository, CommentService $commentService, MemberRepository $memberRepository, ContentService $contentService, ContentRepository $contentRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->commentService = $commentService;
        $this->memberRepository = $memberRepository;
        $this->contentService = $contentService;
        $this->contentRepository = $contentRepository;
    }

    public function index()
    {
        $content = $this->contentRepository->findByField('id', request('content_id'))->first();

        if (!$content) {
            return $this->failed('内容不存在或已删除');
        }

        $this->isCoterieUser($content->coterie_id);

        $limit = request('limit') ? request('limit') : 10;

        $list = $this->commentRepository->getCommentsByContentID(request('content_id'), $limit);

        $comment = null;

        if (request('comment_id')) {
            $comment = $this->commentRepository->getCommentsByCommentID(request('comment_id'));
        }

        /*return $this->response()->paginator($list, new CommentTransformer())->setMeta(['comment' => $comment]);*/
        return $this->paginator($list, Comment::class, ['comment' => $comment]);
    }

    /**
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $rules = [
            'content_id' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make(
            request()->all(),
            $rules
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();
            throw  new \Exception($warning);
        }

        $content = $this->contentRepository->findByField('id', request('content_id'))->first();

        if (!$content || 1 != $content->status) {
            return $this->failed('内容不存在或已删除');
        }

        $member = $this->isCoterieUser($content->coterie_id);

        $input = request()->only('content_id', 'content');

        $input['user_id'] = $member->user_id;

        if ($res = $this->commentService->created($input)) {
            //回复内容评论通知
            AuthUser::find($content->user_id)->notify(new CommentContent($member->coterie, $res, $content));

            return $this->success($res);
        }

        return $this->failed('');
    }

    /**
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit()
    {
        $comment = $this->isCommentUser(request('comment_id'));

        $coterie_id = isset($comment->CoterieContent->coterie_id) ? $comment->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        return $this->success($comment);
    }

    /**
     * 修改评论.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update()
    {
        $rules = [
            'comment_id' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make(
            request()->all(),
            $rules
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();
            throw  new \Exception($warning);
        }

        $comment = $this->isCommentUser(request('comment_id'));

        $coterie_id = isset($comment->CoterieContent->coterie_id) ? $comment->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        if ($res = $this->commentRepository->update(['content' => request('content')], request('comment_id'))) {
            return $this->success($res);
        }

        return $this->failed('修改失败');
    }

    /**
     * 删除评论.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        $comment_id = request('comment_id');

        $user = request()->user();

        $comment = $this->commentRepository->with('CoterieContent.coterie')->findByField('id', $comment_id)->first();

        if (!$comment) {
            return $this->failed('');
        }

        if ($user->id != $comment->CoterieContent->coterie->user_id) {
            if ($user->id != $comment->user_id) {
                return $this->failed('无权限');
            }
        }

        $coterie_id = $comment->CoterieContent->coterie->id;

        $this->isCoterieUser($coterie_id);

        try {
            DB::beginTransaction();

            if ($res = $this->commentRepository->delete(request('comment_id'))) {
                $this->contentService->updateTypeCountByID($comment->CoterieContent->id, 'comment_count', -1);

                DB::commit();

                return $this->success($res);
            }

            return $this->failed('删除失败');
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }

    /**
     * 评论点赞.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function praiseStore()
    {
        $user = request()->user();

        $targets = $this->commentRepository->with('CoterieContent')->findByField('id', request('comment_id'))->first();

        $member = $this->isCoterieUser(isset($targets->CoterieContent->coterie_id) ? $targets->CoterieContent->coterie_id : 0);

        if ($targets) {
            if ($res = $user->favorite($targets)) {
                //评论点赞通知
                if (isset($res['attached']) and count($res['attached'])) {
                    AuthUser::find($targets->user_id)->notify(new PraiseComment($member->coterie, $targets));
                }
            }

            return $this->success($res);
        }

        return $this->failed('');
    }

    /**
     * 取消评论点赞.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function praiseDelete()
    {
        $user = request()->user();

        $targets = $this->commentRepository->with('CoterieContent')->findByField('id', request('comment_id'))->first();

        $this->isCoterieUser(isset($targets->CoterieContent->coterie_id) ? $targets->CoterieContent->coterie_id : 0);

        if ($targets) {
            return $this->success($user->unfavorite($targets));
        }
    }

    /**
     * 验证是否是自己到评论.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isCommentUser($comment_id)
    {
        $user = request()->user();

        $comment = $this->commentRepository->with('CoterieContent')->findWhere(['user_id' => $user->id, 'id' => $comment_id])->first();

        if ($user->cant('isCommentUser', $comment)) {
            throw new \Exception('无权限');
        }

        return $comment;
    }
}

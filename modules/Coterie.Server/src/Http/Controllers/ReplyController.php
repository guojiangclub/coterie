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
use iBrand\Coterie\Core\Notifications\PraiseReply;
use iBrand\Coterie\Core\Notifications\ReplyComment;
use iBrand\Coterie\Core\Repositories\CommentRepository;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\ReplyRepository;
use iBrand\Coterie\Core\Services\ContentService;
use iBrand\Coterie\Core\Services\ReplyService;
use Validator;

class ReplyController extends Controller
{
    protected $contentRepository;

    protected $replyService;

    protected $memberRepository;

    protected $contentService;

    protected $replyRepository;

    public function __construct(
        CommentRepository $commentRepository, ReplyService $replyService, MemberRepository $memberRepository, ContentService $contentService, ReplyRepository $replyRepository, ContentRepository $contentRepository
    ) {
        $this->commentRepository = $commentRepository;

        $this->replyService = $replyService;

        $this->memberRepository = $memberRepository;

        $this->contentService = $contentService;

        $this->replyRepository = $replyRepository;

        $this->contentRepository = $contentRepository;
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
            'comment_id' => 'required',
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

        $comment = $this->commentRepository->findByField('id', request('comment_id'))->first();

        if (!$content || 1 != $content->status) {
            return $this->failed('内容不存在或已删除');
        }

        if (!$comment || 1 != $comment->status) {
            return $this->failed('comment_id不存在');
        }

        $input = request()->only('content_id', 'content', 'comment_id', 'to_meta');

        $member = $this->isCoterieUser($content->coterie_id);

        $input['user_id'] = $member->user_id;

        $input['to_meta'] = isset($input['to_meta']) ? json_encode($input['to_meta']) : null;

        if ($res = $this->replyService->created($input)) {
            if ($comment->user_id != $content->user_id) {
                AuthUser::find($comment->user_id)->notify(new ReplyComment($member->coterie, 'commenter', $res, $content, $comment));

                AuthUser::find($content->user_id)->notify(new ReplyComment($member->coterie, 'contenter', $res, $content, $comment));
            } else {
                AuthUser::find($comment->user_id)->notify(new ReplyComment($member->coterie, 'commenter', $res, $content, $comment));
            }

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
        $reply = $this->isReplyUser(request('reply_id'));

        $coterie_id = isset($reply->CoterieContent->coterie_id) ? $reply->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        return $this->success($reply);
    }

    /**
     * 回复评论点赞.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function praiseStore()
    {
        $user = request()->user();

        $targets = $this->replyRepository->with('CoterieContent')
            ->with('comment')
            ->findByField('id', request('reply_id'))->first();

        $member = $this->isCoterieUser(isset($targets->CoterieContent->coterie_id) ? $targets->CoterieContent->coterie_id : 0);

        if ($targets) {
            if ($res = $user->favorite($targets)) {
                //评论点赞通知
                if (isset($res['attached']) and count($res['attached'])) {
                    AuthUser::find($targets->user_id)->notify(new PraiseReply($member->coterie, $targets));
                }
            }

            return $this->success($res);
        }

        return $this->failed('');
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
            'reply_id' => 'required',
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

        $reply = $this->isReplyUser(request('reply_id'));

        $coterie_id = isset($reply->CoterieContent->coterie_id) ? $reply->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        if ($res = $this->replyRepository->update(['content' => request('content')], request('reply_id'))) {
            return $this->success($res);
        }

        return $this->failed('');
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
        $reply = $this->isReplyUser(request('reply_id'));

        $coterie_id = isset($reply->CoterieContent->coterie_id) ? $reply->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        try {
            DB::beginTransaction();

            if ($res = $this->replyRepository->delete(request('reply_id'))) {
                //$this->contentService->updateTypeCountByID($reply->CoterieContent->id,'comment_count',-1);

                DB::commit();

                return $this->success($res);
            }

            return $this->failed('error');
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }

    /**
     * 验证是否是自己到评论.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isReplyUser($reply_id)
    {
        $user = request()->user();

        $reply = $this->replyRepository->with('CoterieContent')->findWhere(['user_id' => $user->id, 'id' => $reply_id])->first();

        if ($user->cant('isReplyUser', $reply)) {
            throw new \Exception('无权限');
        }

        return $reply;
    }
}

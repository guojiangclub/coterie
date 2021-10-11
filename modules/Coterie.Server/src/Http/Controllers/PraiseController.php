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
use iBrand\Coterie\Core\Notifications\PraiseContent;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\PraiseRepository;
use iBrand\Coterie\Core\Services\ContentService;
use iBrand\Coterie\Core\Services\PraiseService;

class PraiseController extends Controller
{
    protected $contentRepository;

    protected $memberRepository;

    protected $praiseRepository;

    protected $praiseService;

    protected $contentService;

    public function __construct(
        MemberRepository $memberRepository, ContentRepository $contentRepository, PraiseRepository $praiseRepository, PraiseService $praiseService, ContentService $contentService
    ) {
        $this->memberRepository = $memberRepository;

        $this->contentRepository = $contentRepository;

        $this->praiseRepository = $praiseRepository;

        $this->praiseService = $praiseService;

        $this->contentService = $contentService;
    }

    /**
     * 点赞接口.
     *
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store()
    {
        $content = $this->contentRepository->findByField('id', request('content_id'))->first();

        if (!$content || 1 != $content->status) {
            return $this->failed('内容不存在或已删除');
        }

        $member = $this->isCoterieUser($content->coterie_id);

        $input = request()->only('content_id');

        $input['user_id'] = $member->user_id;

        if ($this->praiseRepository->findWhere(['user_id' => $member->user_id, 'content_id' => $input['content_id']])->count()) {
            return $this->failed('已点赞');
        }

        $res = $this->praiseService->created($input);

        //内容点赞通知
        if ($res) {
            AuthUser::find($content->user_id)->notify(new PraiseContent($member->coterie, $content, $res));
        }

        return $this->success($res);
    }

    /**
     * 取消点赞.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        $praise = $this->isPraiseUser(request('praise_id'));

        $coterie_id = isset($praise->CoterieContent->coterie_id) ? $praise->CoterieContent->coterie_id : 0;

        $this->isCoterieUser($coterie_id);

        try {
            DB::beginTransaction();

            if ($res = $this->praiseRepository->delete(request('praise_id'))) {
                $this->contentService->updateTypeCountByID($praise->CoterieContent->id, 'praise_count', -1);

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
     * 验证是否是自己的点赞.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isPraiseUser($praise_id)
    {
        $user = request()->user();

        $reply = $this->praiseRepository->with('CoterieContent')->findWhere(['user_id' => $user->id, 'id' => $praise_id])->first();

        if ($user->cant('isPraiseUser', $reply)) {
            throw new \Exception('无权限');
        }

        return $reply;
    }
}

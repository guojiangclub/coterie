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

use iBrand\Coterie\Core\Notifications\AtUser;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Services\NotificationService;
use iBrand\Coterie\Server\Resources\Notification;

class NotificationController extends Controller
{
    protected $memberRepository;

    protected $coterieRepository;

    public function __construct(
        NotificationService $notificationService, MemberRepository $memberRepository, CoterieRepository $coterieRepository
    ) {
        $this->notificationService = $notificationService;
        $this->memberRepository = $memberRepository;
        $this->coterieRepository = $coterieRepository;
    }

    /**
     * 除了点赞相关以外点消息通知列表.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $user = request()->user();

        $list = $this->notificationService->getLists($user);

        //return $this->response()->paginator($list, new NotificationTransformer());
        return $this->paginator($list, Notification::class);
    }

    /**
     * 点赞消息通知列表.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function praiseList()
    {
        $user = request()->user();

        $list = $this->notificationService->getPraiseLists($user);

        //return $this->response()->paginator($list, new NotificationTransformer());
        return $this->paginator($list, Notification::class);
    }

    /**
     * 获取点赞相关全部未读消息通知条数.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function unreadPraiseCount()
    {
        $user = request()->user();

        $count = $this->notificationService->unreadPraiseCount($user);

        return $this->success(['count' => $count]);
    }

    /**
     * 全部赞相关标记已读.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function markPraiseAllRead()
    {
        $user = request()->user();

        $res = $this->notificationService->markPraiseAllRead($user);

        return $this->success($res);
    }

    public function test()
    {
        $user = request()->user();

        $res = $user->notify(new AtUser(1, 2));
    }
}

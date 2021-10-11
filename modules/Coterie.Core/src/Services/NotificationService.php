<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Services;

use Carbon\Carbon;
use iBrand\Component\User\Models\User;
use iBrand\Coterie\Core\Notifications\PraiseComment;
use iBrand\Coterie\Core\Notifications\PraiseContent;
use iBrand\Coterie\Core\Notifications\PraiseReply;

class NotificationService
{
    protected $limit;

    protected $praise;

    public function __construct(
    ) {
        $this->limit = request('limit') ? request('limit') : 10;

        $this->praise = [PraiseContent::class, PraiseComment::class, PraiseReply::class];
    }

    /**
     * 全部赞相关标记已读.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function markPraiseAllRead(User $user)
    {
        $now = Carbon::now();
        $result = $user->unreadNotifications()->whereIn('type', $this->praise)->update(['read_at' => $now]);

        return $result;
    }

    /**
     * 标记已读通过ID.
     *
     * @param User $user
     * @param $id
     *
     * @return mixed
     */
    public function markReadById(User $user, $id)
    {
        $now = Carbon::now();
        $result = $user->unreadNotifications()->where('id', $id)->first();
        if ($result) {
            $result->read_at = $now;

            return $result->save();
        }

        return false;
    }

    /**
     * 获取全部未读消息通知条数.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function unreadCount(User $user)
    {
        return $user->unreadNotifications()->count();
    }

    /**
     * 获取点赞相关全部未读消息通知条数.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function unreadPraiseCount(User $user)
    {
        return $user->unreadNotifications()->whereIn('type', $this->praise)->count();
    }

    /**
     *获取除了点赞相关以外列表消息通知.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function getLists(User $user)
    {
        return $user->notifications()

            ->whereNotIn('type', $this->praise)

            ->where('read_at', null)

            ->OrderBy('created_at', 'desc')

            ->OrderBy('read_at')

            ->paginate($this->limit);
    }

    /**
     * 获取点赞相关列表消息通知.
     *
     * @param User $user
     *
     * @return mixed
     */
    public function getPraiseLists(User $user)
    {
        return $user->notifications()

            ->whereIn('type', $this->praise)

            ->where('read_at', null)

            ->OrderBy('created_at', 'desc')

            ->OrderBy('read_at')

            ->paginate($this->limit);
    }

    public function atUser(User $user, $at_user_id)
    {
        return User::find($at_user_id);
    }
}

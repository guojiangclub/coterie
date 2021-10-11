<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Repositories\Eloquent;

use iBrand\Coterie\Core\Models\InviteMember;
use iBrand\Coterie\Core\Repositories\InviteMemberRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class InviteMemberRepositoryEloquent extends BaseRepository implements InviteMemberRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return InviteMember::class;
    }

    /**
     * @param $coterie_id
     * @param $inviter_user_id
     *
     * @return mixed
     */
    public function getInviteMemberByCoterieId($coterie_id, $inviter_user_id)
    {
        return $this->model->where('coterie_id', $coterie_id)
            ->where('inviter_user_id', $inviter_user_id)
            ->first();
    }
}

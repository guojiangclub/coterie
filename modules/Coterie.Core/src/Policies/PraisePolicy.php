<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Policies;

use iBrand\Component\User\Models\User;
use iBrand\Coterie\Core\Models\Praise;

class PraisePolicy
{
    public function isPraiseUser(User $user, Praise $praise)
    {
        return  $user->id === $praise->user_id;
    }
}

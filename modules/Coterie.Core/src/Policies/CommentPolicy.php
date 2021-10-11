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
use iBrand\Coterie\Core\Models\Comment;

class CommentPolicy
{
    public function isCommentUser(User $user, Comment $comment)
    {
        return  $user->id === $comment->user_id;
    }
}

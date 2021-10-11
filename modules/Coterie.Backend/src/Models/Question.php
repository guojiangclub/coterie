<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Models;

use iBrand\Component\User\Models\User;

class Question extends \iBrand\Coterie\Core\Models\Question
{
    public function coterie()
    {
        return $this->belongsTo(Coterie::class, 'coterie_id');
    }

    public function atUser()
    {
        return $this->belongsTo(User::class, 'answer_user_id');
    }

    public function content()
    {
        return $this->belongsTo(Content::class, 'answer_user_id');
    }
}

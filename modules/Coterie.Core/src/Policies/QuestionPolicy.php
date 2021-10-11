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
use iBrand\Coterie\Core\Models\Question;

class QuestionPolicy
{
    public function isQuestionAnswerUser(User $user, Question $question)
    {
        return  $user->id === $question->answer_user_id;
    }
}

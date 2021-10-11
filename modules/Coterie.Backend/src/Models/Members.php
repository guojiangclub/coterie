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

class Members extends \iBrand\Coterie\Core\Models\Member
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTypeTextAttribute()
    {
        switch ($this->attributes['user_type']) {
            case 'normal':
                return '普通会员';
                break;
            case 'guest':
                return '嘉宾';
                break;
            case 'owner':
                return '圈主';
                break;
            default:
                return '普通会员';
        }
    }

    public function getStatusTextAttribute()
    {
        if ($this->attributes['is_forbidden']) {
            return '禁言';
        }

        return '正常';
    }
}

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

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/12/26
 * Time: 14:31.
 */
class Coterie extends \iBrand\Coterie\Core\Models\Coterie
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getTypeTextAttribute()
    {
        $text = '免费';
        if ('charge' == $this->attributes['cost_type']) {
            $text = '收费:'.($this->attributes['price'] / 100).'元';
        }

        return $text;
    }

    public function members()
    {
        return $this->hasMany(Members::class, 'coterie_id');
    }

    public function getGuestNum()
    {
        return $this->members()->where('user_type', 'guest')->count();
    }
}

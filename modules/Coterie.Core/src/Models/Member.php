<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Models;

use iBrand\Component\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['user_meta_info'];

    /**
     * Member constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie_member');

        parent::__construct($attributes);
    }

    public function getUserMetaInfoAttribute()
    {
        return json_decode($this->user_meta);
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id')->where('status', 1)->select(['id', 'nick_name', 'avatar']);
    }

    public function coterie()
    {
        return $this->belongsTo(Coterie::class, 'coterie_id');
    }
}

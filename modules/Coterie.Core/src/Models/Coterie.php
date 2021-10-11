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

class Coterie extends Model
{
    use \Conner\Tagging\Taggable;

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['price_yuan', 'is_recommend'];

    /**
     * Coterie constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie');

        parent::__construct($attributes);
    }

    public function getIsRecommendAttribute()
    {
        if (empty($this->recommend_at)) {
            return 0;
        }

        return 1;
    }

    public function member()
    {
        return $this->hasOne(Member::class, 'coterie_id');
    }

    public function memberOwner()
    {
        return $this->hasOne(Member::class, 'coterie_id')->where('user_type', 'owner');
    }

    public function memberGuest()
    {
        return $this->hasMany(Member::class, 'coterie_id')->where('user_type', 'guest');
    }

    public function memberNormal()
    {
        return $this->hasMany(Member::class, 'coterie_id')->where('user_type', 'normal');
    }

    public function getPriceYuanAttribute()
    {
        return number_format($this->price / 100, 2, '.', '');
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id')->where('status', 1)->select(['id', 'nick_name', 'avatar']);
    }

    public function memberWithTrashed()
    {
        return $this->hasOne(Member::class, 'coterie_id')->withTrashed();
    }
}

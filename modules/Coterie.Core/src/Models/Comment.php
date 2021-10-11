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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeFavorited;

class Comment extends Model
{
    use SoftDeletes;

    use CanBeFavorited;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['user_meta_info', 'is_praise_user', 'is_comment_user', 'praise_count'];

    /**
     * Comment constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie_content_comment');

        parent::__construct($attributes);
    }

    public function getUserMetaInfoAttribute()
    {
        return json_decode($this->meta);
    }

    public function CoterieContent()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function Reply()
    {
        return $this->hasMany(Reply::class, 'comment_id')->where('status', 1);
    }

    public function getIsPraiseUserAttribute()
    {
        $user = request()->user();

        return $user->hasfavorited($this) ? 1 : 0;
    }

    public function getIsCommentUserAttribute()
    {
        return request()->user()->id == $this->user_id ? 1 : 0;
    }

    public function getPraiseCountAttribute()
    {
        return $this->favoriters()->count();
    }
}

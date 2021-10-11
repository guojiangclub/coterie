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

class Content extends Model
{
    use \Conner\Tagging\Taggable;

    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['meta_info', 'is_recommend', 'is_praise_user', 'is_content_user', 'is_stick', 'link_info', 'img_list_info', 'tags_list_info', 'audio_list_info'];

    /**
     * Content constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie_content');

        parent::__construct($attributes);
    }

    public function getMetaInfoAttribute()
    {
        return json_decode($this->meta);
    }

    public function question()
    {
        return $this->hasOne(Question::class, 'content_id');
    }

    public function coterie()
    {
        return $this->belongsTo(Coterie::class, 'coterie_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class, 'content_id');
    }

    public function praise()
    {
        return $this->hasMany(Praise::class, 'content_id');
    }

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id')->where('status', 1)->select(['id', 'nick_name', 'avatar']);
    }

    public function getIsRecommendAttribute()
    {
        if (empty($this->recommended_at)) {
            return 0;
        }

        return 1;
    }

    public function getIsStickAttribute()
    {
        if (empty($this->stick_at)) {
            return 0;
        }

        return 1;
    }

    public function getIsPraiseUserAttribute()
    {
        $user = request()->user();

        if (!$user || 0 == count($this->praise)) {
            return 0;
        }

        if (in_array($user->id, $this->praise->pluck('user_id')->toArray())) {
            return 1;
        }

        return 0;
    }

    public function getIsContentUserAttribute()
    {
        $user = request()->user();

        if (!$user || $this->user_id != $user->id) {
            return 0;
        }

        return 1;
    }

    public function getLinkInfoAttribute()
    {
        if (!empty($this->link)) {
            return json_decode($this->link, true);
        }

        return null;
    }

    public function getImgListInfoAttribute()
    {
        if (!empty($this->img_list)) {
            return json_decode($this->img_list, true);
        }

        return null;
    }

    public function getTagsListInfoAttribute()
    {
        if (!empty($this->tags_list)) {
            return json_decode($this->tags_list, true);
        }

        return null;
    }

    public function getAudioListInfoAttribute()
    {
        if (!empty($this->audio_list)) {
            return json_decode($this->audio_list, true);
        }

        return null;
    }
}

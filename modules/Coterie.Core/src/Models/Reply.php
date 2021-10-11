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

class Reply extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['user_meta_info', 'to_user_meta_info', 'is_reply_user'];

    /**
     * Content constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie_content_reply');

        parent::__construct($attributes);
    }

    public function getUserMetaInfoAttribute()
    {
        return json_decode($this->meta);
    }

    public function getToUserMetaInfoAttribute()
    {
        return json_decode($this->to_meta);
    }

    public function CoterieContent()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    public function getIsReplyUserAttribute()
    {
        return request()->user()->id == $this->user_id ? 1 : 0;
    }
}

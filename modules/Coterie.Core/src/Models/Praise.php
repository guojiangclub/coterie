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

class Praise extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['client_id'];

    protected $appends = ['user_meta_info'];

    /**
     * Content constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('ibrand.app.database.prefix', 'ibrand_').'coterie_content_praise');

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
}

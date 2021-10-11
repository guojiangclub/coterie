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

class Content extends \iBrand\Coterie\Core\Models\Content
{
    public function getContentTypeTextAttribute()
    {
        switch ($this->attributes['content_type']) {
            case 'link':
                return '链接分享';
                break;
            case 'file':
                return '文件分享';
                break;
            default:
                return '图文内容';
        }
    }
}

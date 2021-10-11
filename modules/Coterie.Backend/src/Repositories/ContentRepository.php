<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Repositories;

use iBrand\Coterie\Backend\Models\Content;
use Prettus\Repository\Eloquent\BaseRepository;

class ContentRepository extends BaseRepository
{
    public function model()
    {
        return Content::class;
    }

    public function getContentByID($id)
    {
        return $this->scopeQuery(function ($query) {
            return $query->withTrashed();
        })->find($id);
    }

    public function getContentPaginate($where, $relationWhere = [], $limit = 15)
    {
        return $this->scopeQuery(function ($query) use ($where, $relationWhere) {
            if (key_exists('forbidden', $where)) {
                unset($where['forbidden']);
                $query = $query->onlyTrashed();
            }

            if (count($where) and is_array($where)) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            $query = $query->with('coterie');

            $query = $query->whereHas('coterie', function ($query) use ($relationWhere) {
                if (count($relationWhere) and is_array($relationWhere)) {
                    foreach ($relationWhere as $key => $value) {
                        if (is_array($value)) {
                            list($operate, $va) = $value;
                            $query = $query->where($key, $operate, $va);
                        } else {
                            $query = $query->where($key, $value);
                        }
                    }
                }
            });

            return $query->orderBy('created_at', 'desc');
        })->paginate($limit);
    }
}

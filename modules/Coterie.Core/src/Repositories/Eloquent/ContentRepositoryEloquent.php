<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Repositories\Eloquent;

use iBrand\Coterie\Core\Models\Content;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class ContentRepositoryEloquent extends BaseRepository implements ContentRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Content::class;
    }

    /**
     * @param $coterie_id
     * @param $limit
     *
     * @return mixed
     */
    public function getListRecommended($coterie_id, $limit)
    {
        return $this->model->with('question')
            ->with('question.user')
            ->with('user')
            ->where('coterie_id', $coterie_id)
            ->where('status', 1)
            ->orderBy('recommended_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit);
    }

    /**
     * @param $coterie_id
     * @param $limit
     *
     * @return mixed
     */
    public function getLists($coterie_id, $user_id = null, $style_type = null, $tag = [], $limit = 10, $comment_limit = 10)
    {
        $query = $this->model->with('question')
            ->with('question.user')
            ->with('user')
            ->with('praise')
            ->with(['comment' => function ($query) use ($comment_limit) {
                return $query->orderBy('updated_at', 'desc')->limit($comment_limit);
            }]);

        if ($style_type) {
            $query = $query->where('style_type', $style_type);
        }

        if ($user_id) {
            $query = $query->where('user_id', $user_id);
        }

        if (count($tag)) {
            $query = $query->withAllTags($tag);
        }

        return $query->where('coterie_id', $coterie_id)
            ->where('status', 1)
            ->orderBy('recommended_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getContentByID($id)
    {
        return $this->model->with('coterie')
            ->with('question')
            ->with('question.user')
            ->with('user')
            ->with('praise')
            ->orderBy('recommended_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->where('status', 1)
            ->where('id', $id)
            ->first();
    }

    /**
     * @param $coterie_id
     *
     * @return mixed
     */
    public function getStickContent($coterie_id)
    {
        return $this->model->where('coterie_id', $coterie_id)
            ->where('style_type', 'default')
            ->where('status', 1)
            ->whereNotNull('stick_at')
            ->orderBy('stick_at', 'desc')
            ->first();
    }
}

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

use iBrand\Coterie\Core\Models\Comment;
use iBrand\Coterie\Core\Repositories\CommentRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class CommentRepositoryEloquent extends BaseRepository implements CommentRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Comment::class;
    }

    /**
     * @param $content_id
     * @param int $limit
     *
     * @return mixed
     */
    public function getCommentsByContentID($content_id, $limit = 10)
    {
        return $this->model->with('reply')
            ->where('content_id', $content_id)
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->paginate($limit);
    }

    /**
     * @param $commentID
     *
     * @return mixed
     */
    public function getCommentsByCommentID($commentID)
    {
        return $this->model->with('reply')
            ->where('status', 1)
            ->find($commentID);
    }
}

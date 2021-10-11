<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface RepositoryContract.
 */
interface ContentRepository extends RepositoryInterface
{
    public function getListRecommended($coterie_id, $limit);

    //public function getHostTags($coterie_id,$limit);

    public function getLists($coterie_id, $user_id = null, $style_type = null, $tag = [], $limit = 10, $comment_limit = 10);

    public function getContentByID($id);

    public function getStickContent($coterie_id);
}

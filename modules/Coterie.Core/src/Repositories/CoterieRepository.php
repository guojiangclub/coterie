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
interface CoterieRepository extends RepositoryInterface
{
    /**
     * @param $id
     * @param int $limit
     *
     * @return mixed
     */
    public function getInfoByID($id, $limit = 5);

    /**
     * @return mixed
     */
    public function getRecommendCoterie();

    /**
     * @param $name
     * @param int $limit
     *
     * @return mixed
     */
    public function getCoterieByName($name, $limit = 10);

    /**
     * @param $user_id
     * @param int $limit
     *
     * @return mixed
     */
    public function getCoterieByUserID($user_id, $limit = 10);

    public function getCoterieMemberByUserID($user_id, $coterie_id);

    public function getCoterieByID($coterie_id);
}

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
interface MemberRepository extends RepositoryInterface
{
    public function createByUser($user, $coterie_id, $user_type);

    public function getListByCoterieID($coterie_id, $name, $limit = 10, $type = ['owner', 'guest', 'normal'], $is_forbidden = 0);

    public function getMemberByCoterieID($user_id, $coterie_id);

    public function getMemberInfo($user_id, $coterie_id);
}

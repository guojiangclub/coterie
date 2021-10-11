<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Policies;

use iBrand\Component\User\Models\User;
use iBrand\Coterie\Core\Models\Order;

class OrderPolicy
{
    public function isOrderUser(User $user, Order $order)
    {
        return  $user->id === $order->user_id;
    }

    public function isPaymentOrderUser(User $user, Order $order)
    {
        return  $user->id === $order->user_id and empty($order->paid_at);
    }
}

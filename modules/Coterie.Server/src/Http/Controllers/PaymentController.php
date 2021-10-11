<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Http\Controllers;

use iBrand\Component\Pay\Facades\Charge;
use iBrand\Component\Pay\Facades\PayNotify;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\OrderRepository;

class PaymentController extends Controller
{
    protected $memberRepository;

    protected $coterieRepository;

    protected $orderRepository;

    public function __construct(
            MemberRepository $memberRepository,

            CoterieRepository $coterieRepository,

            OrderRepository $orderRepository
        ) {
        $this->memberRepository = $memberRepository;

        $this->coterieRepository = $coterieRepository;

        $this->orderRepository = $orderRepository;
    }

    /**
     * 圈子支付.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function coterie()
    {
        $order_no = request('order_no');

        $order = $this->isPaymentOrderUser($order_no);

        $coterie = $this->coterieRepository->getCoterieMemberByUserID($order->user_id, $order->coterie_id);

        if ($coterie and 'charge' == $coterie->cost_type and empty($coterie->memberWithTrashed)) {
            $data = ['channel' => 'wx_lite',
                      'app' => 'coterie', 'type' => 'default', 'order_no' => $order_no, 'amount' => $order->price, 'client_ip' => \request()->getClientIp(), 'subject' => '加入付费圈子:'.$coterie->name, 'body' => '加入付费圈子:'.$coterie->name, 'extra' => ['openid' => \request('openid'), 'invite_user_code' => request('invite_user_code'), 'uuid' => null], ];

            $charge = Charge::create($data);

            return $this->success(compact('charge'));
        }

        return $this->failed('');
    }

    /**
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function coterieSuccess()
    {
        $charge = Charge::find(request('charge_id'));

        if (!$charge) {
            return $this->failed('支付失败');
        }

        $order = PayNotify::success($charge->type, $charge);

        if ($order and !empty($order->paid_at)) {
            return $this->success($order);
        }

        return $this->failed('支付失败');
    }

    /**
     * 验证是否是自己未付款的订单.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isPaymentOrderUser($order_no)
    {
        $user = request()->user();

        $order = $this->orderRepository->findWhere(['user_id' => $user->id, 'order_no' => $order_no])->first();

        if ($user->cant('isPaymentOrderUser', $order)) {
            throw new \Exception('无权限');
        }

        return $order;
    }
}

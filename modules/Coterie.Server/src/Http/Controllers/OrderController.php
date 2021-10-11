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

use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\OrderRepository;

class OrderController extends Controller
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
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function store()
    {
        $id = request('coterie_id');

        $user = request()->user();

        if (request('is_ios')) {
            return $this->failed('由于相关规范,IOS无法在小程序内加入该果酱圈');
        }

        $coterie = $this->coterieRepository->getCoterieMemberByUserID($user->id, $id);

        if ($coterie and 'charge' == $coterie->cost_type and empty($coterie->memberWithTrashed)) {
            $data['user_id'] = $user->id;

            $data['coterie_id'] = $coterie->id;

            $data['client_id'] = $this->client_id();

            $data['price'] = $coterie->price;

            $data['order_no'] = build_order_no();

            if ($res = $this->orderRepository->create($data)) {
                return $this->success(['order_no' => $res->order_no]);
            }
        }

        return $this->failed('');
    }
}

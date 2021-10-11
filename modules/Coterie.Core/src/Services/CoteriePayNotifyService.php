<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Services;

use Carbon\Carbon;
use DB;
use iBrand\Component\Pay\Contracts\PayNotifyContract;
use iBrand\Component\Pay\Models\Charge;
use iBrand\Coterie\Core\Auth\User as AuthUser;
use iBrand\Coterie\Core\Notifications\JoinCoterie;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\InviteMemberRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\OrderRepository;

class CoteriePayNotifyService implements PayNotifyContract
{
    protected $memberRepository;

    protected $orderRepository;

    protected $coterieRepository;

    protected $coterieService;

    protected $inviteMemberRepository;

    public function __construct(
        MemberRepository $memberRepository,

        OrderRepository $orderRepository,

        CoterieService $coterieService,

        CoterieRepository $coterieRepository,

        InviteMemberRepository $inviteMemberRepository
    ) {
        $this->memberRepository = $memberRepository;

        $this->orderRepository = $orderRepository;

        $this->coterieRepository = $coterieRepository;

        $this->coterieService = $coterieService;

        $this->inviteMemberRepository = $inviteMemberRepository;
    }

    public function success(Charge $charge)
    {
        $order = $this->orderRepository->findByField('order_no', $charge->order_no)->first();

        if (!$order || $order->paid_at) {
            return $order;
        }

        //获取圈子信息
        $coterie = $this->coterieRepository->getCoterieMemberByUserID($order->user_id, $order->coterie_id);

        if (!$coterie || $coterie->memberWithTrashed) {
            return false;
        }

        $user = AuthUser::find($order->user_id);

        if (1 == $charge->paid and $charge->amount == $order->price) {
            try {
                DB::beginTransaction();

                $order->paid_at = Carbon::now()->toDateString();

                $order->save();

                //邀请码加入圈子

                $invite_user_code = isset($charge->extra['invite_user_code']) ? $charge->extra['invite_user_code'] : null;

                if ($invite_user_code) {
                    $member = $this->memberRepository->findByField('id', coterie_invite_decode($invite_user_code))->first();

                    if ($member and !$this->inviteMemberRepository->getInviteMemberByCoterieId($member->coterie_id, $user->id)) {
                        $this->inviteMemberRepository->create(['coterie_id' => $member->coterie_id, 'user_id' => $member->user_id, 'inviter_user_id' => $user->id, 'client_id' => client_id()]);
                    }
                }

                $member = $this->memberRepository->createByUser($user, $order->coterie_id, 'normal');

                if ($member) {
                    $this->coterieService->updateTypeCountByID($order->coterie_id, 'member_count', 1);
                }

                //加入圈子通知
                AuthUser::find($coterie->user_id)->notify(new JoinCoterie($coterie, $member));

                DB::commit();

                return $order;
            } catch (\Exception $exception) {
                DB::rollBack();

                \Log::info($exception);

                throw  new \Exception($exception);
            }
        }
    }
}

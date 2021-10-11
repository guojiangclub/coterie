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

use Carbon\Carbon;
use DB;
use iBrand\Coterie\Core\Auth\User as AuthUser;
use iBrand\Coterie\Core\Notifications\CancelSetupGuest;
use iBrand\Coterie\Core\Notifications\JoinCoterie;
use iBrand\Coterie\Core\Notifications\SetupGuest;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\InviteMemberRepository;
use iBrand\Coterie\Core\Repositories\InviteRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Services\CoterieService;
use iBrand\Coterie\Server\Resources\Member;
use iBrand\Coterie\Server\Transformers\MemberTransformer;
use Validator;

class MemberController extends Controller
{
    protected $memberRepository;

    protected $coterieRepository;

    protected $inviteRepository;

    protected $coterieService;

    protected $inviteMemberRepository;

    public function __construct(
        MemberRepository $memberRepository,

        CoterieRepository $coterieRepository,

        CoterieService $coterieService,

        InviteRepository $inviteRepository,

        InviteMemberRepository $inviteMemberRepository
    ) {
        $this->memberRepository = $memberRepository;

        $this->coterieRepository = $coterieRepository;

        $this->coterieService = $coterieService;

        $this->inviteRepository = $inviteRepository;

        $this->inviteMemberRepository = $inviteMemberRepository;
    }

    /**
     * 获取圈子会员列表.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        $id = request('coterie_id');

        $name = request('name');

        $limit = request('limit') ? request('limit') : 10;

        $is_forbidden = 0 == request('is_forbidden') ? 0 : 1;

        $this->isCoterieUser($id);

        $owner_list = $this->memberRepository->getListByCoterieID($id, $name, 0, $type = 'owner', $is_forbidden);

        $guest_list = $this->memberRepository->getListByCoterieID($id, $name, 0, $type = 'guest', $is_forbidden);

        $normal_list = $this->memberRepository->getListByCoterieID($id, $name, $limit, $type = 'normal', $is_forbidden);

        $forbidden_list = $this->memberRepository->getListByCoterieID($id, $name, 0, '', 1);

        //return $this->response()->paginator($normal_list, new MemberTransformer())->setMeta(['owner_list'=>$owner_list,'guest_list'=>$guest_list,'forbidden_list'=>$forbidden_list]);
        return $this->paginator($normal_list, Member::class, ['owner_list' => $owner_list, 'guest_list' => $guest_list, 'forbidden_list' => $forbidden_list]);
    }

    /**
     * 加入免费圈子.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Exception
     */
    public function store()
    {
        $id = request('coterie_id');

        $user = request()->user();

        $coterie = $this->IsAllowCreateMember($user->id, $id);

        $status = false;

        if (empty($coterie->memberWithTrashed) || 2 == $coterie->memberWithTrashed->is_forbidden) {
            $status = true;
        }

        if ($coterie and 'free' == $coterie->cost_type and $status) {
            try {
                DB::beginTransaction();

                //邀请码加入圈子

                if (coterie_invite_decode(request('invite_user_code'))) {
                    $member = $this->memberRepository->findByField('id', coterie_invite_decode(request('invite_user_code')))->first();

                    if ($member and !$this->inviteMemberRepository->getInviteMemberByCoterieId($member->coterie_id, $user->id)) {
                        $this->inviteMemberRepository->create(['coterie_id' => $member->coterie_id, 'user_id' => $member->user_id, 'inviter_user_id' => $user->id, 'client_id' => $this->client_id()]);
                    }
                }

                $member = $this->memberRepository->createByUser($user, $id, 'normal');

                if ($member) {
                    $this->coterieService->updateTypeCountByID($id, 'member_count', 1);
                }

                //加入圈子通知
                AuthUser::find($coterie->user_id)->notify(new JoinCoterie($coterie, $member));

                DB::commit();

                return $this->success($member);
            } catch (\Exception $exception) {
                DB::rollBack();

                throw  new \Exception('created error');
            }
        }

        return $this->failed('无法加入该圈子，请联系客服');
    }

    /**
     * 获取嘉宾邀请码code.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getInviteCode()
    {
        $id = request('coterie_id');

        $this->isCoterieOwner($id);

        $res = $this->inviteRepository->create(['coterie_id' => $id, 'user_type' => 'guest', 'client_id' => $this->client_id(), 'code' => build_order_no('C')]);

        if ($res) {
            return $this->success(['code' => $res->code]);
        }

        return $this->failed('');
    }

    /**
     * 通过邀请码code加入圈子.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Exception
     */
    public function storeByCode()
    {
        $code = request('code');

        $user = request()->user();

        $invite = $this->inviteRepository->findWhere(['code' => request('code')])->first();

        if (!$invite || $invite->used_at) {
            return $this->failed('邀请码不存在或已使用');
        }

        $coterie = $this->IsAllowCreateMember($user->id, $invite->coterie_id);

        if ($coterie and empty($coterie->memberWithTrashed)) {
            try {
                DB::beginTransaction();

                $member = $this->memberRepository->createByUser($user, $invite->coterie_id, $invite->user_type);

                if ($member) {
                    $this->coterieService->updateTypeCountByID($invite->coterie_id, 'member_count', 1);

                    $invite->used_at = Carbon::now()->toDateString();

                    $invite->save();
                }

                //加入圈子通知
                AuthUser::find($coterie->user_id)->notify(new JoinCoterie($coterie, $member));

                DB::commit();

                return $this->success($member);
            } catch (\Exception $exception) {
                DB::rollBack();

                throw  new \Exception('created error');
            }
        }

        return $this->failed('无法加入该圈子，请联系客服');
    }

    /**
     * 修改会员类型会员或嘉宾.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Exception
     */
    public function updateUserType()
    {
        $rules = [
            'member_id' => 'required',
            'user_type' => 'required |in:normal,guest',
            'coterie_id' => 'required',
        ];

        $validator = Validator::make(
            request()->all(),
            $rules
        );
        if ($validator->fails()) {
            $warnings = $validator->messages();
            $warning = $warnings->first();
            throw  new \Exception($warning);
        }

        $coterie = $this->isCoterieOwner(request('coterie_id'));

        $member = $this->memberRepository->findWhere(['id' => request('member_id'), 'coterie_id' => request('coterie_id')])->first();

        if ($member and 'owner' != $member->user_type) {
            $member_user_type = $member->user_type;

            $member->user_type = request('user_type');

            $member->save();

            if ('guest' == $member_user_type and 'guest' != $member->user_type) {
                //取消嘉宾通知
                AuthUser::find($member->user_id)->notify(new CancelSetupGuest($coterie, $member));
            }

            if ('guest' != $member_user_type and 'guest' == $member->user_type) {
                //设置嘉宾通知
                AuthUser::find($member->user_id)->notify(new SetupGuest($coterie, $member));
            }

            return $this->success();
        }

        return $this->failed('error');
    }

    /**
     * 禁言接口.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function forbidden()
    {
        $this->isCoterieOwner(request('coterie_id'));

        $member = $this->memberRepository->findWhere(['id' => request('member_id'), 'coterie_id' => request('coterie_id')])->first();

        if ($member and 'owner' != $member->user_type) {
            $member->is_forbidden = 0 == request('is_forbidden') ? 0 : 1;

            $member->save();

            return $this->success();
        }

        return $this->failed('error');
    }

    /**
     * 删除会员.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        $this->isCoterieOwner(request('coterie_id'));

        try {
            DB::beginTransaction();

            $member = $this->memberRepository->deleteWhere(['id' => request('member_id'), 'coterie_id' => request('coterie_id')]);

            if ($member) {
                $this->coterieService->updateTypeCountByID(request('coterie_id'), 'member_count', -1);

                DB::commit();

                return $this->success();
            }

            return $this->failed('error');
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }

    /**
     * 退出圈子.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function quit()
    {
        $coterie_id = request('coterie_id');

        $member = $this->isCoterieUser($coterie_id);

        if ('owner' == $member->user_type) {
            return $this->failed('');
        }

        try {
            DB::beginTransaction();

            $member_info = $this->memberRepository->findWhere(['user_id' => $member->user_id, 'is_forbidden' => 0, 'coterie_id' => request('coterie_id')])->first();

            $member_info->is_forbidden = 2;

            $member_info->save();

            $res = $member_info->delete();

            if ($res) {
                $this->coterieService->updateTypeCountByID(request('coterie_id'), 'member_count', -1);

                DB::commit();

                return $this->success();
            }

            return $this->failed('error');
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }

    /**
     * @param $user_id
     * @param $coterie_id
     *
     * @return mixed
     */
    protected function IsAllowCreateMember($user_id, $coterie_id)
    {
        return $this->coterieRepository->getCoterieMemberByUserID($user_id, $coterie_id);
    }
}

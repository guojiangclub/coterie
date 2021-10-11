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

use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use Illuminate\Support\Facades\DB;

class CoterieService
{
    protected $coterieRepository;

    protected $memberRepository;

    protected $contentRepository;

    public function __construct(
        CoterieRepository $coterieRepository, MemberRepository $memberRepository, ContentRepository $contentRepository
    ) {
        $this->coterieRepository = $coterieRepository;

        $this->memberRepository = $memberRepository;

        $this->contentRepository = $contentRepository;
    }

    /**
     * @param $input
     * @param $user_id
     * @param string $cost_type
     * @param int    $price
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function created($input, $user, $cost_type = 'free', $price = 0)
    {
        $input['user_id'] = $user->id;
        $input['client_id'] = client_id();
        $input['member_count'] = 1;

        try {
            DB::beginTransaction();
            $coterie = $this->coterieRepository->create($input);
            $this->memberRepository->createByUser($user, $coterie->id, 'owner');

            DB::commit();

            return $coterie;
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception('创建失败');
        }
    }

    public function getCoterieInfo($coterie_id, $user = null)
    {
        $info = $this->coterieRepository->getInfoByID($coterie_id);

        if ($info) {
            $list = $this->contentRepository->getListRecommended($coterie_id, 3);

            $info->content = $list;

            $info->is_coterie_member = 0;

            $info->coterie_user_type = '';

            $info->is_perfect_user_info = 0;

            $info->invite_user_code = '';

            if ($user) {
                $member = $this->memberRepository->findWhere(['user_id' => $user->id, 'coterie_id' => $coterie_id, 'is_forbidden' => 0])->first();

                $info->is_coterie_member = $member ? 1 : 0;

                $info->coterie_user_type = isset($member->user_type) ? $member->user_type : '';

                $info->is_perfect_user_info = $user->nick_name ? 1 : 0;

                $info->invite_user_code = $member ? coterie_invite_encode($member->id) : '';
            }

            $login_user = user_meta_array(auth('api')->user());

            $info->login_user_meta = count($login_user) ? $login_user : null;
        }

        return $info;
    }

    /**
     * @param $id
     * @param $type
     * @param $num
     *
     * @return bool|mixed
     */
    public function updateTypeCountByID($id, $type, $num)
    {
        $coterie = $this->coterieRepository->findByField('id', $id)->first();

        if ($coterie) {
            $coterie->increment("$type", $num);

            $coterie->save();

            return $coterie;
        }

        return null;
    }
}

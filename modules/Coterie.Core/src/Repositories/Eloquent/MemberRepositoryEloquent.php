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

use Carbon\Carbon;
use iBrand\Coterie\Core\Models\Member;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class MemberRepositoryEloquent extends BaseRepository implements MemberRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Member::class;
    }

    /**
     * @param $user
     * @param $coterie_id
     * @param string $user_type
     *
     * @return mixed
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function createByUser($user, $coterie_id, $user_type = 'normal')
    {
        $data['coterie_id'] = $coterie_id;
        $data['user_id'] = $user->id;
        $data['user_meta'] = user_meta($user);
        $data['user_type'] = $user_type;
        $data['client_id'] = client_id();
        $data['joined_at'] = Carbon::now()->toDateTimeString();

        return $this->model->create($data);
    }

    /**
     * @param $coterie_id
     * @param $user_type
     * @param int $is_forbidden
     *
     * @return mixed
     */
    public function getListByCoterieID($coterie_id, $name, $limit = 10, $user_type = 'normal', $is_forbidden = 0)
    {
        $query = $this->model->where('coterie_id', $coterie_id)
            ->where('is_forbidden', $is_forbidden);

        if (!empty($user_type)) {
            $query = $query->where('user_type', $user_type);
        }

        if (!empty($name)) {
            $query = $query->whereHas('user', function ($query) use ($name) {
                return $query->where('nick_name', 'like', '%'.$name.'%');
            });
        }

        $list = $query->orderBy('created_at', 'desc');

        if ($limit > 0) {
            return $list->paginate($limit);
        }

        return $list->get();
    }

    /**
     * @param $user_id
     * @param $coterie_id
     *
     * @return mixed
     */
    public function getMemberByCoterieID($user_id, $coterie_id)
    {
        return $this->model->withTrashed()
            ->where('coterie_id', $coterie_id)
            ->where('user_id', $user_id)
            ->with('coterie')->first();
    }

    /**
     * @param $user_id
     * @param $coterie_id
     *
     * @return mixed
     */
    public function getMemberInfo($user_id, $coterie_id)
    {
        return $this->model->where('coterie_id', $coterie_id)
            ->where('user_id', $user_id)->where('is_forbidden', 0)
            ->whereHas('coterie')->with('coterie')->with('coterie.memberOwner')
            ->first();
    }
}

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

use iBrand\Coterie\Core\Models\Coterie;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class Repository.
 */
class CoterieRepositoryEloquent extends BaseRepository implements CoterieRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Coterie::class;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public function getInfoByID($id, $limit = 5)
    {
        return $this->model->with('user')->with('memberGuest')
            ->with(['memberNormal' => function ($query) use ($limit) {
                $query->orderBy('created_at', 'desc')->limit($limit);
            }])
            ->find($id);
    }

    /**
     * @return mixed
     */
    public function getRecommendCoterie()
    {
        return $this->model->with('user')
            ->whereNotNull('recommend_at')
            ->orderBy('recommend_at', 'desc')
            ->orderBy('created_at', 'desc')->get();
    }

    /**
     * @param $name
     * @param int $limit
     *
     * @return mixed
     */
    public function getCoterieByName($name, $limit = 10)
    {
        return $this->model->with('user')
            ->where('name', 'like', '%'.$name.'%')
            ->orderBy('recommend_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    /**
     * @param $user_id
     * @param int $limit
     *
     * @return mixed
     */
    public function getCoterieByUserID($user_id, $limit = 10)
    {
        return $this->model->with('user')->whereHas('member', function ($query) use ($user_id) {
            return $query->where('user_id', $user_id)->where('is_forbidden', 0);
        })->orderBy('recommend_at', 'desc')->orderBy('created_at', 'desc')->paginate($limit);
    }

    /**
     * @param $user_id
     * @param $coterie_id
     *
     * @return mixed
     */
    public function getCoterieMemberByUserID($user_id, $coterie_id)
    {
        return $this->model->with('user')
            ->where('id', $coterie_id)->with(['memberWithTrashed' => function ($query) use ($user_id, $coterie_id) {
                return $query->where('user_id', $user_id)->where('coterie_id', $coterie_id);
            }])->first();
    }

    /**
     * @param $coterie_id
     *
     * @return mixed
     */
    public function getCoterieByID($coterie_id)
    {
        return $this->model->with('user')->where('id', $coterie_id)->first();
    }
}

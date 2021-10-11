<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Repositories;

use iBrand\Component\User\Models\User;
use iBrand\Component\User\Repository\UserRepository;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    use CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Get a user by the given credentials.
     *
     * @param array $credentials
     *
     * @return mixed
     */
    public function getUserByCredentials(array $credentials)
    {
        $query = $this->model;
        foreach ($credentials as $key => $value) {
            if (!Str::contains($key, 'password') and !empty($value)) {
                $query = $query->where($key, $value);
            }
        }

        return $query->first();
    }

    public function getUserPaginate($where, $limit = 15)
    {
        $data = $this->scopeQuery(function ($query) use ($where) {
            if (is_array($where) and count($where) > 0) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        list($operate, $va) = $value;
                        $query = $query->where($key, $operate, $va);
                    } else {
                        $query = $query->where($key, $value);
                    }
                }
            }

            return $query->orderBy('created_at', 'desc');
        });
        if (0 == $limit) {
            return $data->all();
        }

        return $data->paginate($limit);
    }
}

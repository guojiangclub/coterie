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

use iBrand\Common\Controllers\Controller as BaseController;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

abstract class Controller extends BaseController
{
    protected $memberRepository;
    protected $coterieRepository;

    public function __construct(
        MemberRepository $memberRepository, CoterieRepository $coterieRepository
    ) {
        $this->memberRepository = $memberRepository;
        $this->coterieRepository = $coterieRepository;
    }

    /**
     * @return array|string
     */
    public function client_id()
    {
        if ('public' != env('SAAS_SERVER_TYPE')) {
            return null;
        }

        if (request('appid')) {
            return request('appid');
        }

        return request()->header('appid') ? request()->header('appid') : '';
    }

    /**
     * 验证是否是该圈成员.
     *
     * @param $coterie_id
     * @param null $user
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function isCoterieUser($coterie_id, $user = null)
    {
        null == $user ? $user = request()->user() : $user;

        $member = $this->memberRepository->getMemberInfo($user->id, $coterie_id);

        if ($user->cant('isCoterieUser', $member)) {
            throw new \Exception('无权限');
        }

        return $member;
    }

    /**
     * 验证是否是圈主.
     *
     * @param $id
     * @param null $user
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function isCoterieOwner($id, $user = null)
    {
        null == $user ? $user = request()->user() : $user;

        $coterie = $this->coterieRepository->with('user')->findByField('id', $id)->first();

        if ($user->cant('isCoterieOwner', $coterie)) {
            throw new \Exception('无权限');
        }

        return $coterie;
    }

    /**
     * @param array $list
     * @param int   $perPage
     *
     * @return array|null
     */
    protected function setPaginator(array $list, $perPage = 10)
    {
        if (0 == count($list)) {
            return null;
        }

        if (request()->has('page')) {
            $current_page = request()->input('page');
            $current_page = $current_page <= 0 ? 1 : $current_page;
        } else {
            $current_page = 1;
        }
        $item = array_slice($list, ($current_page - 1) * $perPage, $perPage); //注释1
        $total = count($list);
        $paginator = new LengthAwarePaginator($item, $total, $perPage, $current_page, [
            'path' => Paginator::resolveCurrentPath(), //注释2
            'pageName' => 'page',
        ]);
        $page = [];
        foreach ($paginator as $item) {
            $page['data'][] = $item;
        }
        $page['meta']['total'] = $paginator->total();
        $page['meta']['count'] = $paginator->count();
        $page['meta']['per_page'] = $perPage;
        $page['meta']['current_page'] = $current_page;
        $page['meta']['total_pages'] = $paginator->lastPage();

        return $page;
    }
}

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

use iBrand\Component\User\Models\User;
use iBrand\Coterie\Core\Models\Coterie;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Services\CoterieService;
use iBrand\Coterie\Core\Services\MiniProgramService;
use iBrand\Miniprogram\Poster\MiniProgramShareImg;
use Illuminate\Validation\Rule;
use Storage;
use Validator;

class CoterieController extends Controller
{
    protected $coterieRepository;

    protected $coterieService;

    protected $contentRepository;

    protected $memberRepository;

    protected $miniProgramService;

    protected $miniProgram;

    public function __construct(
        CoterieRepository $coterieRepository, CoterieService $coterieService, ContentRepository $contentRepository, MemberRepository $memberRepository, MiniProgramService $miniProgramService
    ) {
        $this->coterieRepository = $coterieRepository;
        $this->coterieService = $coterieService;
        $this->contentRepository = $contentRepository;
        $this->memberRepository = $memberRepository;
        $this->miniProgramService = $miniProgramService;
    }

    public function test()
    {
    }

    /**
     * 创建圈子.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Exception
     */
    public function store()
    {
        $messages = [
            'name.unique' => '已经存在此名称',
        ];

        $validator = Validator::make(request()->all(), [
            'name' => 'required|unique:'.(new Coterie())->getTable().'|max:255',
            'description' => 'required',
            'avatar' => 'required',
            'duration_type' => 'required |in:joined,deadline,deadline',
            'cost_type' => 'required |in:free,charge',
            'price' => 'integer|required_if:cost_type,charge',
        ], $messages);

        if ($validator->fails()) {
            return $this->failed($validator->errors());
        }

        $user = request()->user();

        $input = request()->only('name', 'description', 'avatar', 'duration_type', 'notice', 'price', 'cost_type');

        $input['price'] = 'charge' == $input['cost_type'] ? $input['price'] : 0;

        if ($res = $this->coterieService->created($input, $user, $input['cost_type'], $input['price'])) {
            return $this->success($res);
        }

        return $this->failed('创建失败');
    }

    /**
     * 编辑圈子.
     *
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit()
    {
        $coterie = $this->isCoterieOwner(request('coterie_id'));

        return $this->success($coterie);
    }

    /**
     * 更新圈子基本数据.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update()
    {
        $id = request('coterie_id');

        $this->isCoterieOwner($id);

        $this->updatePost($id);

        $input = request()->only('name', 'description', 'avatar', 'notice');

        if ($res = $this->coterieRepository->update($input, $id)) {
            return $this->success($res);
        }

        return $this->failed('updated error');
    }

    /**
     * @param $id
     *
     * @return \Dingo\Api\Http\Response
     */
    public function show()
    {
        $id = request('coterie_id');

        $user = auth('api')->user();

        $list = $this->coterieService->getCoterieInfo($id, $user);

        return $this->success($list);
    }

    /**
     * 获取推荐圈子.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function getRecommend()
    {
        $limit = request('limit') ? request('limit') : 5;

        $list = $this->coterieRepository->getRecommendCoterie();

        $count = $list->count();

        if ($count) {
            $data = $list->random($limit >= $count ? $count : $limit);

            return $this->success($data);
        }

        return $this->success([]);
    }

    /**
     * 圈子搜索接口.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function search()
    {
        $name = request('name') ? request('name') : '';

        $limit = request('limit') ? request('limit') : 10;

        $list = $this->coterieRepository->getCoterieByName($name, $limit);

        //return $this->response()->paginator($list, new CoterieTransformer());
        return $this->paginator($list, \iBrand\Coterie\Server\Resources\Coterie::class);
    }

    /**
     * 解散圈子.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        $coterie_id = request('coterie_id');

        $this->isCoterieOwner($coterie_id);

        if ($res = $this->coterieRepository->delete($coterie_id)) {
            return $this->success($res);
        }

        return $this->failed('');
    }

    /**
     * 数据圈分享.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function share()
    {
        if (!request('coterie_id')) {
            return $this->failed('参数错误');
        }

        $coterie = $this->coterieRepository->with('user')->findByField('id', request('coterie_id'))->first();

        $user = user_meta_array(User::find(request('user_id')));

        return view('coterie::share.coterie', compact('coterie', 'user'));
    }

    /**
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function getCoterieImage()
    {
        if (!request('invite_user_code')) {
            return $this->failed('参数错误');
        }

        $pages = request('pages') ? request('pages') : 'pages/index/index/index';

        $member = $this->memberRepository->findByField('id', coterie_invite_decode(request('invite_user_code')))->first();

        if (!$member) {
            return $this->failed('invite_user_code不存在');
        }

        $scene = $member->coterie_id.'_'.request('invite_user_code');

        //获取小程序码
        $type = 'coterie';

        $mini_code = $this->miniProgramService->createMiniQrcode($pages, 800, $scene, $member->coterie_id, $type);

        $mini_qrcode = env('APP_URL').'/storage/'.$type.'/'.$member->coterie_id.'/'.'mini_qrcode.jpg';

        \Log::info($mini_qrcode);

        if (!$mini_code) {
            return $this->failed('生成小程序码失败');
        }

        $route = url('api/coterie/share').'/?user_id='.$member->user_id.'&coterie_id='.$member->coterie_id.'&appid='.$this->client_id().'&scene='.$scene.'&mini_code='.$mini_qrcode;

        $result = MiniProgramShareImg::generateShareImage($route, 'share_coterie');

        if ($result and isset($result['url'])) {
            $img = Storage::disk('MiniProgramShare')->get($result['path']);

            $savePath = $result['path'];

            if (client_id()) {
                $savePath = client_id().'/'.$result['path'];
            }

            Storage::disk('qiniu')->put($savePath, $img);

            $result_qiniu = Storage::disk('qiniu')->url($savePath);

            $result['url'] = $result_qiniu;

            return $this->success($result);
        }

        return $this->failed([]);
    }

    /**
     * 获取用户加入圈子.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function userCoterie()
    {
        $user = request()->user();

        $limit = request('limit') ? request('limit') : 10;

        $list = $this->coterieRepository->getCoterieByUserID($user->id, $limit);

        //return $this->response()->paginator($list, new CoterieTransformer())->setMeta(['is_perfect_user_info' => $user->nick_name ? 1 : 0]);
        return $this->paginator($list, \iBrand\Coterie\Server\Resources\Coterie::class, ['is_perfect_user_info' => $user->nick_name ? 1 : 0]);
    }

    protected function updatePost($id)
    {
        $toble = config('ibrand.app.database.prefix', 'ibrand_').'coterie';

        $rules = [
            'name' => [
                'required',
                Rule::unique($toble)->ignore($id),
                'max:255',
            ],
            'description' => 'required',
            'avatar' => 'required',
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
    }
}

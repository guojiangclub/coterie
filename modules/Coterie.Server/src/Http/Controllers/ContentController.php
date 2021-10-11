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
use Conner\Tagging\Model\TagGroup;
use iBrand\Component\User\Models\User;
use iBrand\Coterie\Core\Auth\User as AuthUser;
use iBrand\Coterie\Core\Notifications\AnswerQuestion;
use iBrand\Coterie\Core\Notifications\AtUser;
use iBrand\Coterie\Core\Notifications\PublishContent;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\QuestionRepository;
use iBrand\Coterie\Core\Services\ContentService;
use iBrand\Coterie\Core\Services\MiniProgramService;
use iBrand\Coterie\Server\Resources\Content;
use iBrand\Miniprogram\Poster\MiniProgramShareImg;
use QL\QueryList;
use Storage;
use Validator;

class ContentController extends Controller
{
    protected $contentRepository;

    protected $contentService;

    protected $memberRepository;

    protected $coterieRepository;

    protected $questionRepository;

    protected $miniProgramService;

    public function __construct(
        ContentRepository $contentRepository, ContentService $contentService, MemberRepository $memberRepository, CoterieRepository $coterieRepository, QuestionRepository $questionRepository, MiniProgramService $miniProgramService
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentService = $contentService;
        $this->memberRepository = $memberRepository;
        $this->coterieRepository = $coterieRepository;
        $this->questionRepository = $questionRepository;
        $this->miniProgramService = $miniProgramService;
    }

    /**
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $coterie_id = request('coterie_id');

        $member = $this->isCoterieUser($coterie_id);

        $limit = request('limit') ? request('limit') : 10;

        $comment_limit = request('comment_limit') ? request('comment_limit') : 10;

        $user_id = null;

        $style_type = null;

        $tag = [];

        if ('user' == request('type')) {
            $user_id = request('value');
        }

        if ('question' == request('type')) {
            $style_type = 'question';
        }

        if ('tag' == request('type')) {
            $value = request('value');

            $tag = [$value];
        }

        $list = $this->contentRepository->getLists($coterie_id, $user_id, $style_type, $tag, $limit, $comment_limit);

        //return $this->response()->paginator($list, new ContentTransformer())->setMeta(['member' => $member]);
        return $this->paginator($list, Content::class, ['member' => $member]);
    }

    /**
     * 创建动态内容或回答提问.
     *
     * @return \Dingo\Api\Http\Response
     */
    public function store()
    {
        $member = $this->isCoterieUser(request('coterie_id'));

        $this->DefaultPost();

        $input = request()->only('coterie_id', 'style_type', 'content_type', 'link', 'description', 'img_list', 'tags_list', 'audio_list', 'recommended_at', 'stick_at');

        $input['user_id'] = $member->user_id;

        $input['meta']['user'] = user_meta_array();

        //@某人
        $at_user = null;
        if (request('at_user_id')) {
            if (!$at_user = AuthUser::find(request('at_user_id'))) {
                return $this->failed('at_user_id不合法');
            }
            $at_user_member = $this->isCoterieUser(request('coterie_id'), $at_user);
            if ($at_user_member->user_id == $member->user_id) {
                return $this->failed('不能@自己');
            }
            $input['meta']['at_user'] = user_meta_array($at_user);
        }

        $input['meta'] = json_encode($input['meta']);

        if ('question' == $input['style_type']) {
            $question = $this->questionRepository->findWhere(['answer_user_id' => $member->user_id, 'coterie_id' => request('coterie_id'), 'id' => request('question_id')])->first();

            if (empty($question) || !empty($question->content_id)) {
                return $this->failed('该问题不存在或已回答');
            }
        }

        $question_id = request('question_id') ? request('question_id') : 0;

        $res = $this->contentService->created($input, $question_id);

        //动态@某人消息通知
        //发表动态通知
        if (isset($res->style_type) and 'default' == $res->style_type) {
            AuthUser::find($member->coterie->user_id)->notify(new PublishContent($member->coterie, user_meta_array(), $res));

            if ($at_user) {
                $at_user->notify(new AtUser($member->coterie, user_meta_array(), $res, user_meta_array($at_user)));
            }
        }

        //回答问题消息通知
        if (isset($res->style_type) and 'question' == $res->style_type) {
            AuthUser::find($question->user_id)->notify(new AnswerQuestion($member->coterie, user_meta_array(), $res, $question));
        }

        return $this->success($res);
    }

    /**
     * edit.
     *
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit()
    {
        $content = $this->isContentUser(request('content_id'));

        $this->isCoterieUser($content->coterie_id);

        return $this->success($content);
    }

    /**
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update()
    {
        $content = $this->isContentUser(request('content_id'));

        $this->isCoterieUser($content->coterie_id);

        $this->DefaultUpdatePost();

        $input = request()->only('content_type', 'link', 'description', 'img_list', 'tags_list', 'audio_list', 'recommended_at', 'stick_at');

        if ($input['content_type'] = 'link') {
            $input['link'] = request('link');
        }

        $input['img_list'] = isset($input['img_list']) ? json_encode($input['img_list']) : null;

        $input['tags_list'] = isset($input['tags_list']) ? json_encode($input['tags_list']) : null;

        $input['audio_list'] = isset($input['audio_list']) ? json_encode($input['audio_list']) : null;

        $res = $this->contentRepository->update($input, $content->id);

        $coterie = $this->coterieRepository->findByField('id', $content->coterie_id)->first();

        if ($input['tags_list']) {
            $group = TagGroup::firstOrCreate(['slug' => 'coterie-'.$content->coterie_id, 'name' => 'coterie-'.$content->coterie_id]);

            foreach (json_decode($input['tags_list'], true) as $item) {
                $tags_list[] = $item.'-'.$coterie->id;
            }

            $content->untag();

            $content->tag($tags_list);

            $content->save();

            $filtered = $content->with('tagged')->find($content->id)->tagged->filter(function ($item) use ($group) {
                $item->tag->tag_group_id = $group->id;

                $item->tag->save();
            });

            $coterie->tag($tags_list);

            $coterie->save();
        }

        return $this->success($res);
    }

    /**
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show()
    {
        $id = request('content_id');

        $info = $this->contentRepository->getContentByID($id);

        if ($info) {
            $member = $this->isCoterieUser($info->coterie_id);

            $user = request()->user();

            $info->is_content_user = $info->user->id == $user->id ? 1 : 0;

            $info->coterie_user_type = isset($member->user_type) ? $member->user_type : '';

            $info->invite_user_code = isset($member->id) ? coterie_invite_encode($member->id) : null;

            $user_meta = user_meta();

            $info->lgoin_user_meta_info = $user_meta ? json_decode($user_meta, true) : null;

            return $this->success($info);
        }

        return $this->failed('内容不存在或已删除');
    }

    /**
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function showInfo()
    {
        $id = request('content_id');

        $info = $this->contentRepository->getContentByID($id);

        if ($info) {
            $user = auth('api')->user();

            $info->is_content_user = isset($user->id) and $info->user->id == $user->id ? 1 : 0;

            $member = null;

            if ($user) {
                $member = $this->memberRepository->findWhere(['user_id' => $user->id, 'coterie_id' => $info->coterie_id, 'is_forbidden' => 0])->first();
            }

            $info->is_coterie_member = $member ? 1 : 0;
        }

        return $this->success($info);
    }

    /**
     * 内容分享.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function share()
    {
        if (!request('content_id')) {
            return $this->failed('参数错误');
        }

        $content = $this->contentRepository->getContentByID(request('content_id'));

        $user = user_meta_array(User::find(request('user_id')));

        return view('coterie::share.content', compact('content', 'user'));
    }

    /**
     * @return \Dingo\Api\Http\Response|mixed
     */
    public function getContentImage()
    {
        if (!request('invite_user_code')) {
            return $this->failed('参数错误');
        }

        $pages = request('pages') ? request('pages') : 'pages/index/index/index';

        $content = $this->contentRepository->getContentByID(request('content_id'));

        $coterie_id = isset($content->coterie_id) ? $content->coterie_id : 0;

        $member = $this->isCoterieUser($coterie_id);

        $scene = $member->coterie_id.'_'.request('content_id').'_'.request('invite_user_code');

        //获取小程序码
        $type = 'content';

        $mini_code = $this->miniProgramService->createMiniQrcode($pages, 800, $scene, request('content_id'), $type);

        if (!$mini_code) {
            return $this->failed('生成小程序码失败');
        }
        $mini_qrcode = env('APP_URL').'/storage/'.$type.'/'.request('content_id').'/'.'mini_qrcode.jpg';

        \Log::info($mini_qrcode);

        $route = url('api/content/share').'/?user_id='.$member->user_id.'&content_id='.request('content_id').'&coterie_id='.$member->coterie_id.'&appid='.$this->client_id().'&scene='.$scene.'&mini_code='.$mini_qrcode;

        $result = MiniProgramShareImg::generateShareImage($route, 'share_coterie_content');

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
     * 获取热门标签.
     *
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getHostTags()
    {
        //$limit=request('limit')?request('limit'):10;

        //$member=$this->isCoterieUser(request('coterie_id'));

        //$list=$this->contentRepository->getHostTags(request('coterie_id'),$limit);

        //return $this->success($list);

        $member = $this->isCoterieUser(request('coterie_id'));

        $coterie = $this->coterieRepository->findByField('id', $member->coterie_id)->first();

        $data[] = 'coterie-'.$member->coterie_id;

        $list = $coterie->existingTagsInGroups($data);

        if ($list->count()) {
            $list = $list->sortByDesc('count')->values()->toArray();

            $list_new = [];

            foreach ($list as $key => $item) {
                $list_new[$key]['name'] = $item['name'];

                $list_new[$key]['count'] = $item['count'] - 1;

                $list_new[$key]['title'] = explode('-', $item['slug'])[0];

                if (request('title')) {
                    if (false == strstr($list_new[$key]['title'], request('title'))) {
                        unset($list_new[$key]);
                    }
                }
            }

            $limit = request('limit') ? request('limit') : 10;

            $pages = $this->setPaginator(array_values($list_new), $limit);

            return $this->success($pages);
        }

        return $this->success([]);
    }

    /**
     * 获取置顶内容.
     *
     * @return \Dingo\Api\Http\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function getContentStickAt()
    {
        $this->isCoterieUser(request('coterie_id'));

        $content = $this->contentRepository->getStickContent(request('coterie_id'));

        return $this->success($content);
    }

    /**
     * 动态内容置顶或取消.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setContentStickAt()
    {
        $coterie_id = request('coterie_id');

        $this->isCoterieOwner($coterie_id);

        $content = $this->contentRepository->findWhere(['style_type' => 'default', 'coterie_id' => $coterie_id, 'id' => request('content_id')])->first();

        $time = 1 == request('type') ? Carbon::now()->toDateTimeString() : null;

        if (!$content) {
            return $this->failed('');
        }

        if ($content_old = $this->contentRepository->getStickContent($coterie_id)) {
            $content_old->stick_at = null;

            $content_old->save();
        }

        $content->stick_at = $time;

        $content->save();

        return $this->success($content);
    }

    /**
     * 设置动态内容或提问推荐或取消.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function setContentRecommendedAt()
    {
        $member = $this->isCoterieUser(request('coterie_id'));

        if (in_array($member->user_type, ['owner,guest'])) {
            return $this->failed('无权限');
        }

        $type = empty(request('type')) ? 0 : 1;

        $content = $this->contentService->setContentRecommendedAt(request('content_id'), request('coterie_id'), $type);

        if ($content) {
            return $this->success($content);
        }

        return $this->failed('');
    }

    /**
     * 删除动态或问答.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function delete()
    {
        $content_id = request('content_id');

        $coterie_id = request('coterie_id');

        $user=request()->user();

		$coterie = $this->coterieRepository->with('user')->findByField('id', $coterie_id)->first();

		if (!$user->cant('isCoterieOwner', $coterie) || $this->isContentUser($content_id)) {

            if ($this->contentService->deleteContent($content_id, $coterie_id)) {

                return $this->success();
            }
        }

        return $this->failed('删除失败');
    }

    /**
     * 动态内容验证
     *
     * @throws \Exception
     */
    protected function DefaultPost()
    {
        $rules = [
            'coterie_id' => 'required',
            'style_type' => 'required |in:default,question',
            'content_type' => 'required |in:default,link,file',
            'description' => 'required',
            'link' => 'required_if:content_type,link',
            'question_id' => 'required_if:style_type,question',
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

    /**
     * 动态内容验证
     *
     * @throws \Exception
     */
    protected function DefaultUpdatePost()
    {
        $rules = [
            'content_id' => 'required',
            'content_type' => 'required |in:default,link,file',
            'description' => 'required',
            'link' => 'required_if:content_type,link',
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

    /**
     * 获取url信息.
     *
     * @param $url
     *
     * @return null|string
     */
    public function queryLink()
    {
        try {
            $url = request('link');

            $ql = QueryList::get($url)->encoding('UTF-8');

            $title = $ql->find('title')->text();

            $img = $ql->find('img')->src;

            $data['link'] = $url;

            $data['title'] = $title;

            if (!empty($img)) {
                $img = preg_replace("/\/\//", '', $img);
                if (0 == strpos($img, '/')) {
                    preg_match("/^(\w+:\/\/)?([^\/]+)/i", $url, $matches);
                    $img = $matches[2].$img;
                }
            }
            $data['img'] = $img;

            return $this->success(['link' => $data, 'link_info' => json_encode($data)]);
        } catch (\Exception $exception) {
            return $this->failed('链接不存在');
        }
    }

    /**
     * 验证是否是自己发布的内容.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isContentUser($content_id)
    {
        $user = request()->user();

        $content = $this->contentRepository->findWhere(['user_id' => $user->id, 'id' => $content_id])->first();

        if ($user->cant('isContentUser', $content)) {
            throw new \Exception('无权限');
        }

        return $content;
    }
}

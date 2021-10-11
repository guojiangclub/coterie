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
use iBrand\Coterie\Core\Auth\User as AuthUser;
use iBrand\Coterie\Core\Notifications\PublishQuestion;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\MemberRepository;
use iBrand\Coterie\Core\Repositories\QuestionRepository;
use iBrand\Coterie\Core\Services\ContentService;
use Validator;

class QuestionController extends Controller
{
    protected $contentRepository;

    protected $contentService;

    protected $memberRepository;

    protected $coterieRepository;

    protected $questionRepository;

    public function __construct(
        ContentRepository $contentRepository, ContentService $contentService, MemberRepository $memberRepository, CoterieRepository $coterieRepository, QuestionRepository $questionRepository
    ) {
        $this->contentRepository = $contentRepository;
        $this->contentService = $contentService;
        $this->memberRepository = $memberRepository;
        $this->coterieRepository = $coterieRepository;
        $this->questionRepository = $questionRepository;
    }

    /**
     * 创建提问.
     *
     * @return \Dingo\Api\Http\Response|mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeQuestion()
    {
        $member = $this->isCoterieUser(request('coterie_id'));

        $guest_owner = $this->isCoterieGuestOwner(request('coterie_id'), request('answer_user_id'));

        if (!$guest_owner) {
            return $this->failed('answer_user_id不合法');
        }

        $this->QuestionPost();

        $input = request()->only('coterie_id', 'answer_user_id', 'content', 'img_list');

        $input['img_list'] = isset($input['img_list']) ? json_encode($input['img_list']) : null;

        $input['user_id'] = $member->user_id;

        $input['client_id'] = $this->client_id();

        $res = $this->questionRepository->create($input);

        //发表问题消息通知
        $answer_user = AuthUser::find(request('answer_user_id'));
        $answer_user->notify(new PublishQuestion($member->coterie, user_meta_array(), $res, user_meta_array($answer_user)));

        return $this->success($res);
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $member = $this->isCoterieUser(request('coterie_id'));

        $this->isQuestionAnswerUser($member->coterie_id, request('question_id'));

        $question = $this->questionRepository->with('user')->findByField('id', request('question_id'))->first();

        return $this->success($question);
    }

    protected function QuestionPost()
    {
        $rules = [
            'answer_user_id' => 'required',
            'content' => 'required',
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
     * 验证是否是该圈管理人员.
     *
     * @param $id
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isCoterieGuestOwner($coterie_id, $user_id)
    {
        $user = User::find($user_id);

        $member = $this->isCoterieUser($coterie_id, $user);

        if (!$member || 'normal' == $member->user_type) {
            return false;
        }

        return true;
    }

    /**
     * @param $user_id
     * @param $coterie_id
     *
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function isQuestionAnswerUser($coterie_id, $question_id)
    {
        $user = request()->user();

        $question = $this->questionRepository->findWhere(['coterie_id' => $coterie_id, 'answer_user_id' => $user->id, 'id' => $question_id])->first();

        if ($user->cant('isQuestionAnswerUser', $question)) {
            throw new \Exception('无权限');
        }

        return $question;
    }
}

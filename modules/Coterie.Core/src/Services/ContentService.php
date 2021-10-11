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
use Conner\Tagging\Model\TagGroup;
use iBrand\Coterie\Core\Repositories\ContentRepository;
use iBrand\Coterie\Core\Repositories\CoterieRepository;
use iBrand\Coterie\Core\Repositories\QuestionRepository;
use Illuminate\Support\Facades\DB;

class ContentService
{
    protected $contentRepository;

    protected $coterieRepository;

    protected $coterieService;

    protected $questionRepository;

    public function __construct(
        ContentRepository $contentRepository, CoterieRepository $coterieRepository, CoterieService $coterieService, QuestionRepository $questionRepository
    ) {
        $this->contentRepository = $contentRepository;
        $this->coterieRepository = $coterieRepository;
        $this->coterieService = $coterieService;
        $this->questionRepository = $questionRepository;
    }

    public function created($input, $question_id = 0)
    {
        try {
            $input['img_list'] = isset($input['img_list']) ? json_encode($input['img_list']) : null;

            $input['tags_list'] = isset($input['tags_list']) ? json_encode($input['tags_list']) : null;

            $input['audio_list'] = isset($input['audio_list']) ? json_encode($input['audio_list']) : null;

            $input['client_id'] = client_id();

            $coterie = null;

            DB::beginTransaction();

            $content = $this->contentRepository->create($input);

            if ($content) {
                $coterie = $this->coterieService->updateTypeCountByID($input['coterie_id'], 'content_count', 1);
            }

            if ('question' == $content->style_type and $question_id > 0) {
                $coterie = $this->coterieService->updateTypeCountByID($input['coterie_id'], 'ask_count', 1);

                $this->questionRepository->update(['content_id' => $content->id], $question_id);
            }

            if ($input['tags_list']) {
                $group = TagGroup::firstOrCreate(['slug' => 'coterie-'.$content->coterie_id, 'name' => 'coterie-'.$content->coterie_id]);

                foreach (json_decode($input['tags_list'], true) as $item) {
                    $tags_list[] = $item.'-'.$coterie->id;
                }

                $content->tag($tags_list);

                $content->save();

                $filtered = $content->with('tagged')->find($content->id)->tagged->filter(function ($item) use ($group) {
                    $item->tag->tag_group_id = $group->id;

                    $item->tag->save();
                });

                $coterie->tag($tags_list);

                $coterie->save();
            }

            DB::commit();

            return $content;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
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
        $content = $this->contentRepository->findByField('id', $id)->first();

        if ($content) {
            $content->increment("$type", $num);

            $content->save();

            return $content;
        }

        return null;
    }

    /**
     * @param $content_id
     * @param $coterie_id
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function setContentRecommendedAt($content_id, $coterie_id, $type = 1)
    {
        try {
            DB::beginTransaction();

            $content = $this->contentRepository->findWhere(['coterie_id' => $coterie_id, 'id' => $content_id])->first();

            $time = 1 == $type ? Carbon::now()->toDateTimeString() : null;

            $num = 1 == $type ? 1 : -1;

            if ($content) {
                if (empty($content->recommended_at) and 0 == $type) {
                    return null;
                }

                if ($content->recommended_at and 1 == $type) {
                    return null;
                }

                $content->recommended_at = $time;

                $content->save();

                $this->coterieService->updateTypeCountByID($coterie_id, 'recommend_count', $num);

                DB::commit();

                return $content;
            }

            return null;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }

    /**
     * @param $content_id
     * @param $coterie_id
     *
     * @return null
     *
     * @throws \Exception
     */
    public function deleteContent($content_id, $coterie_id)
    {
        try {
            DB::beginTransaction();

            $content = $this->contentRepository->findWhere(['coterie_id' => $coterie_id, 'id' => $content_id])->first();

            if ($content) {
                $content->stick_at = null;

                if ($content->recommended_at) {
                    $content->recommended_at = null;

                    $content->save();

                    $this->coterieService->updateTypeCountByID($coterie_id, 'recommend_count', -1);
                }

                if ('default' == $content->style_type) {
                    $this->coterieService->updateTypeCountByID($coterie_id, 'content_count', -1);
                }

                if ('question' == $content->style_type) {
                    $this->coterieService->updateTypeCountByID($coterie_id, 'ask_count', -1);
                }

                $res = $this->contentRepository->delete($content_id);

                DB::commit();

                return $res;
            }

            return null;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }
}

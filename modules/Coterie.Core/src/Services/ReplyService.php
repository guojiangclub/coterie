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

use iBrand\Coterie\Core\Repositories\ReplyRepository;
use Illuminate\Support\Facades\DB;

class ReplyService
{
    protected $replyRepository;

    protected $contentService;

    public function __construct(
        ReplyRepository $replyRepository, ContentService $contentService
    ) {
        $this->replyRepository = $replyRepository;

        $this->contentService = $contentService;
    }

    /**
     * @param $input
     *
     * @return bool
     */
    public function created($input)
    {
        try {
            DB::beginTransaction();

            $input['client_id'] = client_id();

            $input['meta'] = user_meta();

            $reply = $this->replyRepository->create($input);

            if ($reply) {
                //$this->contentService->updateTypeCountByID($input['content_id'],'comment_count',1);
            }

            DB::commit();

            return $reply;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }
}

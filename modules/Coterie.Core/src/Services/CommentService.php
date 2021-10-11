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

use iBrand\Coterie\Core\Repositories\CommentRepository;
use Illuminate\Support\Facades\DB;

class CommentService
{
    protected $commentRepository;

    protected $contentService;

    public function __construct(
         CommentRepository $commentRepository, ContentService $contentService
    ) {
        $this->commentRepository = $commentRepository;

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

            $comment = $this->commentRepository->create($input);

            if ($comment) {
                $this->contentService->updateTypeCountByID($input['content_id'], 'comment_count', 1);
            }

            DB::commit();

            return $comment;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }
}

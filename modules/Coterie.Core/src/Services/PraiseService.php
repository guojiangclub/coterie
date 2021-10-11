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

use iBrand\Coterie\Core\Repositories\PraiseRepository;
use Illuminate\Support\Facades\DB;

class PraiseService
{
    protected $praiseRepository;

    protected $contentService;

    public function __construct(
        PraiseRepository $praiseRepository, ContentService $contentService
    ) {
        $this->praiseRepository = $praiseRepository;

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

            $praise = $this->praiseRepository->create($input);

            if ($praise) {
                $this->contentService->updateTypeCountByID($input['content_id'], 'praise_count', 1);
            }

            DB::commit();

            return $praise;
        } catch (\Exception $exception) {
            DB::rollBack();

            throw  new \Exception($exception);
        }
    }
}

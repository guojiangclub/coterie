<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Console;

use Illuminate\Console\Command;

class DatabaseDeleteCommand extends Command
{
    protected $signature = 'coterie-database:delete {uuid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Coterie Database';

    public function handle()
    {
        $uuid = $this->argument('uuid');

        $websiteRepository = app(\Hyn\Tenancy\Contracts\Repositories\WebsiteRepository::class);

        if (!$website = $websiteRepository->findByUuid($uuid)) {
            $this->info('coterie database <'.$uuid.'> non-existent');
        }

        $websiteRepository->delete($website);

        $this->info('coterie database <'.$uuid.'> delete successfully.');
    }
}

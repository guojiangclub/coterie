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
use Laravel\Passport\Passport;
use phpseclib\Crypt\RSA;

class DatabaseCreateCommand extends Command
{
    protected $signature = 'coterie-database:create {uuid}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Coterie Database';

    public function handle()
    {
        $uuid = $this->argument('uuid');

        $websiteRepository = app(\Hyn\Tenancy\Contracts\Repositories\WebsiteRepository::class);

        if (!$website = $websiteRepository->findByUuid($uuid)) {
            $website = new \Hyn\Tenancy\Models\Website();

            $website->uuid = $uuid;

            $website->managed_by_database_connection = 'mysql';

            $website = $websiteRepository->create($website);
        }

        $environment = app(\Hyn\Tenancy\Environment::class);

        $environment->tenant($website);

        config(['database.default' => 'tenant']);

        $this->loadKeysFrom($uuid);

        $this->call('passport:client', ['--personal' => true, '--name' => config('app.name').' Personal Access Client']);

        $this->call('passport:client', ['--password' => true, '--name' => config('app.name').' Password Grant Client']);

        $this->info('coterie database <'.$uuid.'> create successfully.');
    }

    protected function loadKeysFrom($uuid)
    {
        $path = $uuid;

        if (!is_dir(storage_path($path))) {
            mkdir(storage_path($path), 0777);
        }

        $rsa = new RSA();

        $keys = $rsa->createKey(4096);

        list($publicKey, $privateKey) = [
            Passport::keyPath($path.'/oauth-public.key'),
            Passport::keyPath($path.'/oauth-private.key'),
        ];

        if (!file_exists($publicKey) || !file_exists($privateKey)) {
            file_put_contents($publicKey, array_get($keys, 'publickey'));
            file_put_contents($privateKey, array_get($keys, 'privatekey'));
        }
    }
}

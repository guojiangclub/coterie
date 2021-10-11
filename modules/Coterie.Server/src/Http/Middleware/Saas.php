<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Http\Middleware;

use Auth;
use Closure;
use Hyn\Tenancy\Database\Connection;
use Illuminate\Contracts\Auth\Guard;
use Laravel\Passport\Passport;
use phpseclib\Crypt\RSA;

class Saas
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $connection;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, Connection $connection)
    {
        $this->auth = $auth;

        $this->connection = $connection;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uuid = client_id();

        if (!env('SAAS_SERVER_TYPE') || 'public' == env('SAAS_SERVER_TYPE')) {
            if (request()->is('api/*') and !$uuid) {
                throw new \Exception('非法请求');
            }
        }

//        if ($uuid AND $website = app(\Hyn\Tenancy\Contracts\Repositories\WebsiteRepository::class)->findByUuid($uuid)) {
//
//            $environment = app(\Hyn\Tenancy\Environment::class);
//
//            $environment->tenant($website);
//
//            config(['database.default' => 'tenant']);
//
//        } else {
//
//            config(['database.default' => 'mysql']);
//        }

        $path = $uuid;

        if (!file_exists(storage_path($path).'/oauth-public.key')) {
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

        Passport::loadKeysFrom(storage_path($path));

        return $next($request);
    }
}

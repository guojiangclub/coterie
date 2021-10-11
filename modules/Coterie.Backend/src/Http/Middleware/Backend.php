<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Http\Middleware;

use Closure;
use Cookie;
use Encore\Admin\Auth\Database\Administrator as AdminUser;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Database\Connection;
use iBrand\Component\Account\Models\AccountApplication;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Auth;

class Backend
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $connection;

    protected $db;

    protected $websiteRepository;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, Connection $connection, DatabaseManager $databaseManager, WebsiteRepository $websiteRepository)
    {
        $this->auth = $auth;

        $this->connection = $connection;

        $this->db = $databaseManager;

        $this->websiteRepository = $websiteRepository;
    }

    public function handle($request, Closure $next)
    {
        $uuid = Cookie::get('ibrand_log_uuid');

        if ($ibrand_log_sso_user = Cookie::get('ibrand_log_sso_user')) {
            $ibrand_log_sso_user = json_decode($ibrand_log_sso_user);
        }

        if ($uuid and $ibrand_log_sso_user and $website = $this->websiteRepository->findByUuid($uuid)) {
            $info = $this->connection->generateConfigurationArray($website);

            config(['database.connections.'.$uuid => $info]);

            $this->db->setDefaultConnection($uuid);
        } else {
            $this->db->setDefaultConnection('mysql');

            return redirect(route('account.index'));
        }

        if (!$user = AdminUser::where('mobile', $ibrand_log_sso_user->mobile)->first()) {
            return redirect(route('account.index'));
        }

        Auth::guard('admin')->loginUsingId($user->id);

        $account_application = AccountApplication::with('account')->where('uuid', $uuid)->first();

        Auth::guard('account')->loginUsingId($account_application->account->id);

        return $next($request);
    }
}

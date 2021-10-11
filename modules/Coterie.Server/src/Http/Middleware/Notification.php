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
use iBrand\Coterie\Core\Services\NotificationService;
use Illuminate\Contracts\Auth\Guard;

class Notification
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $notificationService;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, NotificationService $notificationService)
    {
        $this->auth = $auth;

        $this->notificationService = $notificationService;
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
        $user = auth('api')->user();

        if ($notification_id = request('notification_id')) {
            $this->notificationService->markReadById($user, $notification_id);
        }

        return $next($request);
    }
}

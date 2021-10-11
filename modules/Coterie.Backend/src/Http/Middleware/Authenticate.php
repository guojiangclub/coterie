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
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class Authenticate
{
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
        if ($request->getSchemeAndHttpHost() == env('APP_URL')) {
            return $this->unAuthenticateHandle($request);
        }

        if (!$request->cookie('ibrand_log_uuid')) {
            //Log::info(1);
            return $this->unAuthenticateHandle($request);
        }

        $website = \Hyn\Tenancy\Facades\TenancyFacade::website();
        $current_uuid = $website->uuid;

        //Log::info('wesite:' . json_encode($website));

        $uuid = $request->cookie('ibrand_log_uuid');
        $cookie_key = 'ibrand_log_sso_user';
        if (!$request->cookie($cookie_key) or $uuid != $current_uuid) {
            // Log::info(2);
            return $this->unAuthenticateHandle($request);
        }

        $environment = app()->make(\Hyn\Tenancy\Environment::class);
        $environment->tenant($website);
        config(['database.default' => 'tenant']);

        if (Auth::guard('admin')->guest()) {
            //Log::info(3);
            $mobile = json_decode($request->cookie($cookie_key), true)['mobile'];
            $admin = Administrator::where('mobile', $mobile)->first();
            if ($admin) {
                //Log::info(4);
                Auth::guard('admin')->login($admin);
            } else {
                //Log::info(5);
                return $this->unAuthenticateHandle($request);
            }
        }

        if ('/admin' == $request->getRequestUri()) {
            return redirect('/admin/coterie/mini/version');
        }

        return $next($request);
    }

    protected function unAuthenticateHandle($request)
    {
        Auth::guard('admin')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        Cookie::queue(Cookie::forget('ibrand_log_uuid'));
        Cookie::queue(Cookie::forget('ibrand_log_sso_user'));
        Cookie::queue(Cookie::forget('ibrand_log_appid'));
        Cookie::queue(Cookie::forget('ibrand_log_application_name'));

        return redirect(env('APP_URL').'/account/login');
    }
}

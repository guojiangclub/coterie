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

use Closure;
use iBrand\Coterie\Core\Common\MiniProgram;
use Illuminate\Contracts\Auth\Guard;

class msgSecCheck
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    protected $miniProgram;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth, MiniProgram $miniProgram)
    {
        $this->auth = $auth;

        $this->miniProgram = $miniProgram;
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
        if ($request->isMethod('post') and count($request->all())) {
            $input = json_encode($request->all(), JSON_UNESCAPED_UNICODE);
            $pattern = 'utf8' ? '/[\x{4e00}-\x{9fa5}]/u' : '/[\x80-\xFF]/';
            preg_match_all($pattern, $input, $result);
            $temp = join('', $result[0]);

            if (!empty($temp)) {
                $res = $this->miniProgram->msgSecCheck($temp);

                if (0 != $res['errcode']) {
                    return response(['status' => false, 'code' => 400, 'message' => '您的内容违反相关规定', 'data' => []]);
                }
            }
        }

        return $next($request);
    }
}

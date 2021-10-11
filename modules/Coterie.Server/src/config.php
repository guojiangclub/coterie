<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [
    /*
   |--------------------------------------------------------------------------
   | Access via `https`
   |--------------------------------------------------------------------------
   |
   |If your page is going to be accessed via https, set it to `true`.
   |
   */
    'secure' => env('SECURE', false),

    'pay_debug' => env('PAY_DEBUG', false),

    'routeAttributes' => [
        'middleware' => ['api', 'cors'],
    ],

    'routeAuthAttributes' => [
        'middleware' => ['auth:api'],
    ],
];

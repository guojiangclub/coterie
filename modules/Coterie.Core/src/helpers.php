<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('collect_to_array')) {
    /**
     * @param $collection
     *
     * @return array
     */
    function collect_to_array($collection)
    {
        $array = [];
        foreach ($collection as $item) {
            $array[] = $item;
        }

        return $array;
    }
}

if (!function_exists('client_id')) {
    /**
     * @param $collection
     *
     * @return array
     */
    function client_id()
    {
        if ('public' != env('SAAS_SERVER_TYPE')) {
            return null;
        }

        if (request('appid')) {
            return request('appid');
        }

        return request()->header('appid') ? request()->header('appid') : '';
    }
}

if (!function_exists('Hashids_encode')) {
    function Hashids_encode($id, $connections = 'main')
    {
        $salt = config('hashids.connections.'.$connections.'.salt');

        if (!$salt) {
            return null;
        }

        $prefix = config('hashids.connections.'.$connections.'.prefix');

        $code = \Vinkla\Hashids\Facades\Hashids::connection($connections)->encode($id);

        if ($prefix) {
            return $prefix.$code;
        }

        return $code;
    }
}

if (!function_exists('Hashids_decode')) {
    function Hashids_decode($str, $connections = 'main')
    {
        $salt = config('hashids.connections.'.$connections.'.salt');

        if (!$salt) {
            return null;
        }

        $prefix = config('hashids.connections.'.$connections.'.prefix');

        if ($prefix) {
            $str = substr($str, strlen($prefix), strlen($str));
        }

        $decode = \Vinkla\Hashids\Facades\Hashids::connection($connections)->decode($str);

        return isset($decode[0]) ? $decode[0] : null;
    }
}

if (!function_exists('user_meta')) {
    /**
     * get user's nick_name and avatar data.
     *
     * @param null $user
     *
     * @return string
     */
    function user_meta($user = null)
    {
        $user = $user ? $user : request()->user();

        if (!$user) {
            return '';
        }

        return json_encode([
            'id' => $user->id,
            'nick_name' => $user->nick_name,
            'avatar' => $user->avatar,
        ]);
    }
}

if (!function_exists('user_meta_array')) {
    /**
     * get user's nick_name and avatar data.
     *
     * @param null $user
     *
     * @return string
     */
    function user_meta_array($user = null)
    {
        $user = $user ? $user : request()->user();

        if (!$user) {
            return [];
        }

        return [
            'id' => $user->id,
            'nick_name' => $user->nick_name,
            'avatar' => $user->avatar,
        ];
    }
}

if (!function_exists('coterie_invite_encode')) {
    function coterie_invite_encode($data, $user = null)
    {
        return \Vinkla\Hashids\Facades\Hashids::connection('coterie_member')->encode($data);
    }
}

if (!function_exists('coterie_invite_decode')) {
    function coterie_invite_decode($data, $user = null)
    {
        $arr = \Vinkla\Hashids\Facades\Hashids::connection('coterie_member')->decode($data);

        if (isset($arr[0])) {
            return $arr[0];
        }

        return null;
    }
}

if (!function_exists('build_order_no')) {
    function build_order_no($prefix = 'O')
    {
        //订单号码主体（YYYYMMDDHHIISSNNNNNNNN）

        $order_id_main = date('Ymd').rand(100000000, 999999999);

        //订单号码主体长度

        $order_id_len = strlen($order_id_main);

        $order_id_sum = 0;

        for ($i = 0; $i < $order_id_len; ++$i) {
            $order_id_sum += (int) (substr($order_id_main, $i, 1));
        }

        //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）

        $order_id = $order_id_main.str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);

        return $prefix.$order_id;
    }
}

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

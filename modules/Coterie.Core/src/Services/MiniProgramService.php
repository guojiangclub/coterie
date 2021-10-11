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

use iBrand\Common\Wechat\Factory;
use Storage;

class MiniProgramService
{
    /**
     * @param $page
     * @param $width
     * @param string $scene
     * @param string $type
     *
     * @return bool|string
     */
//    public function createMiniQrcode($appid, $page, $width, $scene = '', $type = 'share_coterie')
//    {
//
//        $img_name = $scene . '_' . $type . '_mini_qrcode.jpg';
//
//        $savePath = $type . '/mini/qrcode/' . $img_name;
//        if (Storage::disk('public')->exists($savePath)) {
//            return $savePath;
//        }
//        $option = [
//            'page' => $page,
//            'width' => $width,
//        ];
//
//        $platform = new \iBrand\Coterie\Core\Services\PlatformService($appid, client_id());
//
//        $body = $platform->getMiniProgramCode($appid, $scene, $option);
//
//        if (str_contains($body, 'errcode')) {
//            return false;
//        }
//
//        if (client_id()) {
//
//            $savePath = client_id() . '/' . $savePath;
//        }
//
//        Storage::disk('qiniu')->put($savePath, $body);
//
//        $result = Storage::disk('qiniu')->url($savePath);
//
//        if ($result) {
//            return $result;
//        }
//
//        return false;
//
//    }

    public function createMiniQrcode($page, $width, $scene, $str, $type = 'coterie')
    {
        $option = [
            'page' => $page,
            'width' => $width,
            'scene' => $scene,
        ];
        $app = Factory::miniProgram(config('ibrand.wechat.mini_program.coterie'));

        $body = $app->app_code->getUnlimit($scene, $option);

        if (str_contains($body, 'errcode')) {
            return false;
        }
        $img_name = 'mini_qrcode.jpg';
        $savePath = 'public/'.$type.'/'.$str.'/'.$img_name;
        $result = \Storage::put($savePath, $body);
        if ($result) {
            return $savePath;
        }

        return false;
    }

    public function getSession($appid, $code)
    {
        $platform = new \iBrand\Coterie\Core\Services\PlatformService($appid, client_id());

        $data['code'] = $code;

        $res = $platform->wxCurl(env('WECHAT_API_URL').'api/mini/base/session?client_id='.env('WECHAT_API_CLIENT_ID').'&appid='.$appid, $data, false);

        $res_arr = [];

        if (json_decode($res)) {
            foreach (json_decode($res) as $key => $item) {
                $res_arr[$key] = $item;
            }
        }

        return $res_arr;
    }
}

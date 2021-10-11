<?php

/*
 * This file is part of ibrand/coterie-core.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Core\Common;

use iBrand\Common\Wechat\Factory;
use Storage;

class MiniProgram
{
    protected $config;

    public function __construct()
    {
        $this->config = config('ibrand.wechat.mini_program.coterie');
    }

    public function msgSecCheck($str)
    {
        $app = Factory::miniProgram($this->config);

        return $app->content_security->checkText($str);
    }

    public function imgSecCheck($path)
    {
        $app = Factory::miniProgram($this->config);

        return $app->content_security->checkImage($path);
    }

    public function createMiniQrcode($page, $width, $save_path, $type = 'B', $scene = '', $disk = 'public')
    {
        $app = Factory::miniProgram($this->config);

        $option['width'] = $width;
        $option['scene'] = $scene;
        if ('B' == $type) {
            $option['page'] = $page;
            $body = $app->app_code->getUnlimit($scene, $option);
        } else {
            $body = $app->app_code->get($page, $option);
        }

        if (str_contains($body, 'errcode')) {
            return false;
        }

        if ('qiniu' == $disk) {
            $result = Storage::disk('qiniu')->put($save_path, $body);
        } else {
            $result = Storage::disk($disk)->put($save_path, $body);
        }

        if ($result) {
            return $save_path;
        }

        return false;
    }
}

<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Services;

use iBrand\Coterie\Core\Services\PlatformService;

class MiniProgramService
{
    protected $appid;

    protected $uuid;

    protected $wechat_api;

    protected $platform;

    public function __construct($appid, $uuid)
    {
        $this->appid = $appid;

        $this->uuid = $uuid;

        $this->platform = new PlatformService($this->appid, $this->uuid);

        $this->wechat_api = env('WECHAT_API_URL');
    }

    public function getToken()
    {
        return $this->platform->getToken();
    }

    public function getVersion()
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version?appid='.$this->appid, [], false);

        return json_decode($res);
    }

    public function getQrCode()
    {
        return $this->platform->wxCurl($this->wechat_api.'api/mini/code/getQrCode?appid='.$this->appid, ['appid' => $this->appid], false);
    }

    public function getUnlimit($scene, $optional)
    {
        $data = [
            'scene' => $scene,
            'optional' => $optional,
        ];

        return $this->platform->wxCurl($this->wechat_api.'api/mini/app_code/getUnlimit?appid='.$this->appid, $data, false);
    }

    public function themeModel($template_id)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/model?appid='.$this->appid.'&template_id='.$template_id, [], false);

        return json_decode($res);
    }

    public function codeCommit($data)
    {
        return $this->platform->wxCurl($this->wechat_api.'api/mini/code/commit?appid='.$this->appid, $data, false);
    }

    public function testerBind($data)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/tester/bind?appid='.$this->appid, $data, false);

        return json_decode($res);
    }

    public function testerunBind($data)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/tester/unbind?appid='.$this->appid, $data, false);

        return json_decode($res);
    }

    public function submitAudit($data)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/submitAudit?appid='.$this->appid, $data, false);

        return json_decode($res);
    }

    public function withdrawAudit()
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/withdrawAudit?appid='.$this->appid, [], false);

        return json_decode($res);
    }

    public function Reexamination($id)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/reexamination?appid='.$this->appid.'&id='.$id, [], false);

        return json_decode($res);
    }

    public function release()
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/release?appid='.$this->appid, [], false);

        return json_decode($res);
    }

    public function sendLog($limit = 1, $page = 1)
    {
        $res = $this->platform->wxCurl($this->wechat_api.'api/mini/saas/version/log?appid='.$this->appid.'&page='.$page.'&limit='.$limit, [], false);

        return json_decode($res);
    }
}

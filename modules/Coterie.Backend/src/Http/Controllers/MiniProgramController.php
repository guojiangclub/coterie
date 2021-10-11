<?php

/*
 * This file is part of ibrand/coterie-backend.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Backend\Http\Controllers;

use Encore\Admin\Facades\Admin as LaravelAdmin;
use Encore\Admin\Layout\Content;
use iBrand\Coterie\Backend\Services\MiniProgramService;
use Storage;

class MiniProgramController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));
    }

    public function version()
    {
        return;
        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $version = $platform->getVersion();

        if (!isset($version->data->version)) {
            admin_toastr('获取版本信息失败', 'warning');

            return redirect()->route('admin.account.coterie.index');
        }

        if (!isset($version->data->domain) || !empty($version->data->domain)) {
            admin_toastr($version->data->domain, 'warning');

            return redirect()->route('admin.account.coterie.index');
        }

        $version_info = $version->data;

        return LaravelAdmin::content(function (Content $content) use ($version_info) {
            $name = request()->cookie('ibrand_log_application_name');

            $appid = request()->cookie('ibrand_log_appid');

            $content->header('发布小程序');

            $content->description($name);

            $content->breadcrumb(
                    ['text' => '小程序管理', 'url' => '/coterie', 'no-pjax' => 1],
                    ['text' => '小程序管理', 'url' => '/coterie/mini', 'no-pjax' => 1, 'left-menu-active' => '版本发布']
                );

            $version = $version_info->version;

            $theme = $version_info->theme;

            $appid = $version_info->appid;

            $testers = $version_info->testers;

            $category = $version_info->category;

            $audit = $version_info->audit;

            $status_message = $version_info->status_message;

            $publish = $version_info->publish;

            $uuid = request()->cookie('ibrand_log_uuid');

            if (request('repost')) {
                $audit = [];
                $status_message = '';
            }

            $content->body(view('account-backend::mini.version.index', compact('uuid', 'version', 'version_info', 'theme', 'appid', 'testers', 'category', 'audit', 'status_message', 'publish')));
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function Model()
    {
        $template_id = request('template_id');

        $theme = [];

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $theme_data = $platform->themeModel($template_id);

        if (isset($theme_data->data)) {
            $theme = $theme_data->data;
        }

        return view('account-backend::mini.version.model', compact('theme'));
    }

    /**
     * 获取体验小程序的体验二维码
     *
     * @return mixed
     */
    public function getQrCode()
    {
        try {
            $uuid = request()->cookie('ibrand_log_uuid');

            $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

            $res = $platform->getQrCode();

            echo $res;

            //            $savePath=$uuid.'/coterie/'.'experience_mini_code'.$this->generaterandomstring().'.jpg';
    //
    //            Storage::disk('qiniu')->put($savePath,$res);
    //
    //            if ($result=Storage::disk('qiniu')->url($savePath)) {
    //
    //               echo $result;
    //            }
        } catch (\Exception $exception) {
        }
    }

    /**
     * 获取体验小程序太阳码
     *
     * @return mixed
     */
    public function getUnlimit()
    {
        try {
            $uuid = request()->cookie('ibrand_log_uuid');

            $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

            $scene = $uuid;

            $optional = [
                    'page' => request('page'),
                    'width' => request('width'),
                ];

            $res = $platform->getUnlimit($scene, $optional);

            echo $res;

            //            $savePath=$uuid.'/coterie/'.'experience_mini_code_limit'.$this->generaterandomstring().'.jpg';
    //
    //            Storage::disk('qiniu')->put($savePath,$res);
    //
    //            if ($result=Storage::disk('qiniu')->url($savePath)) {
    //
    //               echo $result;
    //            }
        } catch (\Exception $exception) {
        }
    }

    /**
     * @param int $length
     *
     * @return string
     */
    private function generaterandomstring($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

    /**
     * 为授权的小程序帐号上传小程序代码
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function codeCommit()
    {
        $data = request()->except('_token');

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->codeCommit($data);

        return $this->admin_wechat_api($result);
    }

    /**
     * 绑定微信用户为小程序体验者.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testerBind()
    {
        $data = request()->except('_token');

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->testerBind($data);

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * 解除绑定小程序的体验者.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function testerunBind()
    {
        $data = request()->except('_token');

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->testerunBind($data);

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitAudit()
    {
        $data = request()->except('_token');

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->submitAudit($data);

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function withdrawAudit()
    {
        $data = request()->except('_token');

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->withdrawAudit($data);

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function Reexamination()
    {
        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->Reexamination(request('id'));

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function release()
    {
        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->release();

        if (isset($result->status) and false == $result->status) {
            return $this->api([], false, 400, $result->message);
        }

        return $this->api($result);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendLog()
    {
        $limit = request('limit') ? request('limit') : 1;

        $page = request('page') ? request('page') : 1;

        $platform = new MiniProgramService(request()->cookie('ibrand_log_appid'), request()->cookie('ibrand_log_uuid'));

        $result = $platform->sendLog($limit, $page);

        $lists = [];

        if (isset($result->status) and $result->status) {
            $lists = $result;
        }

        return LaravelAdmin::content(function (Content $content) use ($lists) {
            $name = request()->cookie('ibrand_log_application_name');

            $appid = request()->cookie('ibrand_log_appid');

            $content->header('发布小程序');

            $content->description($name);

            $content->breadcrumb(
                    ['text' => '小程序管理', 'url' => '/coterie', 'no-pjax' => 1],
                    ['text' => '小程序管理', 'url' => '/coterie/mini', 'no-pjax' => 1, 'left-menu-active' => '发布记录']
                );

            $content->body(view('account-backend::mini.version.log', compact('lists')));
        });
    }
}

<?php

/*
 * This file is part of ibrand/coterie-server.
 *
 * (c) 果酱社区 <https://guojiang.club>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\Coterie\Server\Http\Controllers;

use Illuminate\Http\Request;
use Image;

class UploadController extends Controller
{
    /**
     * 上传图片.
     *
     * @param Request $request
     *
     * @return \Dingo\Api\Http\Response
     */
    public function ImageUpload(Request $request)
    {
        $file = $request->file('image');

        $Orientation = $request->input('Orientation');

        $destinationPath = storage_path('app/public/uploads');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $extension = $file->getClientOriginalExtension();
        $filename = $this->generaterandomstring().'.'.$extension;

        $image = $file->move($destinationPath, $filename);

        $img = Image::make($image);

        switch ($Orientation) {
            case 6://需要顺时针（向左）90度旋转
                $img->rotate(-90);
                break;
            case 8://需要逆时针（向右）90度旋转
                $img->rotate(90);
                break;
            case 3://需要180度旋转
                $img->rotate(180);
                break;
        }

        $img->save();

        return $this->success(['url' => url('/storage/uploads/'.$filename)]);
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
}

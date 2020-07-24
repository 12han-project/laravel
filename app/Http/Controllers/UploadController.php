<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * ファイルを保存コントローラ
 * @author　　　アリ
 * @version     v1.2
 * @date        2020-06-28
 */
class UploadController extends Controller
{

    /**
     * ファイルを保存
     *
     * @param Request $request
     * @return array $result
     */
    public function teacherUpload(Request $request)
    {
        $path = $request->file('file')->store('code/'.md5(date('Y?m?d',time())),'public');

        $result = [
                'code' => 1,
                'msg' => '/'.$path
        ];

        return $result;
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
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

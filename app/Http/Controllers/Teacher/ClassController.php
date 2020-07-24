<?php

namespace App\Http\Controllers\Teacher;

use App\Classes;
use App\Classes_users;
use App\Http\Controllers\Controller;
use App\Upload_files;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * 授業コントローラー
 * @author      伊藤 遼
 * @version     v2.2
 * @date        2020-07-03
 */
class ClassController extends Controller
{
    /**
     * ファイルの中身を読み込んで渡す．
     *
     * @param   Request   $request
     * @return  json     $data
     */
    public function index(Request $request)
    {
        $upload_file = Upload_files::where('id',$request->id)->first();
        $upload_file['task_code'] = $this->open_file($upload_file['task_file']);

        $data = array(
                'file_id' => $upload_file['id'],
                'code' => $upload_file['task_code'],
        );

        return json_encode($data);
    }

    /**
     * ファイルの中身を読み込んで渡す．
     *
     * @param $task_file
     * @return string
     */
    public function open_file($task_file)
    {
        $code = '';
        $fopen = file('./public/'.$task_file);
        foreach($fopen as $k => $v){
            $code .= $v;
        }
        return $code;
    }
    /**
     * 授業登録ページを表示．
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("teacher/class/create");
    }

    /**
     * 授業登録．
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                    'name' => 'bail|required|min:1|max:25',
            ],[
                    'name.required' => '授業名に一つ以上の文字を入れてください.',
                    'name.min' => '授業名に一つ以上の文字を入れてください.',
                    'name.max' => '授業名に25文字以内にしてください.',
                    'student.required' => '学生番号を入力してください．',
            ]
        );

        foreach (explode(',', $request->students) as $k){
            if(!preg_match('/(([A-Z]|[a-z]){2}[0-9]{5})/', $k))
                return back()->withErrors(['学生番号を英字2文字+数字5文字で入れてください.']);
        }

        $students = explode(",",$request->students);
        $datetime = explode(" - ",$request->datetime);
        $start_time = Carbon::createFromDate($datetime[0]);
        $end_time = Carbon::createFromDate($datetime[1]);

        $class = [
                'name' => $request->name,
                'start_time' => $start_time,
                'end_time' => $end_time,
        ];

        $class_id = Classes::insertGetId($class);

        if(!$class_id) return back()->withErrors(['Classは失敗されました']);

        Classes_users::insert(['class_id' => $class_id, 'user_name' => strtoupper(session('user_info')['name']), 'type' => 1]);
        foreach ($students as $k => $v){
            Classes_users::insert(['class_id' => $class_id, 'user_name' => $v, 'type' => 2]);
        }

        return redirect('/teacher/class');
    }

    /**
     * 授業を登録している学生リストを表示．
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function students(Request $request)
    {
        $class_id = $request->class_id;
        $students = Classes_users::where('class_id',$class_id)->where('type',2)->get();

        foreach ($students as $k => $v){
            $students[$k]['tasks'] = Upload_files::where('user_name', $v['user_name'])->get();
        }

        return view('teacher/class/students', ['students' => $students]);
    }

    /**
     * 授業にアップロードされた課題を表示．
     *
     * @param Request $request　Class_id
     * @return \Illuminate\Http\Response
     */
    public function tasks(Request $request)
    {
        $class_id = $request->class_id;
        $tasks = Upload_files::where('class_id',$class_id)->get();

        return view('teacher/class/tasks', ['tasks' => $tasks]);
    }

    /**
     * 課題を取消
     *
     * @param Request $request　file_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $id = $request->file_id;
        $result = Upload_files::where('id',$id)->delete();
        if($result){
            return back()->with('info','ok');
        }else{
            return back()->withErrors(['taskのdeleteは失敗されました']);
        }
    }

    /**
     * 課題ファイルをダウンロードする．
     *
     * @param Request $request
     * @return String URL
     */
    public function download(Request $request)
    {
        $upload_file = Upload_files::where('id',$request->file_id)->first();
        $task_address = '/public/'.$upload_file['task_file'];
        $task_name = $upload_file['task_name'].'.c';
        return Storage::download($task_address, $task_name);
    }

    /**
     * 課題ファイルをコンパイルして実行する．実行結果を返す．
     *
     * @param Request $request
     * @return string　$result 実行結果
     */
    public function execute_code(Request $request)
    {
        $argv = '';
        foreach (explode(' ', $request->argv) as $k => $v)
            if($k != 0)$argv .= ' '.$v;
//        dd($argv);
        $task = Upload_files::where('id',$request->task_id)->first();
        $file_address = './public/'.$task['task_file'];
        $file_name = explode('/', $task['task_file'])[3];
        $compiler_file = './public/compiler/'.$file_name.'.out';

        exec('gcc -o '.$compiler_file.' '.$file_address.' 2>&1', $output, $return_value);

        //error massage
        if($return_value != 0){
            $error_message = '';
            foreach ($output as $k =>$v){
                if(explode('/', $v)[0] == '.'){
                    $a = explode(':', $v);
                    $a[0] = 'file.c';
                    $error_message .= implode(':', $a)."\n";
                }else{
                    $error_message .= $v."\n";
                }
            }
            return $error_message;
        }


        $result = exec($compiler_file.$argv);
        return $result;

    }

    /**
     * 課題の状態を変更する．
     *
     * @param Request $request
     */
    public function edit(Request $request)
    {
        $status = $request->status;
        $task_id = $request->id;

        $result = Upload_files::where('id', $task_id)->update(["status"=>$status]);
    }

}

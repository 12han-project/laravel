<?php

namespace App\Http\Controllers\Student;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Upload_files;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         */
        $upload_file = Upload_files::where('id',$request->id)->first();
        $upload_file['task_code'] = $this->open_file($upload_file['task_file']);
//dd($upload_file['task_code']);
        $data = array(
                'file_id' => $upload_file['id'],
                'code' => $upload_file['task_code'],
        );

        return json_encode($data);
    }

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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $class_id = $request->class_id;
        $class_name = Classes::where('id', $class_id)->first()['name'];
        $datum = [
                'class_id' => $class_id,
                'class_name' => $class_name
        ];
        return view('student/task/create', $datum);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests \TaskPost $request)
    {
        $datum = [
                'class_id' => $request->class_id,
                'user_name' => session('user_info')['name'],
                'task_name' => $request->task_name,
                'task_file' => $request->upload,
                'status' => 1,
                'created_at' => Carbon::now(),
        ];

        $file_id = Upload_files::insertGetId($datum);

        if(!$file_id){
            return 'error';
        }

        return redirect('/student/class/'.$request->class_id.'/tasks');
    }

    public function task_list(Request $request)
    {
        $class_id = $request->class_id;

        $tasks = Upload_files::where('user_name', session('user_info')['name'])->get();

        $datum = [
            'tasks' => $tasks,
        ];

        return view('student/task/tasks', $datum);
    }

    public function download(Request $request)
    {
        $upload_file = Upload_files::where('id',$request->file_id)->first();
        $task_address = '/public/'.$upload_file['task_file'];
        $task_name = $upload_file['task_name'].'.c';
        return Storage::download($task_address, $task_name);
    }

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

    public function save(Request $request)
    {
        $task_id = $request->task_id;
        $task_content = $request->task_content;
        $file_address = './public/'.Upload_files::where('id', $task_id)->first()['task_file'];

        $my_file = fopen($file_address, "w");
        $result = fwrite($my_file, $task_content);
        return $result;
    }

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

}

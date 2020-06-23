<?php

namespace App\Http\Controllers\Student;

use App\Classes;
use App\Http\Controllers\Controller;
use App\Upload_files;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
         * 还没写有没有权限打开文件！！！
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
    public function store(Request $request)
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

//        foreach ($tasks as $k => $v){
//            $tasks[$k]['']
//        }

        $datum = [
            'tasks' => $tasks,
        ];

        return view('student/task/tasks', $datum);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

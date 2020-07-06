<?php

namespace App\Http\Controllers\Teacher;

use App\Classes;
use App\Classes_users;
use App\Http\Controllers\Controller;
use App\Upload_files;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("teacher/class/create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request \ClassPost $request)
    {
        dd($request);
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

    public function students(Request $request)
    {
        $class_id = $request->class_id;
        $students = Classes_users::where('class_id',$class_id)->where('type',2)->get();

        foreach ($students as $k => $v){
            $students[$k]['tasks'] = Upload_files::where('user_name', $v['user_name'])->get();
        }

        return view('teacher/class/students', ['students' => $students]);
    }

}

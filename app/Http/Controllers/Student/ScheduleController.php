<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\Schedules;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student/schedule/index');
    }

    public function get(Request $request)
    {
        $student_id = $request->uid;
        $datum = Schedules::where('student_id', $student_id)->first('schedules')['schedules'];

        $datum = json_decode($datum);

        return $datum;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $schedule = [
                "student_id" => $request->uid,
                "title" => $request->title,
                "url" => $request->url,
                "week" => (int)$request->week,
                "time_limit" => (int)$request->time_limit,
        ];

        $result = Schedules::where('student_id',$schedule['student_id'])->first();
        if($result){
            $data = $result['schedules'];
            $data = json_decode($data, true);
        }else{
            $data = [];
        }

        array_push($data, $schedule);

        $data = json_encode($data);

        if($result){
            $result = Schedules::where('student_id',$schedule['student_id'])->update(['schedules' => $data]);
        }else{
            $result = Schedules::insert([
                    "student_id" => $schedule['student_id'],
                    'schedules' => $data
            ]);
        }

        return $result;
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
    public function destroy(Request $request)
    {
        $week = $request->week;
        $student_id = $request->uid;
        $time_limit = $request->time_limit;

        $data = Schedules::where('student_id',$student_id)->first('schedules')['schedules'];
        $data = json_decode($data, true);

        foreach ($data as $k => $v){
            if($v['week'] == $week && $v['time_limit'] == $time_limit) {
                unset($data[$k]);
            }
        }

        $result = Schedules::where('student_id',$student_id)->update([
                'schedules' => $data
        ]);

        return $result;

    }
}

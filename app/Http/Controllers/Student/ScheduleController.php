<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Schedule;
use App\Schedules;
use Illuminate\Http\Request;
use App\Http\Requests;

/**
 * スケジュールコントローラー
 * @author
 * @version     v2.3
 * @date        2020-07-03
 */
class ScheduleController extends Controller
{
    /**
     * スケジュールページを表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('student/schedule/index');
    }

    /**
     * ユーザーのスケジュール情報を表示
     *
     * @param Request $request
     * @return $datum
     */
    public function get(Request $request)
    {
        $student_id = $request->uid;
        $datum = Schedules::where('student_id', $student_id)->first('schedules')['schedules'];

        $datum = json_decode($datum);

        return $datum;
    }

    /**
     * スケジュールを保存
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                    'title' => 'bail|required|min:1|max:25',
                    'url' => 'bail|required|url|max:255',
                    'week' => 'bail|required',
                    'time_limit' => 'bail|required'
            ],[
                    'title.required' => '授業名を入れてください',
                    'title.min' => '授業名に一つ以上の文字を入れてください',
                    'title.max' => '授業名に２５文字以内にしてください',
                    'url.required' => 'URLアドレスを入れてください',
                    'url.url' => '正しいURLアドレスを入れてください',
                    'url.max' => 'URLアドレスを２５５文字以内にしてください',
                    'week.required' => '曜日を選択してください',
                    'time_limit.required' => '時限を選択してください',
            ]
        );

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
     * スケジュールを取消
     *
     * @param  Request  $request
     * @return boolean  $result
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

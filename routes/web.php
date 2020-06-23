<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'LoginController@index');
Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/login/google', 'LoginController@redirectToGoogle');
Route::get('/login/google/callback', 'LoginController@handleGoogleCallback');
Route::get('/logout', 'LoginController@logout');

Route::group(['middleware' => 'is_Login'],function(){
    Route::post('/upload', 'UploadController@teacherUpload');
});

Route::group(['middleware' => 'Login_teacher'],function(){
    Route::get('/teacher', 'Teacher\TeacherController@index');
    Route::get('/teacher/class/create', 'Teacher\ClassController@create');
    Route::get('/teacher/class', 'Teacher\ClassController@index');
    Route::post('/teacher/class', 'Teacher\ClassController@store');
    Route::get('/teacher/class/{class_id}/students', 'Teacher\ClassController@students');
});

Route::group(['middleware' => 'Login_student'],function(){
    Route::get('/student', 'Student\ScheduleController@index');
    Route::get('/student/class/{class_id}/task_upload', 'Student\TaskController@create');
    Route::get('/student/class/{class_id}/tasks', 'Student\TaskController@task_list');
    Route::post('/student/task', 'Student\TaskController@store');
    Route::get('/student/task/{id}', 'Student\TaskController@index');
    Route::get('/student/schedule', 'Student\ScheduleController@index');
    Route::get('/student/schedule/get', 'Student\ScheduleController@get');
    Route::post('/student/schedule', 'Student\ScheduleController@store');
    Route::delete('/student/schedule', 'Student\ScheduleController@destroy');
});



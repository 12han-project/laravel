<?php
use Illuminate\Support\Facades\Route;

Route::get('/', 'LoginController@index');
Route::get('/login', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/login/google', 'LoginController@redirectToGoogle');
Route::get('/login/google/callback', 'LoginController@handleGoogleCallback');
Route::get('/logout', 'LoginController@logout');

Route::get('/execute_code/{task_id}', 'Student\TaskController@execute_code');

Route::group(['middleware' => 'Login_teacher'],function(){
    Route::get('/teacher', 'Teacher\TeacherController@index');
    Route::get('/teacher/class/create', 'Teacher\ClassController@create');
    Route::get('/teacher/class', 'Teacher\ClassController@create');
    Route::post('/teacher/class', 'Teacher\ClassController@store');
    Route::get('/teacher/class/{class_id}/students', 'Teacher\ClassController@students');
    Route::get('/teacher/class/{class_id}/tasks', 'Teacher\ClassController@tasks');
    Route::get('/teacher/task/download/{file_id}', 'Teacher\ClassController@download');
    Route::get('/teacher/task/delete/{file_id}', 'Teacher\ClassController@delete');
    Route::get('/teacher/task/edit/{id}', 'Teacher\ClassController@edit');
    Route::get('/teacher/task/{id}', 'Teacher\ClassController@index');
});

Route::group(['middleware' => 'Login_student'],function(){
    Route::get('/student', 'Student\ScheduleController@index');
    Route::get('/student/class/{class_id}/task_upload', 'Student\TaskController@create');
    Route::get('/student/class/{class_id}/tasks', 'Student\TaskController@task_list');
    Route::post('/student/task', 'Student\TaskController@store');
    Route::get('/student/task/save', 'Student\TaskController@save');
    Route::get('/student/task/delete/{file_id}', 'Student\TaskController@delete');
    Route::get('/student/task/{id}', 'Student\TaskController@index');
    Route::get('/student/schedule', 'Student\ScheduleController@index');
    Route::get('/student/schedule/get', 'Student\ScheduleController@get');
    Route::get('/student/schedule/store', 'Student\ScheduleController@store');
    Route::delete('/student/schedule', 'Student\ScheduleController@destroy');
    Route::get('/student/task/download/{file_id}', 'Student\TaskController@download');

});



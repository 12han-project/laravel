<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Users;
use Illuminate\Support\Facades\Hash;
use Socialite;

/**
 * ログインコントローラー
 * @author      アリ
 * @version     v2.0
 * @date        2020-07-03
 */
class LoginController extends Controller
{
    /**
     * ログイン状態を確認して，ログイン済みならログインユーザーのタイプのホームページへ移す．ログインしなかったらログインページへ移す．
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!empty(session('user_info'))){
            if(session('user_info')['type'] == 1) return redirect('/teacher');
            if(session('user_info')['type'] == 2) return redirect('/student');
        }

        return view('login');
    }

    /**
     * Googleのログインページへ移す
     *
     * @return $mixed　URLアドレス
     */
    public function redirectToGoogle()
    {
        // Google へのリダイレクト
        return Socialite::driver('google')->redirect();
    }

    /**
     * ログインユーザーのタイプに対してホームページへ移す
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        $gUser = Socialite::driver('google')->stateless()->user();
        $user = $gUser->user;
        $student_number = strtoupper(explode('@', $user['email'])[0]);

        if(!isset($user['hd']) || $user['hd'] != 'shibaura-it.ac.jp')
            return redirect('/login')->withErrors(['芝浦工業大学のアカウントでログインしてください.']);

        $user_type = preg_match('/(([A-Z]|[a-z]){2}[0-9]{5})/', $student_number) ? 2 : 1;

        $isset_user = Users::where('number', $student_number)->first();
        if(!$isset_user){
            $result = Users::insert([
                    'number' => $student_number,
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'type' => $user_type,
                    'picture' => $user['picture'],
            ]);
            if(!$result)
                return redirect('/login')->withErrors(['error.']);
        }

        session([
                'user_info' => [
                        'name' => $user['name'],
                        'picture' => $user['picture'],
                        'email' => $user['email'],
                        'number' => $student_number,
                        'type' => $user_type,
                ]
        ]);

        if($user_type == 1) return redirect('/teacher');
        if($user_type == 2) return redirect('/student');
    }

    /**
     * ユーザー情報を確認し，Googleから戻した情報をデータベースに保存し，ホームページへ移す．
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validate($request,
            [
                    'username' => 'bail|required|alpha_num',
                    'password' => 'bail|required',
            ],[
                    'username.required' => 'ユーザー名の間違い',
                    'username.alpha_num' => 'パスワードの間違い',
                    'password.required' => 'パスワードを入力してください',
            ]
        );

        $user = Users::where('number',strtoupper(trim($request->username)))->first();

        if(empty($user)){
            return back()->withErrors(['新しいアカウントです．Google登録してください．']);
        }else if($user->password == NULL){
            return back()->withErrors(['パスワードが設定されてない，Googleでログインしてから設定してください．']);
        }else if($request->get('password') != $user->password){
            return back()->withErrors(['パスワードの間違い']);
        }

        session([
                'user_info' => [
                        'name' => $user['name'],
                        'picture' => $user['picture'],
                        'email' => $user['email'],
                        'number' => strtoupper($user['number']),
                        'type' => $user['type'],
                ]
        ]);

        if($user['type'] == 1) return redirect('/teacher');
        if($user['type'] == 2) return redirect('/student');
    }

    /**
     * ユーザーをログアウト
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }
}

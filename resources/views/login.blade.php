<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <link rel="shortcut icon" href="/images/favicon.gif" type="image/png">
    <title>課題提出ページへ</title>
    <link href="/css/style.default.css" rel="stylesheet">
</head>
<body class="signin">

<section>
    <div class="signinpanel">
        <div class="row">
            <div class="col-md-12">
                <div class="signin-info">
                </div><!-- signin0-info -->
            </div><!-- col-sm-7 うぇ-->
            <div class="col-md-5" style="float:none;margin:0 auto">
                <form method="post" action="/login">
                    <div class="logopanel" style="margin-bottom: 15px;text-align: center">
                        <h1><span>[</span> 12班 <span>]</span></h1>
                    </div><!-- logopanel -->
                    <p class="mt5 mb20">芝浦工業大学のアカウントでログインしてください．</p>
                    @if ($errors->any())
                        <div class="alert alert-danger" style="margin-bottom: 0;margin-top: 15px">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <br>
                    @endif

                    <input type="text" name="username" class="form-control uname" placeholder="学生番号"/>
                    <input type="password" name="password" class="form-control pword" placeholder="パスワード" />
                    <button class="btn btn-success btn-block">ログイン</button>
{{--                    <div class="text-center"><h5>or</h5></div>--}}


                    <a href="/login/google">
                        <img src="/images/btn_google_signin_dark_normal_web.png" width="100%" alt="">
                    </a>
                    @csrf
                </form>

            </div><!-- col-sm-5 -->
        </div><!-- row -->
    </div><!-- signin -->
</section>
</body>
</html>

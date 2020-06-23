<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="author" content="BluewolfAli">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="/images/favicon.gif" type="image/png">
    <title>Isao Sasano</title>
    <link href="/css/bootstrap-editable.css" rel="stylesheet">
    <link href="/css/style.default.css" rel="stylesheet">
    <link href="/css/jquery.gritter.css" rel="stylesheet">
    @yield("head")
</head>

<body>

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>

    @yield("leftpanel")

    <div class="mainpanel">

        <div class="headerbar">

            <a class="menutoggle"><i class="fa fa-bars"></i></a>

            <div class="header-right">
                <ul class="headermenu">


                    <li>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                <img src="{{session('user_info')['picture']}}" alt="">
                                {{session('user_info')['name']}}
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                                <li><a href="/logout"><i class="glyphicon glyphicon-log-out"></i> ログアウト</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div><!-- header-right -->

        </div><!-- headerbar -->

        <div class="pageheader">
            <h2><i class="fa fa-home"></i> Dashboard <span>Subtitle goes here...</span></h2>
            <div class="breadcrumb-wrapper">
                <span class="label">You are here:</span>
                <ol class="breadcrumb">
                    <li><a href="index.html">Bracket</a></li>
                    <li class="active">Dashboard</li>
                </ol>
            </div>
        </div>
        @yield("content")

    </div><!-- mainpanel -->

</section>

<script src="/js/jquery-1.11.1.min.js"></script>
<script src="/js/jquery-migrate-1.2.1.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/jquery.sparkline.min.js"></script>
<script src="/js/toggles.min.js"></script>
<script src="/js/jquery.cookies.js"></script>
<script src="/js/custom.js"></script>
<script src="/js/jquery.gritter.min.js"></script>

<script>
            {{-- Banner --}}
    var url = window.location.pathname.split('/');
    var info = url[2] == null ? 'index' : url[2];
    $('li[name='+info+']').addClass('active');
    if(url[3] != null){
        $('li[name='+info+']>ul').css('display','block');
        $('li[name='+info+']>ul>li[name='+url[3]+']').addClass('active');
    }

    function messages(type,title,text,time = 2000){
        if(type == 'success') var class_name = 'growl-success';
        if(type == 'warning') var class_name = 'growl-warning';
        if(type == 'primary') var class_name = 'growl-primary';
        if(type == 'danger') var class_name = 'growl-danger';
        if(type == 'info') var class_name = 'growl-info';
        jQuery.gritter.add({
            title: title,
            text: text,
            class_name: class_name,
            image: '/images/screen.png',
            sticky: false,
            time: time,
        });
    }

    // Ajax度にTokenを追加する
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

</script>

@yield("js")

</body>
</html>

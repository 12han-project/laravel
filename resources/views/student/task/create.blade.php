
@extends("layout.app")
@section("leftpanel")
    @include("student.leftpanel")
@endsection("leftpanel")
@section("head")
    <link rel="stylesheet" href="/css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="/daterangepicker/daterangepicker.css" media="all"/>
    <link rel="stylesheet" href="/jQueryUpload/upload.css">
@endsection("head")
@section("content")
    <div class="contentpanel">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">課題提出ページ</h2>
                <p> </p>
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 0;margin-top: 15px">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="panel-body panel-body-nopadding">

                <form class="form-horizontal form-bordered" action="/student/task" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">授業名</label>
                        <div class="col-sm-6">
                            <input type="text" value="{{$class_name}}" class="form-control" readonly="readonly">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">課題名</label>
                        <div class="col-sm-6">
                            <input name="task_name" type="text" placeholder="課題名" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">課題種類</label>
                        <div class="col-sm-6">
                            <div class="radio"><label><input type="radio" name="type" value="c"> C</label></div>
                            <div class="radio"><label><input type="radio" name="type" value="java"> Java</label></div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <label class="col-sm-3 control-label" for="disabledinput">ファイル</label>
                        <div class="input-group">
                            <div class="upload"
                                 action='/upload'
                                 name="file"
                                 data-num='1'
                                 id='case1'
                                 data-type="java,c,png"
                            ></div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-3">
                                <button class="btn btn-primary">提出</button>&nbsp;
                                <button class="btn btn-default">キャンセル</button>
                            </div>
                        </div>
                    </div><!-- panel-footer -->

                    {{ csrf_field() }}
                    <input type="hidden" name="class_id" value="{{$class_id}}">

                </form>

            </div><!-- panel-body -->

        </div><!-- contentpanel -->
    </div>
@endsection("content")
@section("js")
    <script src="/js/jquery.tagsinput.min.js"></script>
    <script src="/js/jquery.maskedinput.min.js"></script>
    <script src="/js/select2.min.js"></script>
    <script src="/js/jquery.autogrow-textarea.js"></script>
    <script src="/daterangepicker/moment.min.js"></script>
    <script src="/daterangepicker/daterangepicker.js"></script>
    <script src="/jQueryUpload/jQuery.upload.js"></script>
    <script src="/js/bootstrap-editable.min.js"></script>
    <script>

        // Tags Input
        $('input.tags').tagsInput({width:'auto'});

        // Textarea Auto grow
        $('textarea').autogrow();

        // Select2
        jQuery(".select2").select2({
            width: '100%'
        });

        // Date range picker
        $('.datetimes').daterangepicker({
            timePicker: true,
            startDate: moment().startOf('hour'),
            endDate: moment().startOf('hour').add(32, 'hour'),
            "drops": "up",
            opens: 'left',
            "locale": {
                "format": "YYYY/MM/DD hh:mm A",
                "separator": " - ",
                "applyLabel": "反映",
                "cancelLabel": "取消",
                "fromLabel": "開始日",
                "toLabel": "終了日",
                "customRangeLabel": "自分で指定",
                "weekLabel": "W",
                "daysOfWeek": ["日", "月", "火", "水", "木", "金", "土"],
                "monthNames": ["一月", "二月", "三月", "四月", "五月", "六月", "七月", "八月", "九月","十月","十一月","十二月"],
                "firstDay": 1
            },
        });

        // Upload　model
        $("#case1").upload();

    </script>


@endsection("js")

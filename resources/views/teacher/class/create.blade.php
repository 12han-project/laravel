
@extends("layout.app")
@section("leftpanel")
    @include("teacher.leftpanel")
@endsection("leftpanel")
@section("head")
    <link rel="stylesheet" href="/css/jquery.tagsinput.css" />
    <link rel="stylesheet" href="/daterangepicker/daterangepicker.css" media="all"/>
@endsection("head")
@section("content")
    <div class="contentpanel">

        <div class="panel panel-default">
            <div class="panel-heading">
                <h2 class="panel-title">授業登録ページ</h2>
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

                <form class="form-horizontal form-bordered" action="/teacher/class" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">授業名</label>
                        <div class="col-sm-6">
                            <input name="name" type="text" placeholder="クラス名" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">学生リスト</label>
                        <div class="col-sm-6">
                            <input name="students" class="form-control tags" value="" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">提出期間</label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <input type="text" name="datetime" class="form-control datetimes" placeholder="yyyy/mm/dd hh/mm">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
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



   </script>


@endsection("js")

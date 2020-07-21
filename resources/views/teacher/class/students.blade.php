@extends("layout.app")
@section("leftpanel")
    @include("teacher.leftpanel")
@endsection("leftpanel")
@section("content")
    <div class="contentpanel">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-btns">
                    <a href="" class="panel-close">&times;</a>
                    <a href="" class="minimize">&minus;</a>
                </div><!-- panel-btns -->
                <h3 class="panel-title">学生リスト</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                        <tr>
                            <th width="90%">学生番号</th>
{{--                            <th>操作</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $k => $v)
                            <tr class="odd gradeX" template-id={{$v['id']}}>
                                <td>{{$v['user_name']}}</td>
{{--                                <td>--}}
{{--                                    <a href="#" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm_box"--}}
{{--                                       onclick="confirm_popup(this)">取消</a>--}}
{{--                                </td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div><!-- table-responsive -->

            </div><!-- panel-body -->
        </div><!-- panel -->
    </div><!-- contentpanel -->


    <!-- Confirm Box -->
    <div class="modal fade confirm_popup" id="confirm_box" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">取消</h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary delete-btn" onclick="delete_student()">確認</button>
                    <button type="button" class="btn btn-default close-model" data-dismiss="modal">キャンセル</button>
                </div>
            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- Confirm Box -->
@endsection("content")
@section("js")
    <script>
        function delete_student(){
            alert(0)
        }
    </script>
@endsection("js")

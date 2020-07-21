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
                <h3 class="panel-title">課題リスト</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                        <tr>
                            <th width="10%">課題名</th>
                            <th>提出時間</th>
                            <th>課題状態</th>
                            <th width="15%">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $k => $v)
                            <tr class="odd gradeX" task_id="{{$v['id']}}">
                                <td class="task_number">{{$v['task_name']}}</td>
                                <td class="created_at">{{$v['created_at']}}</td>
                                @if($v['status'] == 1)
                                    <td><span style="color:#999;">確認待ち</span></td>
                                @elseif($v['status'] == 2)
                                    <td><span style="color:#1CAF9A;">OK</span></td>
                                @elseif($v['status'] == 3)
                                    <td><span style="color:#D9534F;">間違いアリ</span></td>
                                @endif
                                <input class="task_status" type="hidden" value="{{$v['status']}}">
                                <td>
                                    <a href="#" class="btn btn-primary btn-xs"
                                       data-toggle="modal" data-target=".bs-example-modal-photo"
                                       onclick="code_info(this)">御覧</a>
                                    <a href="#" class="btn btn-danger btn-xs delete" data-toggle="modal" data-target="#confirm_box"
                                       onclick="abc({{$v['id']}})">Delete</a>
                                </td>
                                <input class="task_id" type="hidden" value="{{$v['task_id']}}">
                                <input class="upload_task_id" type="hidden" value="{{$v['id']}}">
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
                    <button type="button" class="close close-model" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">確認操作</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary delete-btn" onclick="delete_task()">確認</button>
                    <button type="button" class="btn btn-default close-model" data-dismiss="modal">キャンセル</button>
                </div>
            </div><!-- modal-content -->
        </div><!-- modal-dialog -->
    </div><!-- Confirm Box -->

    <div class="modal fade bs-example-modal-photo task_popup editor" tabindex="-1" role="dialog"
         aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-photo-viewer">
            <div class="modal-content">
                <div class="modal-header title">
                    <button aria-hidden="true" data-dismiss="modal" class="close close-model" type="button">&times;</button>
                    <h4 class="modal-title">title</h4>
                </div>
                <div class="modal-body" style="padding:0;">
                    <div id="editor1" task_file="" style="height: 541px;width: 58%;float: left;font-family: ">loading...</div>
                </div>

                <div style="width: 42%;float: right;">
                    <div id="editor2" task_id="0" style="height: 400px;">user@ubuntu:./a.out</div>
                    <br>
                    <div class="form-group confirm_box" style="width:90%;margin:0 auto">
                        <a class="btn btn-primary confirm_box confirm_box" onclick="edit_status(this,2)" task_id="0" style="width: 78px;margin: 0 0 15px 20px;">正解</a>
                        <a class="btn btn-danger confirm_box confirm_box"  onclick="edit_status(this,3)" task_id="0" style="width: 78px;margin: 0 0 15px 20px;">間違い</a>
                        <a class="btn btn-success-alt btn" onclick="execute_c_code(1)" style="width: 78px;margin: 0 0 15px 20px;">実行</a>
                        <a href="/teacher/download?file" target="_blank" class="btn btn-success-alt download" style="width: 130px;margin: 0 0 15px 20px;">ダウンロード</a>
                        <a class="btn btn-danger confirm_box_no" data-dismiss="modal" class="close" style="width: 130px;margin: 0 0 15px 20px;">閉じる</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection("content")
@section("js")
    <script src="/js/holder.js"></script>
    <script src="/ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var editor1 = ace.edit("editor1");
        editor1.setTheme("ace/theme/monokai");
        editor1.session.setMode("ace/mode/c_cpp");
        editor1.getSession().setUseWrapMode(true);
        editor1.setHighlightActiveLine(false);
        editor1.getReadOnly(true);
        //缩进大小
        editor1.getSession().setTabSize(4);


        var editor2 = ace.edit("editor2");
        editor2.setTheme("ace/theme/terminal");
        editor2.session.setMode("ace/mode/c_cpp");
        editor2.getSession().setUseWrapMode(true);
        editor2.setHighlightActiveLine(false);
        editor2.getSession().setTabSize(0);
        editor2.renderer.setShowGutter(false);
        //绑定键盘
        editor2.commands.addCommand({
            name: "enter_comend",
            bindKey: {win: "Enter", mac: "Return"},
            exec: function() {
                execute_c_code(0);
            }
        });

        function abc(id){
            delete_id = id;
        }
        function delete_task(){
            $.ajax({
                url : '/teacher/task/delete/'+delete_id,
                data : {},
                dataType : 'html',
                type : 'get',
                success : function(data){
                    messages('success','成功','task is deleted',2000);
                    $('.close-model').click();
                    $('tr[task_id="'+delete_id+'"]').hide(1000);
                },
                error : function(date){
                    alert('error')
                }
            });
        }

        function execute_c_code(is_button){
            //在光标处插入
            shell_code = editor2.session.getLine(editor2.selection.getCursor().row);
            task_id = $('#editor2').attr('task_id');
            $.ajax({
                url : '/execute_code/'+task_id,
                data : {argv:shell_code},
                dataType : 'html',
                type : 'get',
                success : function(data){
                    if(is_button)
                        editor2.insert(shell_code+"\n"+data+"\n");
                    else
                        editor2.insert("\n"+data+"\nuser@ubuntu:./a.out");
                    // $('#editor2 .ace_content').scrollTop(99)
                }
            });
        }

        editor2.on("change", function(e){
            const command = editor2.session.getLine(editor2.session.getLength() - 1);
            const patt = /(user@ubuntu:a.out)+[\w ]*/;
            if(!patt.test(command)){
                // editor2.removeLines(0,1);
                // editor2.replace(new Range(1,1,1,1), "user@ubuntu:");
            }
        })

        function edit_status(e, status)
        {
            $task_id = $(e).attr('task_id');
            $.ajax({
                url : '/teacher/task/edit/'+$task_id,
                data : {'status': status},
                dataType : 'html',
                type : 'get',
                success : function(data){
                    messages('success','成功','ok',2000);
                    $('.close-model').click();
                    location.reload();
                },
                error : function(date){
                    alert('error')
                }
            });
        }

        //課題ソースをAjaxで読み込む
        function code_info(e)
        {
            var upload_task_id = $(e).parent().siblings('input.upload_task_id').val();
            var upload_task_name = $(e).parent().siblings('td.task_number').html();
            $.ajax({
                url : '/teacher/task/'+upload_task_id,
                data : {'_token':'{{csrf_token()}}'},
                dataType : 'html',
                type : 'get',
                success : function(data){
                    data = JSON.parse(data);
                    editor1.setValue(data['code']);
                    editor1.moveCursorTo(0, 0);

                    $('img.ppt_show').attr('src',data['img']);
                    $('.editor .comment').val(data['comment']);
                    $('.editor .title>h4').html(upload_task_name);
                    file_id = data['file_id'];
                    $('.editor a.download').attr('href','/teacher/task/download/'+file_id);
                    $('.editor a.confirm_box').attr('task_id', upload_task_id);
                    $('#editor2').attr('task_id', upload_task_id);

                    // チェック済みの課題は保存ボタンはクリックできない
                    if(data['status'] == 2){
                        $('.task_popup a.confirm_box_ok').addClass('disabled').attr('onclick','');
                    }else{
                        $('.task_popup a.confirm_box_ok').removeClass('disabled').attr('onclick','confirm_popup(this)');
                    }
                }
            });


        }
    </script>
@endsection("js")

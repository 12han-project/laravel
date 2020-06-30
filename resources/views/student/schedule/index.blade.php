@extends("layout.app")
@section("leftpanel")
    @include("student.leftpanel")
@endsection("leftpanel")
@section("head")
    <link href="/css/fullcalendar.css" rel="stylesheet">
@endsection("head")
@section("content")
    <iframe class="zoom_iframe" src="" style="display: none"></iframe>
    <div class="contentpanel">
        <div id="calendar" class="fc fc-ltr">
            <table class="fc-header" style="width:100%">
                <tbody>
                <tr>
                    <td class="fc-header-center" width="33.33%"></td>
                    <td class="fc-header-center" width="33.33%">
                        <span class="fc-header-title">
                        <h2>2020年度-前半-時間割</h2>
                            <br>
                        </span>
                    </td>
                    <td class="text-right" width="33.33%">
                        <span class="fc-header-title ">
                            <a class="btn btn-xs btn-success" data-toggle="modal" data-target=".bs-example-modal-static">add new class</a>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="fc-content" style="position: relative;">
                <div class="fc-view fc-view-month fc-grid" style="position:relative" unselectable="on">
                    <div class="fc-event-container" style="position:absolute;z-index:8;top:0;left:0">
                    </div>
                    <table class="fc-border-separate" style="width:100%" cellspacing="0">
                        <thead>
                        <tr class="fc-first fc-last">
                            <th class="fc-day-header fc-sun fc-widget-header" style="width: 4%;"></th>
                            <th class="fc-day-header fc-sun fc-widget-header" style="width: 16%;">月</th>
                            <th class="fc-day-header fc-mon fc-widget-header" style="width: 16%;">火</th>
                            <th class="fc-day-header fc-tue fc-widget-header" style="width: 16%;">水</th>
                            <th class="fc-day-header fc-wed fc-widget-header" style="width: 16%;">木</th>
                            <th class="fc-day-header fc-thu fc-widget-header" style="width: 16%;">金</th>
                            <th class="fc-day-header fc-fri fc-widget-header" style="width: 16%;">土</th>
                        </tr>
                        </thead>
                        <tbody class="schedule_body">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bs-example-modal-static" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
                    <h4 class="modal-title">Create New Class</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal form-bordered">

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Class Name</label>
                            <div class="col-sm-6">
                                <input type="text" name="title" placeholder="" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Zoom URL</label>
                            <div class="col-sm-6">
                                <input type="text" name="url" placeholder="https://" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">曜日</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label><input name="week" type="radio" value="1" > 月</label>
                                    <label><input name="week" type="radio" value="2" > 火</label>
                                    <label><input name="week" type="radio" value="3" > 水</label>
                                    <label><input name="week" type="radio" value="4" > 木</label>
                                    <label><input name="week" type="radio" value="5" > 金</label>
                                    <label><input name="week" type="radio" value="6" > 土</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">時限</label>
                            <div class="col-sm-7">
                                <div class="radio">
                                    <label><input name="time_limit" type="radio" value="1" > 1限</label>
                                    <label><input name="time_limit" type="radio" value="2" > 2限</label>
                                    <label><input name="time_limit" type="radio" value="3" > 3限</label>
                                    <label><input name="time_limit" type="radio" value="4" > 4限</label>
                                    <label><input name="time_limit" type="radio" value="5" > 5限</label>
                                    <label><input name="time_limit" type="radio" value="6" > 6限</label>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-sm-6 col-sm-offset-3">
                                    <input  class="btn btn-primary save" type="button" value="保存">&nbsp;
                                    <input  class="btn btn-danger delete" type="button" value="Delete">
                                </div>
                            </div>
                        </div>



                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection("content")
@section("js")
    <script src="/js/fullcalendar.min.js"></script>
    <script>
        create_black_schedule();
        get_schedule();

        $("a[data-toggle=modal]").click(function(){
            // $('form input').removeAttr('checked');
            // $('form input[type=text]').attr('value','');
        });

        $('input.save').click(function(){
            add_new_class();
        });

        $('input.delete').click(function(){
            delete_class();
        });

        function delete_class(){
            const datum = {
                "uid": "{{session('user_info')['number']}}",
                "week": $('form input[name=week]:checked').val(),
                "time_limit": $('form input[name=time_limit]:checked').val(),
            };
            $.ajax({
                url : "/student/schedule",
                type : "delete",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data : datum,
                dataType:"text",
                success:function(data){
                    if(data){
                        $('button.close').click();
                        $('.schedule_body').html(" ");
                        create_black_schedule();
                        get_schedule();
                        $('form input').removeAttr('checked');
                    }
                },
                error:function(){
                    alert('error')
                }
            });
        }

        function add_new_class(){
            const datum = {
                "uid": "{{session('user_info')['number']}}",
                "title": $('form input[name=title]').val(),
                "url": $('form input[name=url]').val(),
                "week": $('form input[name=week]:checked').val(),
                "time_limit": $('form input[name=time_limit]:checked').val()
            };

            $.ajax({
                url : "/student/schedule/store",
                type : "get",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data : datum,
                dataType:"json",
                success:function(data){
                    if(data){
                        $('button.close').click();
                        $('.schedule_body').html(" ");
                        create_black_schedule();
                        get_schedule();
                        $('form input').removeAttr('checked');
                        $('form input[type=text]').attr('value','');
                    }
                },
                error:function(){
                    alert('error')
                }
            });
        }

        function open_zoom(e){
            window.event.returnValue = false;
            $('.zoom_iframe').attr("src", $(e).attr('href'));
        }

        function get_schedule(){
            const datum = {
                "uid": "{{session('user_info')['number']}}",
            };
            $.ajax({
                url : "/student/schedule/get",
                type : "get",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data : datum,
                dataType:"json",
                success:function(data){
                    data.map(v => {
                        $('.schedule_body>tr:nth-child('+v.time_limit+')>td:nth-child('+(v.week+1)+') div.title').html(v.title).css({
                            "padding": "10px 7px",
                            "background-color": "#3788d8",
                            "color": "#FFF",
                            "border-radius": "3px",
                        }).attr({
                            "title": v.title,
                            "url": v.url,
                            "week": v.week,
                            "time_limit": v.time_limit,
                        });
                        $('.schedule_body>tr:nth-child('+v.time_limit+')>td:nth-child('+(v.week+1)+') div.url>a').attr({'href':v.url}).html('ZOOMで開く').css({
                            "padding": "10px 7px",
                            "background-color": "green",
                            "color": "#FFF",
                            "border-radius": "3px",
                        });
                    });
                },
                error:function(){
                }
            });
        }

        function edit_class(e){
            $('form input').removeAttr('checked');
            $('form input[type=input]').val('');
            $('form input[name=title]').val($(e).attr('title'));
            $('form input[name=url]').val($(e).attr('url'));
            $('form input[name=week][value='+($(e).attr('week'))+']').attr('checked','checked');
            $('form input[name=time_limit][value='+($(e).attr('time_limit'))+']').attr('checked','checked');

            $('a[data-toggle=modal]').click()
        }

        function create_black_schedule(){
            var html = "";
            for (let i = 1; i <= 6; i++){
                html += `<tr class="fc-week">`;
                html += `<td class="fc-day fc-sun fc-widget-content fc-past">
                                        <div style="min-height: 129px;">
                                            <div class="fc-day-number">${i}限</div>
                                        </div>
                                    </td>`;
                for (let j = 1; j <= 6; j++){
                    html += `<td class="fc-day fc-wed fc-widget-content fc-past">
                                <div>
                                    <div class="title" onclick="edit_class(this)" style=""></div>
                                    <div class="content" style="margin-top: 12px">
                                        <div class="url" style="position: relative; height: 0px;"><a href="" onclick="open_zoom(this)"></a></div>
                                    </div>
                                </div>
                            </td>`;
                }

                html += `</tr>`;
            }
            $('.schedule_body').html(html);
        }
    </script>

@endsection("js")

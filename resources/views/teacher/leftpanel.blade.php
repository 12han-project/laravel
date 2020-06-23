<div class="leftpanel">

    <div class="logopanel ">
        <h1><span>[</span> 12班 <span>]</span></h1>
    </div><!-- logopanel -->

    <div class="leftpanelinner">
        <h5 class="sidebartitle">操作</h5>
        <ul class="nav nav-pills nav-stacked nav-bracket">
            <li><a href="/teacher/class/create"><i class="fa fa-user"></i> <span>授業登録</span></a></li>
        </ul>
        <h5 class="sidebartitle">授業リスト</h5>
        <ul class="nav nav-pills nav-stacked nav-bracket">
{{--            <li><a href="public/index.html"><i class="fa fa-home"></i> <span>index</span></a></li>--}}
            @if($classes = App\Classes_users::where('user_name',session('user_info')['number'])->get())
                @foreach($classes as $k)
                    <?php $class = App\Classes::where('id', $k['class_id'])->first();?>
                    <li class="nav-parent" name="class-{{$k['class_id']}}"><a href="#"><i class="fa fa-tasks"></i> <span>{{$class['name']}}</span></a>
                        <ul class="children">
                            <li name="task_list"><a href="/teacher/class/{{$k['class_id']}}/students"><i class="fa fa-caret-right"></i> 学生リスト</a></li>
                            <li name="upload"><a href="/teacher/class/{{$k['class_id']}}/tasks"><i class="fa fa-caret-right"></i> 課題リスト</a></li>
                        </ul>
                    </li>
                @endforeach
            @endif
        </ul>

    </div><!-- leftpanelinner -->
</div><!-- leftpanel -->

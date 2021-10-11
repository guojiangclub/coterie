<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($users)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>头像</th>
                <th>昵称</th>
                <th>手机</th>
                <th>加入时间</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach($users as $list)
                <tr>
                    <td><img src="{{$list->avatar}}" class="avatar"></td>
                    <td>{{$list->nick_name}}</td>
                    <td>{{$list->mobile}}</td>
                    <td>{{$list->created_at}}</td>
                    <td>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!!$users->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!!$users->appends(request()->except('page'))->render() !!}
        </div>
        <!-- /.box-body -->
    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>













<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($lists)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>提问用户</th>
                <th>所属圈子</th>
                <th>邀请用户</th>
                <th>提问时间</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach($lists as $list)
                <tr>
                    <td>{!! $list->user->nick_name !!}</td>
                    <td>{{$list->coterie->name}}</td>
                    <td>{{$list->atUser->nick_name}}</td>
                    <td>{{$list->created_at}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary"
                           href="{{route('admin.coterie.question.show', ['id' => $list->id])}}">
                            <i data-original-title="查看" data-toggle="tooltip" data-placement="top"
                               class="fa fa-pencil-square-o" title=""></i></a>


                        @if(request('status')=='forbidden')
                            <a data-href="{{route('admin.coterie.question.restore')}}" data-action="recommend"
                               data-id="{{$list->id}}"
                               class="btn btn-xs btn-primary restore-action"><i class="fa fa-refresh"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top" title=""
                                                                                data-original-title="恢复"></i></a>
                        @else
                            <a href="javascript:;" class="btn btn-xs btn-danger delete-content" data-id="{{$list->id}}"
                               data-href="{{route('admin.coterie.question.delete')}}">
                                <i data-toggle="tooltip" data-placement="top" class="fa fa-trash" title=""
                                   data-original-title="删除"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="pull-left">
            &nbsp;&nbsp;共&nbsp;{!!$lists->total() !!} 条记录
        </div>

        <div class="pull-right id='ajaxpag'">
            {!!$lists->appends(request()->except('page'))->render() !!}
        </div>
        <!-- /.box-body -->
    @else
        <div>
            &nbsp;&nbsp;&nbsp;当前无数据
        </div>
    @endif
</div>













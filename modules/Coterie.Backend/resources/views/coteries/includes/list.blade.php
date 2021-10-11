<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($lists)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>圈子名称</th>
                <th>圈主</th>
                <th>成员数量</th>
                <th>嘉宾数量</th>
                <th>主题数量</th>
                <th>圈子类型</th>
                <th>是否推荐</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach($lists as $list)
                <tr>
                    <td><img src="{{$list->avatar}}" class="avatar"> {{$list->name}}</td>
                    <td>{{$list->user->nick_name??''}}</td>
                    <td><a href="{{route('admin.coterie.members',['id'=>$list->id,'type'=>'all'])}}"
                           title="点击查看成员">{{$list->member_count}}</a></td>
                    <td><a href="{{route('admin.coterie.members',['id'=>$list->id,'type'=>'guest'])}}"
                           title="点击查看嘉宾">{{$list->getGuestNum()}}</a></td>
                    <td>{{$list->content_count}}</td>
                    <td>{{$list->type_text}}</td>
                    <td>{!! $list->recommend_at?'已推荐':'未推荐' !!}</td>
                    <td>
                        <a class="btn btn-xs btn-primary"
                           href="{{route('admin.coterie.show', ['id' => $list->id])}}">
                            <i data-original-title="查看" data-toggle="tooltip" data-placement="top"
                               class="fa fa-pencil-square-o" title=""></i></a>

                        @if(request('status')!='forbidden')
                            @if($list->recommend_at)
                                <a data-href="{{route('admin.coterie.switchRecommend')}}" data-action="cancel"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-danger re-action"><i class="fa fa-times" data-toggle="tooltip"
                                                                              data-placement="top" title=""
                                                                              data-original-title="取消推荐"></i></a>
                            @else
                                <a data-href="{{route('admin.coterie.switchRecommend')}}" data-action="recommend"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-primary re-action"><i class="fa fa-check"
                                                                               data-toggle="tooltip"
                                                                               data-placement="top" title=""
                                                                               data-original-title="推荐"></i></a>
                            @endif

                            <a href="javascript:;" class="btn btn-xs btn-danger delete-coterie" data-id="{{$list->id}}"
                               data-href="{{route('admin.coterie.delete')}}">
                                <i data-toggle="tooltip" data-placement="top" class="fa fa-trash" title=""
                                   data-original-title="删除"></i></a>
                        @endif

                        @if(request('status')=='forbidden')
                            <a data-href="{{route('admin.coterie.restore')}}" data-action="recommend"
                               data-id="{{$list->id}}"
                               class="btn btn-xs btn-primary restore-action"><i class="fa fa-refresh"
                                                                           data-toggle="tooltip"
                                                                           data-placement="top" title=""
                                                                           data-original-title="恢复"></i></a>
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













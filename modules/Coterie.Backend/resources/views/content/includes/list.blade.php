<div class="hr-line-dashed"></div>
<div class="table-responsive">
    @if(count($lists)>0)
        <table class="table table-hover table-striped">
            <tbody>
            <!--tr-th start-->
            <tr>
                <th>发布用户</th>
                <th>所属圈子</th>
                <th>类型</th>
                <th>标签</th>
                <th>评论数</th>
                <th>点赞数</th>
                <th>是否推荐</th>
                <th>是否置顶</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
            <!--tr-th end-->
            @foreach($lists as $list)
                <tr>
                    <td>{!! $list->user->nick_name !!}</td>
                    <td>{{$list->coterie->name}}</td>
                    <td>{{$list->content_type_text}}</td>
                    <td>
                        @if($list->TagsListInfo)
                            @foreach($list->TagsListInfo as $tag)
                                <span>{!! $tag !!}</span>
                            @endforeach
                        @endif
                    </td>
                    <td>{{$list->comment_count}}</td>
                    <td>{{$list->praise_count}}</td>
                    <td>{!! $list->recommended_at?'已推荐':'未推荐' !!}</td>
                    <td>{!! $list->stick_at?'已置顶':'未置顶' !!}</td>
                    <td>{{$list->created_at}}</td>
                    <td>
                        <a class="btn btn-xs btn-primary"
                           href="{{route('admin.coterie.content.show', ['id' => $list->id])}}">
                            <i data-original-title="查看" data-toggle="tooltip" data-placement="top"
                               class="fa fa-pencil-square-o" title=""></i></a>

                        @if(request('status')=='audited')
                            @if($list->recommended_at)
                                <a data-href="{{route('admin.coterie.content.switchRecommend')}}" data-action="cancel"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-danger re-action"><i class="fa fa-times" data-toggle="tooltip"
                                                                              data-placement="top" title=""
                                                                              data-original-title="取消推荐"></i></a>
                            @else
                                <a data-href="{{route('admin.coterie.content.switchRecommend')}}"
                                   data-action="recommend"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-primary re-action"><i class="fa fa-check"
                                                                               data-toggle="tooltip"
                                                                               data-placement="top" title=""
                                                                               data-original-title="推荐"></i></a>
                            @endif

                            @if($list->stick_at)
                                <a data-href="{{route('admin.coterie.content.switchStick')}}" data-action="cancel"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-danger stick-action"><i class="fa fa-level-down"
                                                                                 data-toggle="tooltip"
                                                                                 data-placement="top" title=""
                                                                                 data-original-title="取消置顶"></i></a>
                            @else
                                <a data-href="{{route('admin.coterie.content.switchStick')}}" data-action="recommend"
                                   data-id="{{$list->id}}"
                                   class="btn btn-xs btn-primary stick-action"><i class="fa fa-level-up"
                                                                                  data-toggle="tooltip"
                                                                                  data-placement="top" title=""
                                                                                  data-original-title="置顶"></i></a>
                            @endif
                        @endif

                        @if(request('status')=='unaudited')
                            <a data-href="{{route('admin.coterie.content.switchStick')}}"
                               data-id="{{$list->id}}"
                               class="btn btn-xs btn-primary audited-action"><i class="fa fa-check-circle"
                                                                              data-toggle="tooltip"
                                                                              data-placement="top" title=""
                                                                              data-original-title="审核通过"></i></a>
                        @endif

                        @if(request('status')=='forbidden')
                            <a data-href="{{route('admin.coterie.content.restore')}}" data-action="recommend"
                               data-id="{{$list->id}}"
                               class="btn btn-xs btn-primary restore-action"><i class="fa fa-refresh"
                                                                                data-toggle="tooltip"
                                                                                data-placement="top" title=""
                                                                                data-original-title="恢复"></i></a>
                        @else
                            <a href="javascript:;" class="btn btn-xs btn-danger delete-content" data-id="{{$list->id}}"
                               data-href="{{route('admin.coterie.content.delete')}}">
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













<div class="tabs-container">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                <div class="table-responsive">
                    <div class="hr-line-dashed"></div>
                    <div class="table-responsive">
                        @if(count($members)>0)
                            <table class="table table-hover table-striped">
                                <tbody>
                                <!--tr-th start-->
                                <tr>
                                    <th>昵称</th>
                                    <th>手机</th>
                                    <th>角色</th>
                                    <th>状态</th>
                                    <th>加入时间</th>
                                </tr>
                                <!--tr-th end-->
                                @foreach($members as $member)
                                    <tr>
                                        <td>{{$member->user->nick_name}}</td>
                                        <td>{{$member->user->mobile}}</td>
                                        <td>{{$member->type_text}}</td>
                                        <td>{{$member->status_text}}</td>
                                        <td>{{$member->joined_at}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                            <div class="pull-left">
                                &nbsp;&nbsp;共&nbsp;{!!$members->total() !!} 条记录
                            </div>

                            <div class="pull-right id='ajaxpag'">
                                {!!$members->appends(request()->except('page'))->render() !!}
                            </div>
                            <!-- /.box-body -->
                        @else
                            <div>
                                &nbsp;&nbsp;&nbsp;当前无数据
                            </div>
                        @endif

                    </div>

                </div><!-- /.box-body -->
                <a href="javascript:history.go(-1)"
                   class="btn btn-danger">返回</a>
            </div>
        </div>
    </div>
</div>
<div class="tabs-container">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body form-horizontal">
                <input type="hidden" name="id" value="{{$coterie->id}}">

                <div class="form-group">
                    <label class="control-label col-md-2">圈子名称：</label>
                    <div class="col-md-9">
                        <p class="form-control-static"><img src="{!! $coterie->avatar !!}" width="50"
                                                            style="border-radius: 50%"></p>
                        <p class="form-control-static">
                            {{$coterie->name}}
                            @if($coterie->deleted_at)
                                <span style="color: red">【已删除】</span>
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">圈主：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{$coterie->user->nick_name}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">圈子描述：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{!! $coterie->description !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">圈子类型：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{$coterie->type_text}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">圈子数据：</label>
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td>成员数</td>
                                    <td>嘉宾数</td>
                                    <td>主题数</td>
                                    <td>提问数</td>
                                    <td>精华数</td>
                                </tr>
                                <tr>
                                    <td>{{$coterie->member_count}}人</td>
                                    <td>{{$coterie->getGuestNum()}}人</td>
                                    <td>{{$coterie->content_count}}</td>
                                    <td>{{$coterie->ask_count}}</td>
                                    <td>{{$coterie->recommend_count}}</td>
                                </tr>


                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">推荐状态：</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {!! $coterie->recommend_at?'已推荐【'.$coterie->recommend_at.'】':'未推荐' !!}</p>
                    </div>
                </div>

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8 controls">
                        <a href="javascript:history.go(-1)"
                           class="btn btn-danger">返回</a>

                        @if(!$coterie->deleted_at)
                            <button type="button" class="btn btn-primary re-action"
                                    data-action="{{$coterie->recommend_at?'cancel':'recommend'}}">
                                {!! $coterie->recommend_at?'取消推荐':'推荐' !!}</button>
                        @else
                            <button type="button" class="btn btn-primary restore-action">恢复</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>

</div>

<script>
    $(function () {

        $('.re-action').on('click', function () {
            var data = {
                id: $('input[name="id"]').val(),
                action: $(this).data('action'),
                _token: _token
            };
            var rejectUrl = '{{route('admin.coterie.switchRecommend')}}';
            $.post(rejectUrl, data, function (result) {
                if (result.status) {
                    swal({
                        title: "操作成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        location.reload();
                    });
                } else {
                    swal('操作失败', result.message, 'error');
                }
            });
        });

        $('.restore-action').on('click', function () {
            swal({
                title: "确定恢复该圈子吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                var data = {
                    id: $('input[name="id"]').val(),
                    _token: _token
                };
                var rejectUrl = '{{route('admin.coterie.restore')}}';
                $.post(rejectUrl, data, function (result) {
                    if (result.status) {
                        swal({
                            title: "恢复成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal('操作失败', result.message, 'error');
                    }
                });
            });
        });
    });
</script>
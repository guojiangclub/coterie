<style type="text/css">
    .avatar {
        width: 50px;
        height:50px;
        border-radius: 100%
    }
</style>
<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="{{ Active::query('status','') }}"><a no-pjax
                                                        href="{{route('admin.coterie.list')}}">所有圈子</a>
        </li>
        <li class="{{ Active::query('status','recommend') }}"><a no-pjax
                                                                 href="{{route('admin.coterie.list',['status'=>'recommend'])}}">推荐圈子</a>
        </li>
        <li class="{{ Active::query('status','forbidden') }}"><a no-pjax
                                                                 href="{{route('admin.coterie.list',['status'=>'forbidden'])}}">已删除圈子</a>
        </li>

    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open( [ 'route' => ['admin.coterie.list'], 'method' => 'get', 'id' => 'commentsurch-form','class'=>'form-horizontal'] ) !!}

                <div class="row">
                    <input type="hidden" id="audit" name="status"
                           value="{{request('status')}}">


                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="value" value="{{request('value')}}" placeholder="请输入圈子名称"
                                   class=" form-control"> <span
                                    class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                    </div>


                </div>
                {!! Form::close() !!}

                <div class="table-responsive">
                    @include('account-backend::coteries.includes.list')
                </div><!-- /.box-body -->

            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('.re-action').on('click', function () {
            var data = {
                id: $(this).data('id'),
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

        $('.delete-coterie').on('click', function () {
            var obj = $(this);
            swal({
                title: "确定删除该圈子吗?",
                text: "",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: "取消",
                closeOnConfirm: false
            }, function () {
                var data = {
                    id: obj.data('id'),
                    _token: _token
                };
                var rejectUrl = '{{route('admin.coterie.delete')}}';
                $.post(rejectUrl, data, function (result) {
                    if (result.status) {
                        swal({
                            title: "删除成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal('删除失败', result.message, 'error');
                    }
                });
            });
        });

        $('.restore-action').on('click', function () {
            var obj = $(this);
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
                    id: obj.data('id'),
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
<script>
    $(function () {

        //推荐状态
        $('.re-action').on('click', function () {
            var data = {
                id: $(this).data('id'),
                action: $(this).data('action'),
                _token: _token
            };
            var rejectUrl = '{{route('admin.coterie.content.switchRecommend')}}';
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

        //置顶状态
        $('.stick-action').on('click', function () {
            var data = {
                id: $(this).data('id'),
                action: $(this).data('action'),
                _token: _token
            };
            var rejectUrl = '{{route('admin.coterie.content.switchStick')}}';
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

        $('.delete-content').on('click', function () {
            var obj = $(this);
            swal({
                title: "确定删除该动态内容吗?",
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
                var rejectUrl = '{{route('admin.coterie.content.delete')}}';
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
                title: "确定恢复该动态吗?",
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
                var rejectUrl = '{{route('admin.coterie.content.restore')}}';
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

        $('.audited-action').on('click', function () {
            var obj = $(this);
            swal({
                title: "确定审核通过该话题吗?",
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
                var rejectUrl = '{{route('admin.coterie.content.audited')}}';
                $.post(rejectUrl, data, function (result) {
                    if (result.status) {
                        swal({
                            title: "审核成功！",
                            text: "",
                            type: "success"
                        }, function () {
                            location.reload();
                        });
                    } else {
                        swal('审核失败', result.message, 'error');
                    }
                });
            });
        });
    });
</script>
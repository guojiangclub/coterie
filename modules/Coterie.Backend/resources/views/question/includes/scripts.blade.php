<script>
    $(function () {
        $('.delete-content').on('click', function () {
            var obj = $(this);
            swal({
                title: "确定删除该提问内容吗?",
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
                var rejectUrl = '{{route('admin.coterie.question.delete')}}';
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
                title: "确定恢复该提问吗?",
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
                var rejectUrl = '{{route('admin.coterie.question.restore')}}';
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
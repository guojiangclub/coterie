<script>

    function custom() {

        var html = $('.category_select').html();
        swal({
                title: "请选择服务类目",
                text: html,
                html: true,
                showCancelButton: true,
                closeOnConfirm: true,
                confirmButtonText: "确认",
                cancelButtonText: "取消",
            },
            function () {
                var val = $('.showSweetAlert #category_select').find("option:selected").val();
                var key = $('.showSweetAlert #category_select').find("option:selected").data('key');
                $('.category_txt').text(val);
                window.category_key=key;

            });
    }



    $('body').on('click',".tester-delete",function () {
        var that = $(this);
        var postUrl = that.data('href');
        var body = {
            _token: _token
        };

        swal({
            title: "确定要删除该体验者微信么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            $.post(postUrl, body, function (result) {
                if (result.status) {
                    swal({
                        title: "删除成功！",
                        text: "",
                        type: "success"
                    }, function () {
                        that.parent().parent().remove();
                    });
                } else {
                    swal({
                        title: "删除失败",
                        text: result.message,
                        type: "error"
                    });
                }
            });
        });
    });


    function tester_create() {
        swal({
                title: "添加体验微信",
                text: "",
                type: "input",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "保存",
                cancelButtonText: "取消",
                animation: "slide-from-top",
                inputPlaceholder: "请输入微信号"
            },
            function (inputValue) {

                if (inputValue == "") {
                    swal.showInputError("请输入微信号");
                    return false
                }
                var url = "{{route('admin.account.coterie.mini.version.testerBind',['_token'=>csrf_token()])}}";
                var appid = "{{$appid}}";

                var data = {'wechatid': inputValue, 'appid': appid}

                var delurl="{{route('admin.account.coterie.mini.version.testerunBind',['appid'=>$appid])}}"+'&wechatid='+inputValue;

                $.post(url, data, function (ret) {
                    if (!ret.status) {
                        swal("创建失败!", ret.message, "warning");
                    } else {
                        swal({
                            title: "创建成功",
                            text: "",
                            type: "success",
                            confirmButtonText: "确定"
                        }, function () {
                            var tr=[" <tr id="+inputValue+">",
                                "<td class=\"col-md-10\">"+inputValue+"</td>",
                                "              <td class=\"col-md-2\">",
                                "                         <a class=\"btn btn-xs btn-danger tester-delete pull-left \"",
                                "                                 data-href="+delurl+">",
                                "                                      <i data-toggle=\"tooltip\" data-placement=\"top\"",
                                "                                                   class=\"fa fa-trash\"",
                                "                title=\"删除\"></i></a>",
                                "      </td>",
                                " </tr>"].join("");

                            $('#tester_wechat').append(tr);
                        });
                    }
                });

            });
    }


</script>


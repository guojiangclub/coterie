
<script>
    var category = {};
</script>

<div class="col-md-12">
    <style>
        .colorpicker-visible{
            z-index: 9999999;
        }
        .title {
            font-size: 40px;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            display: block;
            text-align: center;
            margin: 20px 0 10px 0px;
        }

        .links {
            /*text-align: center;*/
            margin-bottom: 20px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>


    <div class="links">

    </div>
</div>


<div class="row">

    <div class="col-md-10">

        <div class="box box-default">

            <div class="box-header with-border">

                <h3 class="box-title">{{$version->name}}系统最新版本</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>

                </div>
            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">

                        <tbody>

                        <tr>
                            <td class="col-md-4">版本号</td>
                            <td class="col-md-8"><span class="label label-default">{{$version->version}}</span></td>
                        </tr>
                        <tr>
                            <td class="col-md-4">描述</td>
                            <td class="col-md-8">{{$version->description}}</td>
                        </tr>
                        <tr>
                            <td class="col-md-4">发布时间</td>
                            <td class="col-md-8">{{date("Y-m-d",strtotime($version->created_at))}}</td>
                        </tr>

                        @if($version->trial_version_img)
                        <tr>
                            <td class="col-md-4">扫码体验</td>
                            <td class="col-md-8">
                                <img width="150" height="150"
                                     src="{{$version->trial_version_img}}" alt="">
                            </td>
                        </tr>
                        @endif

                        @if($version->note)
                            <tr>
                                <td class="col-md-4">说明</td>
                                <td class="col-md-8">
                                    {{$version->note}}
                                </td>
                            </tr>
                        @endif


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-5">

        <div class="box box-default">

            <div class="box-header with-border">

                @if($status_message)

                    <h3 class="box-title">{{$status_message}}</h3>

                    @if(isset($audit->status) AND $audit->status==2)
                        <a class="pull-right" href="">刷新查看最状态</a>
                    @endif

                @else
                    <h3 class="box-title">发布小程序</h3>

                @endif

            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>

                        @if(isset($audit->status) AND $audit->status==2)
                            <tr>
                                <td>状态</td>
                                <td>
                                    待审核 (撤回上限每天一次每个月10次)
                                </td>
                            <tr>
                                <td>操作</td>
                                <td>
                                    <a class="label label-info pull-left" onclick="withdrawAudit();">撤回审核</a>
                                </td>
                            </tr>
                            </tr>
                        @endif

                        @if(isset($audit->status) AND $audit->status==0)
                            <tr>
                                <td>状态</td>
                                <td>
                                    审核成功
                                </td>
                            </tr>
                            <tr>
                                <td>操作</td>
                                <td>
                                    <a  class="label label-info pull-left" onclick="release()">发布上线</a>
                                    <a class="label label-danger pull-right" onclick='Reexamination("{{isset($audit->id)?$audit->id:0}}")'>取消发布</a>
                                </td>
                            </tr>
                        @endif

                        @if(isset($audit->status) AND $audit->status==1)
                            <tr>
                                <td>状态</td>
                                <td>
                                    审核失败
                                    <a class="label label-info pull-right" href="{{route('admin.account.coterie.mini.version',['repost'=>1])}}">重新发布</a>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    失败原因:{!!$audit->reason!!}
                                </td>
                            </tr>
                        @endif

                        <tr>
                            <td class="col-md-4">版本号</td>
                            <td class="col-md-8"><span class="label label-default">
                                    @if(!$status_message)
                                        {{$version->version}}
                                     @else
                                        {{$audit->saas->version}}
                                     @endif
                                </span></td>
                        </tr>
                        <tr>
                            <td class="col-md-4">描述</td>
                            <td class="col-md-8">

                                @if(!$status_message)
                                    {{$version->description}}
                                @else
                                    {{$audit->saas->description}}
                                @endif

                            </td>
                        </tr>


                        @if(!$status_message)


                            <tr>
                                <td class="col-md-4" id="category_id" data-key=0>服务类目</td>

                                <td class="col-md-6">

                                <span class="category_txt">
                                    @if(count($category)>0)
                                        @foreach($category as $key=>$item)
                                            @foreach($item as $citem) @if(!is_numeric($citem) AND $key==0){{$citem}}@endif @endforeach @endforeach
                                    @endif
                                </span>

                                        <a onclick="custom()"  class="label label-info pull-right" >修改</a>

                                </td>

                            </tr>

                        @else

                            @if($audit->category)

                                <?php $a_category=json_decode($audit->category)?>

                                <tr>
                                    <td class="col-md-4" id="category_id" data-key=0>服务类目</td>

                                    <td class="col-md-6">

                                        <span class="category_txt">

                                            {{$a_category->first_class}} {{$a_category->second_class}}

                                        </span>


                                    </td>

                                </tr>

                            @endif

                        @endif




                        @if(count($category)>0)

                        <div class="category_select"  style="display: none;"><select id="category_select"
                                                                                    style="height: 35px;width: 250px;">
                                    @foreach($category as $key=>$item)
                                        @foreach($item as $ckey=>$citem)
                                        <script>
                                            category["{{$key}}"] = {
                                                'first_class': "{{$item->first_class}}",
                                                'second_class': "{{$item->second_class}}",
                                                'first_id':"{{$item->first_id}}",
                                                'second_id':"{{$item->second_id}}",

                                            };
                                        </script>
                                            @if(!is_numeric($citem) AND $ckey=='first_class')<option data-key="{{$key}}" value="{{$item->first_class}} {{$item->second_class}}"> {{$item->first_class}} {{$item->second_class}}</option>
                                           @endif @endforeach
                                    @endforeach

                            </select></div>
                        @endif


                        {{--@if(isset($audit->auditid) AND $audit->auditid)--}}
                        {{--<tr>--}}
                        {{--<td class="col-md-4">提交审核时间</td>--}}
                        {{--<td class="col-md-8">--}}
                        {{--{{$audit->audit_time}}--}}
                        {{--</td>--}}
                        {{--</tr>--}}
                        {{--@endif--}}


                        @if(!$status_message)

                            <tr id="theme-no" style="display: none">
                                <td class="col-md-4">主题配色</td>
                                <td class="col-md-8">
                                    <span></span>
                                    <br>
                                    <img id="theme-no-img" style="display: none" width="80"  height="120" src="" alt="">
                                </td>
                            </tr>


                            <tr>
                                <td class="col-md-4">主题配色</td>
                                <td class="col-md-8">
                                    <a class="label label-info" id="users-btn" data-toggle="modal"
                                       data-target="#modal" data-backdrop="static" data-keyboard="false"
                                       data-url="{{route('admin.account.coterie.mini.version.model',['template_id'=>$version->template_id,'uuid'=>$uuid,'wechatappid'=>$appid])}}">
                                        主题配色
                                    </a>
                                    <span class="pull-right">(选择主题配色，生成体验版小程序)</span>
                                </td>

                            </tr>

                        @else

                            @if(isset($audit->theme) AND $audit->theme)

                                <?php  $publish_theme=json_decode($audit->theme,true); ?>
                                <tr>
                                    <td class="col-md-4">主题配色</td>
                                    <td class="col-md-8">
                                        <span>{{isset($publish_theme['title'])?$publish_theme['title']:''}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="col-md-4"></td>
                                    <td class="col-md-8">
                                        @if(isset($publish_theme['img']))
                                            <img width="80"  height="120" src="{{$publish_theme['img']}}" alt="">
                                        @endif
                                    </td>
                                </tr>

                        @endif



                        @endif


                        {{--操作--}}
                        @if(!$status_message)

                            <tr class="CommitMiniCodeUpload" style="display: none">

                                <td class="col-md-4">代码上传</td>

                                <td class="col-md-8">
                                            <span class="pull-right installed CommitMiniCodeUpload_success"
                                                  style="display: none">

                                                <i class="fa fa-check"></i>
                                            </span>
                                    <span class="pull-right installed CommitMiniCodeUpload_error" style="display: none">

                                                <i class="fa fa-times"></i>
                                            </span>
                                </td>

                            </tr>
                          @endif

                            <tr class="CommitMiniCodeExamine" style="display: none">
                                <td class="col-md-4">提交审核</td>

                                <td class="col-md-8">

                                    <a onclick="CommitMiniCodeExamine(CommitMiniCodeExamine_url,CommitMiniCodeExamine_data)"
                                       class="label label-info pull-right CommitMiniCodeExamine_a">提交审核</a>

                                    <span class="pull-right installed CommitMiniCodeExamine_success" style="display: none">

                                                <i class="fa fa-check"></i>
                                            </span>
                                    <span class="pull-right installed CommitMiniCodeExamine_error" style="display: none">

                                                <i class="fa fa-times"></i>
                                            </span>
                                </td>

                            </tr>

                            <tr id="experience_code" style="display: none">
                                <td class="col-md-4">体验二维码</td>
                                <td class="col-md-8">
                                    <img width="150" height="150"
                                         src="{{route('admin.account.coterie.mini.version.getQrCode')}}" alt="">
                                </td>
                            </tr>

                        {{--体验者微信--}}

                        </tbody>

                        <table class="table">
                            <tbody id="tester_wechat">

                            <tr>
                                <td class="col-md-10">
                                    体验者微信
                                </td>
                                <td class="col-md-2">
                                    <a onclick="tester_create()" class="label label-info pull-left">添加</a>
                                </td>
                            </tr>
                            @if(count($testers)>0)
                                @foreach($testers as $item)
                                    <tr id="{{$item->wechatid}}">
                                        <td class="col-md-10">{{$item->wechatid}}</td>
                                        <td class="col-md-2">
                                            <a class="btn btn-xs btn-danger tester-delete pull-left "
                                               data-href="{{route('admin.account.coterie.mini.version.testerunBind',['id'=>$item->wechatid,'appid'=>$appid])}}">
                                                <i data-toggle="tooltip" data-placement="top"
                                                   class="fa fa-trash"
                                                   title="删除"></i></a>
                                        </td>
                                    </tr>
                                @endforeach

                            @endif

                            </tbody>

                        </table>
                    </table>
                </div>
            </div>

            <div id="modal" class="modal inmodal fade"></div>

        </div>

    </div>

    @if($publish)
    <div class="col-md-5">

        <div class="box box-default">

            <div class="box-header with-border">

                <h3 class="box-title">线上版本（{{$appid}}）</h3>

            </div>

            <div class="box-body">
                <div class="table-responsive">
                    <table class="table">

                        <tbody>

                        {{--审核状态，其中0为审核成功，1为审核失败，2为审核中 3已发布 4撤回审核--}}

                        @if(isset($publish->status) AND $publish->status==3)

                            <tr>
                                <td>状态</td>
                                <td>
                                    已发布
                                </td>
                            </tr>

                            <tr>
                                <td class="col-md-4">版本号</td>
                                <td class="col-md-8"><span class="label label-default">{{$publish->saas->version}}</span></td>
                            </tr>

                            @if($publish->audit_time)
                            <tr>
                                <td class="col-md-4">提交审核时间</td>
                                <td class="col-md-8">{{$publish->audit_time}}</td>
                            </tr>
                            @endif

                            @if($publish->release_time)
                            <tr>
                                <td class="col-md-4">发布时间</td>
                                <td class="col-md-8">{{$publish->release_time}}</td>
                            </tr>
                            @endif

                            @if(isset($publish->theme) AND $publish->theme)

                                <?php  $publish_theme=json_decode($publish->theme,true); ?>
                                <tr>
                                    <td class="col-md-4">主题配色</td>
                                    <td class="col-md-8">
                                        <span>{{isset($publish_theme['title'])?$publish_theme['title']:''}}</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="col-md-4"></td>
                                    <td class="col-md-8">
                                        @if(isset($publish_theme['img']))
                                            <img width="80"  height="120" src="{{$publish_theme['img']}}" alt="">
                                        @endif
                                    </td>
                                </tr>

                            @endif

                            <tr>
                                <td class="col-md-4">小程序码</td>
                                <td class="col-md-8">
                                    <img width="150" height="150"
                                         src="{{route('admin.account.coterie.mini.version.getUnlimit',['page'=>$version->mini_address,'width'=>800])}}" alt="">
                                </td>
                            </tr>

                        @endif


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif


</div>

<script src="https://cdn.bootcss.com/Sortable/1.6.0/Sortable.min.js"></script>

@include('account-backend::mini.version.script')

<script>

    @if($status_message)  $('#experience_code').show();  @endif
    {{--window.mini_address="{{$version->mini_address}}";--}}
    {{--window.mini_title="{{$version->mini_title}}";--}}
    {{--window.mini_tag="{{$version->mini_tag}}";--}}

     window.category_key=0;

    {{--@if(isset($audit->status) AND $audit->status==1)--}}
    {{--window.setTimeout(go, 100);--}}

    {{--function go() {--}}
        {{--swal({--}}
                {{--title: "存在上线发布失败记录",--}}
                {{--text: "",--}}
                {{--type: "warning",--}}
                {{--showCancelButton: true,--}}
                {{--confirmButtonColor: "#DD6B55",--}}
                {{--confirmButtonText: "查看",--}}
                {{--cancelButtonText: '重新提交审核',--}}
                {{--closeOnConfirm: false--}}
            {{--},--}}
            {{--function () {--}}
                {{--location.href = ''--}}

            {{--});--}}
    {{--}--}}

    {{--@endif--}}

    //提交小程序代码
    var CommitMiniCode_url = "{{route('admin.account.coterie.mini.version.codeCommit',['appid'=>$appid])}}";
    var CommitMiniCode_data = {
        _token: _token,
        'ext_json': {
            'extAppid':"{{$appid}}",
            'ext':{
                'wechatappid':"{{$appid}}",
                'appid':"{{$uuid}}"
            }
        },
        'template_id': "{{$version->template_id}}",
        'user_version': "{{$version->version}}",
        'user_desc': "{{$version->description}}",
    };

    @if(isset($publish->status))
    @if ($publish->status==2 ||$publish->status==0)
    $('.CommitMiniCodeUpload_success').show()
    $('.CommitMiniCodeExamine').show()
    $('.CommitMiniCodeExamine_a').hide();
    $('.CommitMiniCodeExamine_success').show()
    @endif
    @endif

    // CommitMiniCode(CommitMiniCode_url, CommitMiniCode_data)


    function CommitMiniCode(url, data) {

        $.post(url, data, function (result) {

            if (result.status) {

                $('.CommitMiniCodeUpload_success').show()
                $('.CommitMiniCodeExamine').show()
            } else {
                swal({
                    title: "上传小程序代码失败",
                    text: result.message,
                    type: "error"
                });
                $('.CommitMiniCodeUpload_error').show()
            }
        });
    }

    //提交审核

    var CommitMiniCodeExamine_url = "{{route('admin.account.coterie.mini.version.submitAudit')}}"

    var category_txt = $('.category_txt').text();

    var CommitMiniCodeExamine_data_ext_json=CommitMiniCode_data.ext_json;

    //page
    var CommitMiniCodeExamine_data = {
        _token: _token,
        'item_list': {
            "address": "{{$version->mini_address}}",
            "tag": "{{$version->mini_tag}}",
            "title": "{{$version->mini_title}}",
        },
        'log': {
            'appid': "{{$appid}}",
            'template': {
                'template_id': "{{$version->mini_address}}",
                'user_version': "{{$version->version}}",
                'user_desc': "{{$version->description}}",
                'create_time': "{{$version->created_at}}",
                'category': category_txt,
                "address": "{{$version->mini_address}}",
            },
            'theme':'',
            'saas_version_publish_id':'{{$version->id}}',
            'ext_json':CommitMiniCodeExamine_data_ext_json
        }
    }


    function CommitMiniCodeExamine(url, data) {

        var key=window.category_key;
        var cate_data=category[key];
        CommitMiniCodeExamine_data['item_list']['first_class']=cate_data.first_class;
        CommitMiniCodeExamine_data['item_list']['first_id']=cate_data.first_id;
        CommitMiniCodeExamine_data['item_list']['second_class']=cate_data.second_class;
        CommitMiniCodeExamine_data['item_list']['second_id']=cate_data.second_id;

        if(CommitMiniCodeExamine_data['log']['theme']==''){
            swal({
                title: "请选择主题配色",
                text: "",
                type: "error"
            });
            return ;
        };

        swal({
            title: "确定要提交审核么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        },  function () {

            $.post(url, data, function (result) {

                if (result.status) {

                    swal({
                        title: "提交审核成功",
                        text: "",
                        type: "success",
                        confirmButtonText: "确定"
                    }, function () {
                        location.href = "{{route('admin.account.coterie.mini.version')}}";

                    });

                } else {
                    swal({
                        title: "提交审核失败",
                        text: result.message,
                        type: "error"
                    });

                }
            });

        });

    }


    function withdrawAudit() {

        swal({
            title: "确定要撤回审核么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {

            var url = "{{route('admin.account.coterie.mini.version.withdrawAudit')}}";

            var data = {
                _token: _token,
            }

            $.post(url, data, function (result) {

                if (result.status) {

                    swal({
                        title: "撤回审核成功",
                        text: "",
                        type: "success",
                        confirmButtonText: "确定"
                    }, function () {
                       location.href = "{{route('admin.account.coterie.mini.version')}}";

                    });

                } else {
                    swal({
                        title: "撤回审核失败",
                        text: result.message,
                        type: "error"
                    });

                }
            });

        });

    }


    //上线发布
    function release() {
        swal({
            title: "确定要上线发布么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {
            var url = "{{route('admin.account.coterie.mini.version.release')}}";

            var data = {
                _token: _token,
            }

            $.post(url, data, function (result) {

                if (result.status) {

                    swal({
                        title: "上线发布成功",
                        text: "",
                        type: "success",
                        confirmButtonText: "确定"
                    }, function () {
                        location.href = "{{route('admin.account.coterie.mini.version')}}";

                    });

                } else {
                    swal({
                        title: "上线发布失败",
                        text: result.message,
                        type: "error"
                    });

                }
            });

        });
    }


</script>


<script>
    function Reexamination(id) {

        swal({
            title: "确定要取消发布么?",
            text: "",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "确认",
            cancelButtonText: '取消',
            closeOnConfirm: false
        }, function () {

            var post = "{{route('admin.account.coterie.mini.version.Reexamination')}}"+"?id="+id;

            var data = {
                _token: _token,
            }
            $.post(post, data, function (result) {

                if (result.status) {

                    swal({
                        title: "取消发布成功",
                        text: "",
                        type: "success",
                        confirmButtonText: "确定"
                    }, function () {
                        location.href = "{{route('admin.account.coterie.mini.version')}}";

                    });

                } else {
                    swal({
                        title: "取消发布失败",
                        text: result.message,
                        type: "error"
                    });

                }
            });
        });

    }
</script>

<script>
    function item_list_edit(id,title) {

        if(id=='#mini_address'){
            var txt=window.mini_address;
        }
        if(id=='#mini_title'){
            var txt=window. mini_title;
        }
        if(id=='#mini_tag'){
            var txt=window.mini_tag;
        }
        swal({
                title:title,
                text: "",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "确认",
                cancelButtonText: '取消',
                closeOnConfirm: false,
                type: "input",
                animation: "slide-from-top",
                inputPlaceholder: "",
                inputValue:txt,
            },
            function (inputValue) {
                if (!inputValue) {
                    swal.showInputError("不能为空");
                    return false
                }
                $(id).text(inputValue);
                if(id=='#mini_address'){
                    window.mini_address=inputValue;
                }
                if(id=='#mini_title'){
                    window. mini_title=inputValue;
                }
                if(id=='#mini_tag'){
                    window.mini_tag=inputValue;
                }
                swal({
                    title: "修改成功",
                    text: "",
                    type: "success",
                    confirmButtonText: "确定"
                });
            });
    }
</script>


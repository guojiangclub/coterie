<div class="tabs-container">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body form-horizontal">
                <input type="hidden" name="id" value="{{$detail->id}}">

                <div class="form-group">
                    <label class="control-label col-md-2">发布用户：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">
                            {!! $detail->user->nick_name !!}
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">所属圈子：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{$detail->coterie->name}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">发布时间：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{$detail->created_at}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">类型：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{!! $detail->content_type_text !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">标签：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">
                            @if($detail->TagsListInfo)
                                @foreach($detail->TagsListInfo as $tag)
                                    <span>{!! $tag !!}</span>
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">动态数据：</label>
                    <div class="col-md-9">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <tbody>
                                <tr>
                                    <td>评论数</td>
                                    <td>点赞数</td>
                                </tr>
                                <tr>
                                    <td>{{$detail->comment_count}}</td>
                                    <td>{{$detail->praise_count}}</td>
                                </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">推荐状态：</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {!! $detail->recommended_at?'已推荐【'.$detail->recommended_at.'】':'未推荐' !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">置顶状态：</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {!! $detail->stick_at?'已置顶【'.$detail->stick_at.'】':'未置顶' !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">描述：</label>
                    <div class="col-md-9">
                        <p class="form-control-static"> {!! $detail->description !!}</p>
                    </div>
                </div>

                @if($detail->link)
                    <div class="form-group">
                        <label class="control-label col-md-2">链接：</label>
                        <div class="col-md-9">

                            <p class="form-control-static">
                                <img src="{!! $detail->LinkInfo['img'] !!}" width="50"></p>
                            <p class="form-control-static">
                                <a href="{!! $detail->LinkInfo['link'] !!}" target="_blank">
                                    {!! $detail->LinkInfo['title'] !!}
                                </a>
                            </p>
                        </div>
                    </div>
                @endif

                @if($detail->img_list)
                    <div class="form-group">
                        <label class="control-label col-md-2">图片：</label>
                        <div class="col-md-9">
                            <p class="form-control-static">
                                @if($detail->ImgListInfo)
                                    @foreach($detail->ImgListInfo as $img)
                                        <img src="{!! $img !!}" width="120">
                                    @endforeach
                                @endif
                            </p>
                        </div>
                    </div>
                @endif

                <div class="hr-line-dashed"></div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-8 controls">
                        <a href="javascript:history.go(-1)"
                           class="btn btn-danger">返回</a>

                        @if($detail->status==1)
                            @if($detail->recommended_at)
                                <a data-href="{{route('admin.coterie.content.switchRecommend')}}" data-action="cancel"
                                   data-id="{{$detail->id}}"
                                   class="btn btn-danger re-action">取消推荐</a>
                            @else
                                <a data-href="{{route('admin.coterie.content.switchRecommend')}}"
                                   data-action="recommend"
                                   data-id="{{$detail->id}}"
                                   class="btn btn-primary re-action">推荐</a>
                            @endif

                            @if($detail->stick_at)
                                <a data-href="{{route('admin.coterie.content.switchStick')}}" data-action="cancel"
                                   data-id="{{$detail->id}}"
                                   class="btn btn-danger stick-action">取消置顶</a>
                            @else
                                <a data-href="{{route('admin.coterie.content.switchStick')}}" data-action="recommend"
                                   data-id="{{$detail->id}}"
                                   class="btn btn-primary stick-action">置顶</a>
                            @endif
                        @endif

                        @if($detail->status==0)
                            <a data-href="{{route('admin.coterie.content.switchStick')}}"
                               data-id="{{$detail->id}}"
                               class="btn btn-primary audited-action">审核通过</a>
                        @endif

                        @if($detail->deleted_at)
                            <a data-href="{{route('admin.coterie.content.restore')}}" data-action="recommend"
                               data-id="{{$detail->id}}"
                               class="btn btn-primary restore-action">恢复</a>
                        @else
                            <a href="javascript:;" class="btn btn-danger delete-content" data-id="{{$detail->id}}"
                               data-href="{{route('admin.coterie.content.delete')}}">
                                删除</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('account-backend::content.includes.scripts')
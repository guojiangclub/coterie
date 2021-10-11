<div class="tabs-container">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body form-horizontal">
                <input type="hidden" name="id" value="{{$detail->id}}">

                <div class="form-group">
                    <label class="control-label col-md-2">状态：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">
                            {{$detail->content_id?'已回答':'未回答'}}
                        </p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">提问用户：</label>
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
                    <label class="control-label col-md-2">提问时间：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{{$detail->created_at}}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">被邀请回答用户：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">{!! $detail->atUser->nick_name !!}</p>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">提问内容：</label>
                    <div class="col-md-9">
                        <p class="form-control-static">
                            {!! $detail->content !!}
                        </p>
                        <p class="form-control-static">
                            @if($detail->ImgListInfo)
                                @foreach($detail->ImgListInfo as $img)
                                    <img src="{!! $img !!}" width="120">
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>

                @if($detail->content_id)
                    <div class="form-group">
                        <label class="control-label col-md-2">回答时间：</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{!! $detail->content->created_at !!}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">回答内容：</label>
                        <div class="col-md-9">
                            <p class="form-control-static">{!! $detail->content->description !!}</p>

                            <p class="form-control-static">
                                @if($detail->content->ImgListInfo)
                                    @foreach($detail->content->ImgListInfo as $img)
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


                        @if($detail->deleted_at)
                            <a data-href="{{route('admin.coterie.question.restore')}}" data-action="recommend"
                               data-id="{{$detail->id}}"
                               class="btn btn-primary restore-action">恢复</a>
                        @else
                            <a href="javascript:;" class="btn btn-danger delete-content" data-id="{{$detail->id}}"
                               data-href="{{route('admin.coterie.question.delete')}}">
                                删除</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('account-backend::question.includes.scripts')
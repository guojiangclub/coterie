<div class="tabs-container">
    <ul class="nav nav-tabs">
        <li class="{{ Active::query('status','audited') }}"><a no-pjax
                                                        href="{{route('admin.coterie.content.list',['status'=>'audited'])}}">已审核</a>
        </li>
        <li class="{{ Active::query('status','unaudited') }}"><a no-pjax
                                                                 href="{{route('admin.coterie.content.list',['status'=>'unaudited'])}}">待审核</a>
        </li>
        <li class="{{ Active::query('status','forbidden') }}"><a no-pjax
                                                                 href="{{route('admin.coterie.content.list',['status'=>'forbidden'])}}">已删除</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open( [ 'route' => ['admin.coterie.content.list'], 'method' => 'get', 'id' => 'commentsurch-form','class'=>'form-horizontal'] ) !!}

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
                    @include('account-backend::content.includes.list')
                </div><!-- /.box-body -->

            </div>
        </div>
    </div>
</div>

@include('account-backend::content.includes.scripts')
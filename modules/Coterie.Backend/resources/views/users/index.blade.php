<style type="text/css">
    .avatar {
        width: 50px;
        height: 50px;
        border-radius: 100%
    }
</style>
<div class="tabs-container">
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">
                {!! Form::open( [ 'route' => ['admin.coterie.users.list'], 'method' => 'get', 'id' => 'commentsurch-form','class'=>'form-horizontal'] ) !!}
                <div class="row">
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" name="mobile" value="{{request('value')}}" placeholder="请输入用户手机"
                                   class=" form-control"> <span
                                    class="input-group-btn">
                                        <button type="submit" class="btn btn-primary">查找</button></span></div>
                    </div>


                </div>
                {!! Form::close() !!}

                <div class="table-responsive">
                    @include('account-backend::users.includes.list')
                </div><!-- /.box-body -->

            </div>
        </div>
    </div>
</div>
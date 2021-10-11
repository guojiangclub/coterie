<div class="ibox float-e-margins">
    <div class="ibox-content" style="display: block;">

        <form method="post" action="{{route('admin.coterie.savePay')}}" class="form-horizontal"
              id="setting_site_form">
            {{csrf_field()}}
            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">小程序商户号MCHID</label>
        <span class="help-block m-b-none text-gray">* 申请小程序支付时，若选择使用已有的公众号支付，请填写公众号支付商户号；
否则，请在新申请的微信支付商户平台的通知邮件中获取商户号，请确保邮件中的小程序 AppID 与本次填写的小程序 AppID 一致</span>
                </div>

                <div class="col-sm-10"><input type="text" name="wechat_payment_mcn_id"
                                              value="{{settings('wechat_payment_mcn_id')}}"
                                              class="form-control"></div>
            </div>

            <div class="form-group">
                <div class="col-sm-2">
                    <label class="control-label">小程序支付签名秘钥</label>
                    <span class="help-block m-b-none text-gray">* 微信支付商户平台 - 账户中心 - 账户设置 - API 安全 - API 密钥 - 设置密钥</span>
                </div>

                <div class="col-sm-10"><input type="text" name="wechat_payment_key"
                                              value="{{settings('wechat_payment_key')}}"
                                              class="form-control"></div>
            </div>



            <div class="hr-line-dashed"></div>
            <div class="form-group">
                <div class="col-md-offset-2 col-md-8 controls">
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </div>

            {!! Form::close() !!}
                    <!-- /.tab-content -->
    </div>
</div>

<script>
    $(function () {
        $('#setting_site_form').ajaxForm({
            success: function () {
                swal("保存成功!", "", "success")
            }
        });
    });
</script>

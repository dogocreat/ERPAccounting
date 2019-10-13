<link rel="stylesheet" href="../www/third_party/layui/css/layui.css" media="all">
<script type="text/javascript" src="../www/third_party/jquery/jquery-3.4.1.min.js"></script>
<script src="../www/third_party/layui/layui.js" charset="utf-8"></script>

<div class="layui-card-body" style="margin:10px;">
    <form class="layui-form" action="" lay-filter="component-form-element">
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">名稱：</label>
                <div class="layui-input-block">
                    <input type="text" id="name" placeholder="" autocomplete="off" value="<?=isset($edit_data['name']) ? $edit_data['name'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">統編：</label>
                <div class="layui-input-block">
                    <input type="text" id="company_no" placeholder="" autocomplete="off" value="<?=isset($edit_data['company_no']) ? $edit_data['company_no'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">公司地址：</label>
                <div class="layui-input-block">
                    <input type="text" id="company_address" placeholder="" autocomplete="off" value="<?=isset($edit_data['company_address']) ? $edit_data['company_address'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">公司信箱：</label>
                <div class="layui-input-block">
                    <input type="text" id="company_email" placeholder="" autocomplete="off" value="<?=isset($edit_data['company_email']) ? $edit_data['company_email'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">公司電話：</label>
                <div class="layui-input-block">
                    <input type="text" id="company_phone" placeholder="" autocomplete="off" value="<?=isset($edit_data['company_phone']) ? $edit_data['company_phone'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">電話：</label>
                <div class="layui-input-block">
                    <input type="text" id="phone" placeholder="" autocomplete="off" value="<?=isset($edit_data['phone']) ? $edit_data['phone'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
    </form>
</div>
<script>
layui.use(['layer','element',"laydate"], function() {
    var layer = layui.layer;
    var element = layui.element;
    var laydate = layui.laydate;
    var form = layui.form;
});
</script>

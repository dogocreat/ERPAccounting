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
                <label class="layui-form-label">單位：</label>
                <div class="layui-input-block">
                    <input type="text" id="unit" placeholder="" autocomplete="off" value="<?=isset($edit_data['unit']) ? $edit_data['unit'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">當前庫存：</label>
                <div class="layui-input-block">
                    <input type="text" id="now_stock" placeholder="" autocomplete="off" value="<?=isset($edit_data['now_stock']) ? $edit_data['now_stock'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">安全庫存：</label>
                <div class="layui-input-block">
                    <input type="text" id="safe_stock" placeholder="" autocomplete="off" value="<?=isset($edit_data['safe_stock']) ? $edit_data['safe_stock'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">平均成本：</label>
                <div class="layui-input-block">
                    <input type="text" id="avg_price" placeholder="" autocomplete="off" value="<?=isset($edit_data['avg_price']) ? $edit_data['avg_price'] : '';?>"
                        class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">售價：</label>
                <div class="layui-input-block">
                    <input type="text" id="sale_price" placeholder="" autocomplete="off" value="<?=isset($edit_data['sale_price']) ? $edit_data['sale_price'] : '';?>"
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

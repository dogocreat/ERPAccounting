<link rel="stylesheet" href="../www/third_party/layui/css/layui.css" media="all">
<link rel="stylesheet" href="../www/third_party/DataTables/DataTables-1.10.20/css/bootstrap.css" />
<link rel="stylesheet" href="../www/css/common.css" media="all">
<script type="text/javascript" src="../www/third_party/jquery/jquery-3.4.1.min.js"></script>
<script src="../www/third_party/layui/layui.js" charset="utf-8"></script>
<style>
body {
    background-color: #e8e8e8;
}

.layui-form-label {
    width: 100px !important;
    font-size: 14px !important;
}
</style>
<div class="layui-card" style="margin:10px;">
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg3">
                <label class="layui-form-label">進貨日期：</label>
                <div class="layui-input-block">
                    <input type="text" id="instock_date" placeholder="" autocomplete="off"
                        value="<?=isset($edit_data['instock_date']) ? $edit_data['instock_date'] : '';?>"
                        class="layui-input">
                </div>
            </div>
            <div class="layui-col-lg3">
                <label class="layui-form-label">廠商：</label>
                <div class="layui-input-block">
                    <select name="vendor_id" id="vendor_id">
                        <option value="">請選擇</option>
                        <?php foreach ($vendor_items as $key => $value) { ?>
                        <option value="<?=$value->id?>"
                            <?php echo isset($edit_data['vendor_id']) ? $edit_data['vendor_id'] == $value->id ? 'selected' : '' : '';?>><?=$value->name?>
                        </option>
                        <?php }?>
                    </select>
                </div>
            </div>
            <div class="layui-col-lg3">
                <label class="layui-form-label">營業稅：</label>
                <div class="layui-input-block">
                    <input type="text" id="tax_price" placeholder="" autocomplete="off" onchange="instockTotalPrice()" ;
                        value="<?=isset($edit_data['tax_price']) ? $edit_data['tax_price'] : 0;?>" class="layui-input">
                </div>
            </div>
            <div class="layui-col-lg3">
                <label class="layui-form-label">折讓：</label>
                <div class="layui-input-block">
                    <input type="text" id="back_price" placeholder="" autocomplete="off" onchange="instockTotalPrice()"
                        value="<?=isset($edit_data['back_price']) ? $edit_data['back_price'] : 0;?>"
                        class="layui-input">
                </div>
            </div>
        </div>
    </div>
    <div class="layui-card-body">
        <div class="layui-row layui-col-space10 layui-form-item">
            <div class="layui-col-lg6">
                <label class="layui-form-label">進貨金額：</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid warning-red" id="instock_price">
                        <?=isset($edit_data['instock_price']) ? $edit_data['instock_price'] : 0;?></div>
                </div>
            </div>
            <div class="layui-col-lg6">
                <label class="layui-form-label">應付金額：</label>
                <div class="layui-input-block">
                    <div class="layui-form-mid warning-red" id="payable_price">
                        <?=isset($edit_data['payable_price']) ? $edit_data['payable_price'] : 0;?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="layui-card" style="margin:10px;">
    <div class="layui-card-body" style="margin:10px;">
        <table class="table table-bordered" style="width:100%;" id="instock_detail">
            <thead>
                <tr>
                    <th>品名</th>
                    <th>數量</th>
                    <th>單位</th>
                    <th>單價</th>
                    <th>金額</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php if(isset($edit_data_detail) && !empty($edit_data_detail)){ ?>
                <?php foreach ($edit_data_detail as $dkey => $dvalue) { ?>
                <tr>
                    <td>
                        <select name="stock_id" onchange="selectStock(this);">
                            <option value="">請選擇</option>
                            <?php foreach ($stock_items as $key => $value) { ?>
                            <option value="<?=$value->id?>" stock-unit="<?=$value->unit?>"
                                stock-price="<?=$value->sale_price?>"
                                <?php echo $dvalue['stock_id'] == $value->id ? 'selected' : '';?>><?=$value->name?>
                            </option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="qty" onchange="calTotalPrice(this)" value="<?=$dvalue['qty'];?>">
                    </td>
                    <td>
                        <div name="unit"><?=$dvalue['unit'];?></div>
                    </td>
                    <td>
                        <input type="text" name="stock_price" onchange="calTotalPrice(this)" value="<?=$dvalue['price'];?>">
                    </td>
                    <td>
                        <div name="total_stock_price"><?=round($dvalue['price'] * $dvalue['qty'], 2);?></div>
                    </td>
                    <td>
                        <span style="margin-right:10px;" onclick="addDom(this);"><i
                                style="font-size: 30px; color: green;"
                                class="layui-icon layui-icon-add-circle-fine"></i></span>
                        <span onclick="delDom(this);"><i style="font-size: 30px; color: red;"
                                class="layui-icon layui-icon-delete"></i></span>
                    </td>
                </tr>
                <?php }?>
                <?php }else{ ?>
                <tr>
                    <td>
                        <select name="stock_id" onchange="selectStock(this);">
                            <option value="">請選擇</option>
                            <?php foreach ($stock_items as $key => $value) { ?>
                            <option value="<?=$value->id?>" stock-unit="<?=$value->unit?>"
                                stock-price="<?=$value->sale_price?>"><?=$value->name?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        <input type="text" name="qty" onchange="calTotalPrice(this)">
                    </td>
                    <td>
                        <div name="unit"></div>
                    </td>
                    <td>
                        <input type="text" name="stock_price" onchange="calTotalPrice(this)">
                    </td>
                    <td>
                        <div name="total_stock_price"></div>
                    </td>
                    <td>
                        <span style="margin-right:10px;" onclick="addDom(this);"><i
                                style="font-size: 30px; color: green;"
                                class="layui-icon layui-icon-add-circle-fine"></i></span>
                        <span onclick="delDom(this);"><i style="font-size: 30px; color: red;"
                                class="layui-icon layui-icon-delete"></i></span>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
<script>
layui.use(['layer', 'element', "laydate"], function() {
    var layer = layui.layer;
    var element = layui.element;
    var laydate = layui.laydate;
    laydate.render({
        elem: '#instock_date', //指定元素
        type: 'date'
    });
});

var addDom = (obj) => {
    var row = $(obj).parent().parent();
    var clone = row.clone();
    clone.find('div[name=unit]').text('');
    clone.find('input[name=stock_price]').val('');
    clone.find('div[name=total_stock_price]').text('');
    clone.find('input[name=qty]').val('');
    row.after(clone);
}

var delDom = (obj) => {
    var tableParents = $(obj).parent().parents();
    var row = $(obj).parent().parent();
    var rows = tableParents.find("tr");
    if (rows.length > 2) {
        $(obj).parent().parent().remove();
    }
    instockTotalPrice();
}

var selectStock = (obj) => {
    var row = $(obj).parent().parent();
    // if (obj.value == '') {
        row.find('div[name=unit]').text('');
        row.find('input[name=stock_price]').val('');
        row.find('div[name=total_stock_price]').text('');
        row.find('input[name=qty]').val('');
    // }
    row.find('div[name=unit]').text($(obj).find(":selected").attr('stock-unit'));
    instockTotalPrice();
}

var calTotalPrice = (obj) => {
    var row = $(obj).parent().parent();
    var qty = $.trim(row.find('input[name=qty]').val());
    var stock_price = $.trim(row.find('input[name=stock_price]').val());
    var total_stock_price = parseFloat(stock_price) * parseFloat(qty);
    if(qty != '' && stock_price != ''){
        row.find('div[name=total_stock_price]').text(total_stock_price.toFixed(2));
    }
    instockTotalPrice();
}

var instockTotalPrice = () => {
    var trs = $("#instock_detail").find("tbody").children();
    var totalPrice = 0;
    $.each(trs, function(index, item) {
        var total_stock_price = $.trim($(item).find('div[name=total_stock_price]').text());
        if (total_stock_price != '') {
            totalPrice = parseFloat(totalPrice) + parseFloat(total_stock_price);
        }
    });
    $('#instock_price').text(totalPrice.toFixed(2));
    var instock_price = parseFloat($.trim($('#instock_price').text()));
    var tax_price = parseFloat($.trim($('#tax_price').val()));
    var back_price = parseFloat($.trim($('#back_price').val()));
    var payable_price = instock_price - tax_price - back_price;
    $('#payable_price').text(parseFloat(payable_price).toFixed(2));

}
</script>
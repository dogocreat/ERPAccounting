<!-- search bar -->
<div style="margin:10px;">
    銷貨單號：
    <div class="layui-inline">
        <input class="layui-input" name="outstock_id" id="outstock_id" autocomplete="off">
    </div>
    客戶名稱：
    <div class="layui-inline">
        <input class="layui-input" name="customer_name" id="customer_name" autocomplete="off">
    </div>
    銷貨日期：
    <div class="layui-inline">
        <input class="layui-input" name="start_date" id="start_date" autocomplete="off">
    </div>
    至
    <div class="layui-inline">
        <input class="layui-input" name="end_date" id="end_date" autocomplete="off">
    </div>
    <button class="layui-btn layui-btn-sm" type="button" data-type="reset" onclick="resetSearchData();"><i
            class="layui-icon layui-icon-refresh"></i> 清空</button>
    <button class="layui-btn layui-btn-sm" type="button" data-type="reload" onclick="searchData();"><i
            class="layui-icon layui-icon-search"></i> 查詢</button>
    <button class="layui-btn layui-btn-sm" type="button" onclick="openDialog('');"><i
            class="layui-icon layui-icon-add-1"></i> 新增</button>
</div>

<!-- DataTable -->
<div style="margin-top:40px;">
    <table class="table table-striped table-bordered" id="outstockDataTable" style="width:100%;"></table>
</div>

<script>
var dataTable = null;

var searchData = () => {
    dataTable.ajax.reload(null, false);
}

var resetSearchData = () => {
    $('#outstock_id').val('');
    $('#customer_name').val('');
    $('#start_date').val('');
    $('#end_date').val('');
}

$(document).ready(function() {
    dataTable = $('#outstockDataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "order": [[ 0, "desc" ]],
        "ajax": {
            "type": "POST",
            "url": "<?=$dataTableListUrl;?>",
            "data": function(d) {
                d.outstock_id = $.trim($('#outstock_id').val());
                d.customer_name = $.trim($('#customer_name').val());
                d.start_date = $.trim($('#start_date').val());
                d.end_date = $.trim($('#end_date').val());
            },
            "dataFilter": function(data) {
                var json = jQuery.parseJSON(data);
                json.recordsTotal = json.count;
                json.recordsFiltered = json.count;
                json.data = json.data;
                return JSON.stringify(json); // return JSON string
            },
            "error": function(xhr, error, thrown) {
                console.error(error);
            }
        },
        "columns": [{
                title: "銷貨日期",
                data: 'outstock_date'
            },
            {
                title: "銷貨單號",
                data: 'outstock_id',
            },
            {
                title: "客戶名稱",
                data: 'customer_name',
                "render": function(data, type, row, meta) {
                    return data;
                }
            },
            {
                title: "銷貨金額",
                data: 'outstock_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "營業稅",
                data: 'tax_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "折讓",
                data: 'back_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "應收金額",
                data: 'receivable_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "已收金額",
                data: 'receivabled_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "未收金額",
                data: 'unreceivable_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
        ],
        "columnDefs": [{
            "targets": 9,
            "title": "操作",
            "orderable": false,
            "data": null,
            "width": "20%",
            "render": function(data, type, row, meta) {
                var html = '';
                html +=
                    '<button type="button" class="layui-btn layui-btn-sm" onclick="doReceivabled(' +
                    row.outstock_id + ',' + row.receivabled_price +
                    ')"><i class="layui-icon layui-icon-dollar"></i> 收款</button>';
                html +=
                    '<button type="button" class="layui-btn layui-btn-sm layui-btn-normal" onclick="openDialog(' +
                    row.outstock_id +
                    ')"><i class="layui-icon layui-icon-edit"></i> 修改</button>';
                html +=
                    '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="delOne(' +
                    row.outstock_id +
                    ')"><i class="layui-icon layui-icon-delete"></i> 刪除</button>';
                return html;
            }
        }],
        "language": {
            "emptyTable": "無資料...",
            "processing": "處理中...",
            "loadingRecords": "載入中...",
            "lengthMenu": "顯示 _MENU_ 項結果",
            "zeroRecords": "沒有符合的結果",
            "info": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "infoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "infoFiltered": "(從 _MAX_ 項結果中過濾)",
            "infoPostFix": "",
            "search": "搜尋:",
            "paginate": {
                "first": "第一頁",
                "previous": "上一頁",
                "next": "下一頁",
                "last": "最後一頁"
            },
            "aria": {
                "sortAscending": ": 升冪排列",
                "sortDescending": ": 降冪排列"
            }
        }
    });
});

var openDialog = (id) => {
    layer.open({
        type: 2,
        title: id !== '' ? '修改銷貨單-'+id : '新增銷貨單',
        area: ["80%", "80%"],
        btn: ["保存", "取消"],
        content: ['<?=$openDialogUrl;?>?id=' + id],
        yes: function(index, layero) {
            var outstock_date = $.trim(layer.getChildFrame('body', index).find('#outstock_date').val());
            var customer_id = $.trim(layer.getChildFrame('body', index).find('#customer_id').val());
            var tax_price = $.trim(layer.getChildFrame('body', index).find('#tax_price').val());
            var back_price = $.trim(layer.getChildFrame('body', index).find('#back_price').val());
            var detail = layer.getChildFrame('body', index).find('#outstock_detail').find("tbody")
                .children();
            var detailArray = new Array();
            $.each(detail, function(index, item) {
                var stock_id = $(item).find("select[name=stock_id]").val();
                var qty = $(item).find("input[name=qty]").val();
                var price = $(item).find("input[name=stock_price]").val();
                var unit = $(item).find("div[name=unit]").text();

                if (stock_id != '' && qty != '' && price != '') {
                    detailArray.push({
                        stock_id: stock_id,
                        qty: qty,
                        price: price,
                        unit: unit,
                    });
                }
            });
            if (outstock_date === '') {
                layer.alert("請選擇銷貨日期。", {
                    icon: 2
                });
                return false;
            }
            if (customer_id === '') {
                layer.alert("請選擇銷貨廠商。", {
                    icon: 2
                });
                return false;
            }
            $.post("<?=$saveDialogUrl;?>", {
                    "id": id,
                    "outstock_date": outstock_date,
                    "customer_id": customer_id,
                    "tax_price": tax_price,
                    "back_price": back_price,
                    "details": detailArray,
                },
                function(data) {
                    if (data.code) {
                        layer.alert("操作成功。", {
                            icon: 1
                        }, function(indexalert) {
                            searchData();
                            layer.close(indexalert);
                            layer.close(index);
                        });
                    } else {
                        layer.alert("操作失敗。", {
                            icon: 2
                        });
                    }
                }, "json");
        }
    });
}

var delOne = (id) => {
    layer.confirm('是否刪除編號<span class="warning-red">' + id + "</span>?", {
        icon: 3,
        title: '刪除銷貨單'
    }, function(index) {
        $.post("<?=$delUrl;?>", {
                "id": id,
            },
            function(data) {
                if (data.code) {
                    layer.alert("操作成功。", {
                        icon: 1
                    }, function(indexalert) {
                        searchData();
                        layer.close(indexalert);
                        layer.close(index);
                    });
                } else {
                    layer.alert("操作失敗。", {
                        icon: 2
                    });
                }
            }, "json");
    });
}

var doReceivabled = (id, receivabled_price) => {
    layer.prompt({
        title: '輸入銷貨單號：<span class="warning-red">' + id + '</span> 已付金額',
        formType: 3,
        value: receivabled_price,
    }, function(pass, index) {
        $.post("<?=$doReceivabledUrl;?>", {
                "id": id,
                "receivabled_price": pass,
            },
            function(data) {
                if (data.code) {
                    layer.alert("操作成功。", {
                        icon: 1
                    }, function(indexalert) {
                        searchData();
                        layer.close(indexalert);
                        layer.close(index);
                    });
                } else {
                    layer.alert("操作失敗。", {
                        icon: 2
                    });
                }
            }, "json");
    });
}
</script>
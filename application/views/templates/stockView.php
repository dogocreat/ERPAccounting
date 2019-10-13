<!-- search bar -->
<div style="margin:10px;">
    編號：
    <div class="layui-inline">
        <input class="layui-input" name="id" id="id" autocomplete="off">
    </div>
    名稱：
    <div class="layui-inline">
        <input class="layui-input" name="name" id="name" autocomplete="off">
    </div>
    建立日期：
    <div class="layui-inline">
        <input class="layui-input" name="start_time" id="start_time" autocomplete="off">
    </div>
    至
    <div class="layui-inline">
        <input class="layui-input" name="end_time" id="end_time" autocomplete="off">
    </div>
</div>
<div style="margin:10px;">
    當前庫存大於
    <div class="layui-inline">
        <input class="layui-input" name="now_stock" id="now_stock" autocomplete="off">
    </div>
    安全庫存大於
    <div class="layui-inline">
        <input class="layui-input" name="safe_stock" id="safe_stock" autocomplete="off">
    </div>
    <div class="layui-inline">
        <form class="layui-form" lay-filter="test1">
            <input type="checkbox" name="warning_stock" id="warning_stock" title="庫存不足">
        </form>
    </div>
</div>
<div style="margin:10px;">
    平均成本大於
    <div class="layui-inline">
        <input class="layui-input" name="avg_price" id="avg_price" autocomplete="off">
    </div>
    銷售價格大於
    <div class="layui-inline">
        <input class="layui-input" name="sale_price" id="sale_price" autocomplete="off">
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
    <table class="table table-striped table-bordered" id="customerDataTable" style="width:100%;"></table>
</div>

<script>
var dataTable = null;

var searchData = () => {
    dataTable.ajax.reload(null, false);
}

var resetSearchData = () => {
    $('#id').val('');
    $('#name').val('');
    $('#now_stock').val('');
    $('#safe_stock').val('');
    $('#avg_price').val('');
    $('#sale_price').val('');
    $('#start_time').val('');
    $('#end_time').val('');
}

$(document).ready(function() {
    dataTable = $('#customerDataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ajax": {
            "type": "POST",
            "url": "<?=$dataTableListUrl;?>",
            "data": function(d) {
                d.id = $.trim($('#id').val());
                d.name = $.trim($('#name').val());
                d.now_stock = $.trim($('#now_stock').val());
                d.safe_stock = $.trim($('#safe_stock').val());
                d.avg_price = $.trim($('#avg_price').val());
                d.sale_price = $.trim($('#sale_price').val());
                d.warning_stock = $('#warning_stock').is(":checked");
                d.start_time = $.trim($('#start_time').val());
                d.end_time = $.trim($('#end_time').val());
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
                title: "編號",
                data: 'id',
                width: "6%"
            },
            {
                title: "名稱",
                data: 'name',
            },
            {
                title: "當前庫存",
                data: 'now_stock',
                "render": function(data, type, row, meta) {
                    var html = '';
                    if (parseFloat(data) < parseFloat(row.safe_stock)) {
                        html += '<span class="warning-red">' + data + '</span>';
                    } else {
                        html += data;
                    }
                    return html + ' ' + row.unit;
                }
            },
            {
                title: "安全庫存",
                data: 'safe_stock',
                "render": function(data, type, row, meta) {
                    return data + ' ' + row.unit;
                }
            },
            {
                title: "平均成本",
                data: 'avg_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "銷售價格",
                data: 'sale_price',
                "render": function(data, type, row, meta) {
                    return data + ' ' + '元';
                }
            },
            {
                title: "日期",
                data: 'create_time',
                orderable: false,
                "render": function(data, type, row, meta) {
                    var html = '';
                    html += '<div class="insideColums"><span>建立日期：</span><span>' + row
                        .create_time + '</span></div>';
                    var update_time = row.update_time === null ? '' : row.update_time;
                    html += '<div class="insideColums"><span>更新日期：</span><span>' + update_time +
                        '</span></div>';
                    return html;
                }
            },
        ],
        "columnDefs": [{
            "targets": 7,
            "title": "操作",
            "orderable": false,
            "data": null,
            "width": "15%",
            "render": function(data, type, row, meta) {
                var html = '';
                html +=
                    '<button type="button" class="layui-btn layui-btn-sm layui-btn-normal" onclick="openDialog(' +
                    row.id + ')"><i class="layui-icon layui-icon-edit"></i> 修改</button>';
                html +=
                    '<button type="button" class="layui-btn layui-btn-sm layui-btn-danger" onclick="delOne(' +
                    row.id + ')"><i class="layui-icon layui-icon-delete"></i> 刪除</button>';
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
        title: id !== '' ? '修改庫存品項' : '新增庫存品項',
        area: ["30%", "60%"],
        btn: ["保存", "取消"],
        content: ['<?=$openDialogUrl;?>?id=' + id],
        yes: function(index, layero) {
            var name = $.trim(layer.getChildFrame('body', index).find('#name').val());
            var unit = $.trim(layer.getChildFrame('body', index).find('#unit').val());
            var now_stock = $.trim(layer.getChildFrame('body', index).find('#now_stock').val());
            var safe_stock = $.trim(layer.getChildFrame('body', index).find('#safe_stock').val());
            var avg_price = $.trim(layer.getChildFrame('body', index).find('#avg_price').val());
            var sale_price = $.trim(layer.getChildFrame('body', index).find('#sale_price').val());
            if (name === '') {
                layer.alert("請輸入名稱。", {
                    icon: 2
                });
                return false;
            }
            $.post("<?=$saveDialogUrl;?>", {
                    "id": id,
                    "name": name,
                    "unit": unit,
                    "now_stock": now_stock,
                    "safe_stock": safe_stock,
                    "avg_price": avg_price,
                    "sale_price": sale_price,
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
        title: '刪除庫存品項'
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
</script>
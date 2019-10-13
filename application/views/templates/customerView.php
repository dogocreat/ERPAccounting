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
    居住地址：
    <div class="layui-inline">
        <input class="layui-input" name="live_address" id="live_address" autocomplete="off">
    </div>
    送貨地址：
    <div class="layui-inline">
        <input class="layui-input" name="send_address" id="send_address" autocomplete="off">
    </div>
</div>
<div style="margin:10px;">
    統編：
    <div class="layui-inline">
        <input class="layui-input" name="company_no" id="company_no" autocomplete="off">
    </div>
    發票抬頭：
    <div class="layui-inline">
        <input class="layui-input" name="tick_title" id="tick_title" autocomplete="off">
    </div>
    發票地址：
    <div class="layui-inline">
        <input class="layui-input" name="tick_address" id="tick_address" autocomplete="off">
    </div>
</div>
<div style="margin:10px;">
    手機：
    <div class="layui-inline">
        <input class="layui-input" name="phone" id="phone" autocomplete="off">
    </div>
    建立日期：
    <div class="layui-inline">
        <input class="layui-input" name="start_time" id="start_time" autocomplete="off">
    </div>
    至
    <div class="layui-inline">
        <input class="layui-input" name="end_time" id="end_time" autocomplete="off">
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
    $('#live_address').val('');
    $('#send_address').val('');
    $('#phone').val('');
    $('#company_no').val('');
    $('#tick_title').val('');
    $('#tick_address').val('');
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
                d.live_address = $.trim($('#live_address').val());
                d.send_address = $.trim($('#send_address').val());
                d.phone = $.trim($('#phone').val());
                d.company_no = $.trim($('#company_no').val());
                d.tick_title = $.trim($('#tick_title').val());
                d.tick_address = $.trim($('#tick_address').val());
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
                width: "6%"
            },
            {
                title: "地址",
                data: 'live_address',
                "orderable": false,
                "render": function(data, type, row, meta) {
                    var html = '';
                    html += '<div class="insideColums"><span>通訊地址：</span><span>' + row
                        .live_address + '</span></div>';
                    html += '<div class="insideColums"><span>送貨地址：</span><span>' + row
                        .send_address + '</span></div>';
                    return html;
                }
            },
            {
                title: "客戶信息",
                data: 'company_no',
                "orderable": false,
                "render": function(data, type, row, meta) {
                    var html = '';
                    html += '<div class="insideColums"><span>統編：</span><span>' + row
                        .company_no + '</span></div>';
                    html += '<div class="insideColums"><span>發票抬頭：</span><span>' + row
                        .tick_title + '</span></div>';
                    html += '<div class="insideColums"><span>發票地址：</span><span>' + row
                        .tick_address + '</span></div>';
                    return html;
                }
            },
            {
                title: "手機",
                data: 'phone'
            },
            {
                title: "建立日期",
                data: 'create_time'
            },
        ],
        "columnDefs": [{
            "targets": 6,
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
        title: id !== '' ? '修改客戶' : '新增客戶',
        area: ["30%", "60%"],
        btn: ["保存", "取消"],
        content: ['<?=$openDialogUrl;?>?id=' + id],
        yes: function(index, layero) {
            var name = $.trim(layer.getChildFrame('body', index).find('#name').val());
            var live_address = $.trim(layer.getChildFrame('body', index).find('#live_address').val());
            var send_address = $.trim(layer.getChildFrame('body', index).find('#send_address').val());
            var phone = $.trim(layer.getChildFrame('body', index).find('#phone').val());
            var company_no = $.trim(layer.getChildFrame('body', index).find('#company_no').val());
            var tick_title = $.trim(layer.getChildFrame('body', index).find('#tick_title').val());
            var tick_address = $.trim(layer.getChildFrame('body', index).find('#tick_address').val());
            if (name === '') {
                layer.alert("請輸入名稱。", {
                    icon: 2
                });
                return false;
            }
            $.post("<?=$saveDialogUrl;?>", {
                    "id": id,
                    "name": name,
                    "live_address": live_address,
                    "send_address": send_address,
                    "phone": phone,
                    "company_no": company_no,
                    "tick_title": tick_title,
                    "tick_address": tick_address,
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
        title: '刪除客戶'
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
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
	統編：
    <div class="layui-inline">
        <input class="layui-input" name="company_no" id="company_no" autocomplete="off">
    </div>
    公司地址：
    <div class="layui-inline">
        <input class="layui-input" name="company_address" id="company_address" autocomplete="off">
    </div>
</div>
<div style="margin:10px;">
    公司電話：
    <div class="layui-inline">
        <input class="layui-input" name="company_phone" id="company_phone" autocomplete="off">
    </div>
    手機：
    <div class="layui-inline">
        <input class="layui-input" name="phone" id="phone" autocomplete="off">
    </div>
    公司信箱：
    <div class="layui-inline">
        <input class="layui-input" name="company_email" id="company_email" autocomplete="off">
    </div>
</div>
<div style="margin:10px;">
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
    <table class="table table-striped table-bordered" id="vendorDataTable" style="width:100%;"></table>
</div>

<script>
var dataTable = null;

var searchData = () => {
    dataTable.ajax.reload(null, false);
}

var resetSearchData = () => {
    $('#id').val('');
    $('#name').val('');
    $('#company_address').val('');
    $('#company_phone').val('');
    $('#company_no').val('');
    $('#company_email').val('');
    $('#phone').val('');
    $('#start_time').val('');
    $('#end_time').val('');
}

$(document).ready(function() {
    dataTable = $('#vendorDataTable').DataTable({
        "processing": true,
        "serverSide": true,
        "searching": false,
        "ajax": {
            "type": "POST",
            "url": "<?=$dataTableListUrl;?>",
            "data": function(d) {
                d.id = $.trim($('#id').val());
                d.name = $.trim($('#name').val());
                d.company_address = $.trim($('#company_address').val());
                d.company_phone = $.trim($('#company_phone').val());
                d.company_no = $.trim($('#company_no').val());
                d.company_email = $.trim($('#company_email').val());
                d.phone = $.trim($('#phone').val());
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
                title: "統編",
                data: 'company_no',
			},
			{
                title: "公司地址",
                data: 'company_address',
			},
            {
                title: "公司電話",
                data: 'company_phone'
			},
            {
                title: "手機",
                data: 'phone'
            },
            {
                title: "公司信箱",
                data: 'company_email',
            },
            {
                title: "建立日期",
                data: 'create_time'
            },
        ],
        "columnDefs": [{
            "targets": 8,
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
        title: id !== '' ? '修改廠商' : '新增廠商',
        area: ["30%", "60%"],
        btn: ["保存", "取消"],
        content: ['<?=$openDialogUrl;?>?id=' + id],
        yes: function(index, layero) {
            var name = $.trim(layer.getChildFrame('body', index).find('#name').val());
            var company_no = $.trim(layer.getChildFrame('body', index).find('#company_no').val());
            var company_address = $.trim(layer.getChildFrame('body', index).find('#company_address').val());
            var company_email = $.trim(layer.getChildFrame('body', index).find('#company_email').val());
            var company_phone = $.trim(layer.getChildFrame('body', index).find('#company_phone').val());
            var phone = $.trim(layer.getChildFrame('body', index).find('#phone').val());
            if (name === '') {
                layer.alert("請輸入名稱。", {
                    icon: 2
                });
                return false;
            }
            $.post("<?=$saveDialogUrl;?>", {
                    "id": id,
                    "name": name,
                    "company_no": company_no,
                    "company_address": company_address,
                    "company_email": company_email,
                    "company_phone": company_phone,
                    "phone": phone,
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
        title: '刪除廠商'
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
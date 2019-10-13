<!-- search bar -->
<div style="margin:10px;">
    搜索日期：
    <div class="layui-inline">
        <input class="layui-input" name="start_date" id="start_date" autocomplete="off" value="<?=date("Y-m-d")?>">
    </div>
    至
    <div class="layui-inline">
        <input class="layui-input" name="end_date" id="end_date" autocomplete="off" value="<?=date("Y-m-d")?>">
    </div>

    <div class="layui-inline">
        <button class="layui-btn layui-btn-normal layui-btn-sm" type="button" onclick="changeSearchDate('today');"><i
                class="layui-icon layui-icon-date"></i> 當天</button>
        <button class="layui-btn layui-btn-normal layui-btn-sm" type="button" onclick="changeSearchDate('threeday');"><i
                class="layui-icon layui-icon-date"></i> 三天</button>
        <button class="layui-btn layui-btn-normal layui-btn-sm" type="button" onclick="changeSearchDate('week');"><i
                class="layui-icon layui-icon-date"></i> 一周</button>
        <button class="layui-btn layui-btn-normal layui-btn-sm" type="button" onclick="changeSearchDate('month');"><i
                class="layui-icon layui-icon-date"></i> 當月</button>
        <button class="layui-btn layui-btn-sm" type="button" data-type="reset" onclick="resetSearchData();"><i
                class="layui-icon layui-icon-refresh"></i> 清空</button>
        <button class="layui-btn layui-btn-sm" type="button" data-type="reload"
            onclick="searchData();searchEchartsData();"><i class="layui-icon layui-icon-search"></i> 查詢</button>
    </div>
</div>

<!-- DataTable -->
<div style="margin-top:40px;">
    <div><span class="warning-red">(＊) </span><span class="layui-word-aux" style="font-size:16px;">進貨應付金額 = 進貨金額 - 進貨營業稅
            - 進貨折讓</span></div>
    <div><span class="warning-red">(＊) </span><span class="layui-word-aux" style="font-size:16px;">進貨應付金額 = 進貨已付 +
            進貨未付</span></div>
    <div><span class="warning-red">(＊) </span><span class="layui-word-aux" style="font-size:16px;">銷貨應收金額 = 銷貨金額 - 銷貨營業稅
            - 銷貨折讓</span></div>
    <div><span class="warning-red">(＊) </span><span class="layui-word-aux" style="font-size:16px;">銷貨應收金額 = 銷貨已收 +
            銷貨未收</span></div>
    <div><span class="warning-red">(＊) </span><span class="layui-word-aux" style="font-size:16px;">盈餘 = 銷貨應收金額 -
            進貨應付金額</span></div>
    <table class="table table-striped table-bordered" id="reportTable" style="width:100%;margin-top:20px;">
        <tr>
            <th>進貨金額</th>
            <th>進貨營業稅</th>
            <th>進貨折讓</th>
            <th>進貨應付</th>
            <th>進貨已付</th>
            <th>進貨未付</th>

            <th>銷貨金額</th>
            <th>銷貨營業稅</th>
            <th>銷貨折讓</th>
            <th>銷貨應收</th>
            <th>銷貨已收</th>
            <th>銷貨未收</th>

            <th>盈餘</th>
        </tr>
        <tr id="search_content">

        </tr>
    </table>
</div>

<!-- EChart -->
<!-- <div id="chartmain" style="width:600px; height: 400px;"></div> -->
<div id="chartmain" style="width:100%;height: 50%;"></div>

<script type="text/javascript" src="www/third_party/echarts/echarts.min.js"></script>
<script>
var searchData = () => {
    var start_date = $.trim($('#start_date').val());
    var end_date = $.trim($('#end_date').val());
    $.post("<?=$getDataUrl;?>", {
            "start_date": start_date,
            "end_date": end_date,
        },
        function(data) {
            $("#search_content").empty();
            $("#search_content").append("<td>" + data.instock_report.sum_instock_price + "</td>");
            $("#search_content").append("<td>" + data.instock_report.sum_instock_tax_price + "</td>");
            $("#search_content").append("<td>" + data.instock_report.sum_instock_back_price + "</td>");
            $("#search_content").append("<td>" + data.instock_report.sum_instock_payable_price + "</td>");
            $("#search_content").append("<td>" + data.instock_report.sum_instock_payabled_price + "</td>");
            $("#search_content").append("<td>" + data.instock_report.sum_instock_unpayable_price + "</td>");

            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_price + "</td>");
            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_tax_price + "</td>");
            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_back_price + "</td>");
            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_receivable_price + "</td>");
            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_receivabled_price + "</td>");
            $("#search_content").append("<td>" + data.outstock_report.sum_outstock_unreceivable_price +
                "</td>");

            var sum_profit = parseFloat(data.outstock_report.sum_outstock_receivable_price) - parseFloat(data
                .instock_report.sum_instock_payable_price);
            if (sum_profit > 0) {
                $("#search_content").append("<td>" + sum_profit.toFixed(2) + "</td>");
            } else {
                $("#search_content").append("<td class='warning-red'>" + sum_profit.toFixed(2) + "</td>");
            }
        }, "json");
}

var resetSearchData = () => {
    $('#start_date').val('');
    $('#end_date').val('');
}

var searchEchartsData = () => {
    var start_date = $.trim($('#start_date').val());
    var end_date = $.trim($('#end_date').val());
    $.post("<?=$getEchartsDataUrl;?>", {
            "start_date": start_date,
            "end_date": end_date,
        },
        function(data) {
            createEcharts(data);
        }, "json");
}

var createEcharts = (result) => {
    //指定圖標的配置和數據 
    var option = {
        title: {
            text: '統計圖'
        },
        tooltip: {
            trigger: 'axis'
        },
        legend: {
            data: ['進貨應付', '銷貨應收', '盈餘']
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            data: result.date_arr,
        },
        yAxis: {
            type: 'value'
        },
        yAxis: {},
        series: [{
                name: '進貨應付',
                type: 'line',
                stack: '总量',
                data: result.instock,
            },
            {
                name: '銷貨應收',
                type: 'line',
                stack: '总量',
                data: result.outstock,
            },
            {
                name: '盈餘',
                type: 'line',
                stack: '总量',
                data: result.profit,
            },
        ]
    };
    //初始化echarts實例 
    var myChart = echarts.init(document.getElementById('chartmain'));
    //使用制定的配置項和數據顯示圖表 
    myChart.setOption(option);
}

var changeSearchDate = (date) =>{
    if(date == 'today'){
        $('#start_date').val('<?=date('Y-m-d');?>');
        $('#end_date').val('<?=date('Y-m-d');?>');
    }else if(date == 'threeday'){
        $('#start_date').val('<?=date('Y-m-d',strtotime("-2 days"));?>');
        $('#end_date').val('<?=date('Y-m-d');?>');
    }
    else if(date == 'week'){
        $('#start_date').val('<?=date('Y-m-d',strtotime("-1 week"));?>');
        $('#end_date').val('<?=date('Y-m-d');?>');
    }else if(date == 'month'){
        $('#start_date').val('<?=date('Y-m-d', mktime(0,0,0,date('m'),1,date('Y')));?>');
        $('#end_date').val('<?=date('Y-m-d');?>');
    }
    searchData();
    searchEchartsData();
}
</script>
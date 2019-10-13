</body>
</html>

<script type="text/javascript" src="www/third_party/DataTables/datatables.min.js"></script>
<script type="text/javascript" src="www/third_party/pjax/jquery.pjax.js"></script>
<script src="www/third_party/layui/layui.js" charset="utf-8"></script>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).pjax('a', '#main');
        initLayui();

        //初始化時或頁面重整時菜單導覽刷回
        var menuItems = $("#navMenu").find('dd[lay-id]');
        menuItems.each(function(i,o){
            if($(o).attr('lay-id') === window.location.pathname){
                $(o).addClass('layui-this');
            }
        });
    });

    $(document).on('pjax:complete', function() {
        initLayui();
    })

    function initLayui(){
        //注意：导航 依赖 element 模块，否则无法进行功能性操作
        layui.use(['layer','element',"laydate","form"], function() {
            var layer = layui.layer;
            var element = layui.element;
            var laydate = layui.laydate;
            var form = layui.form;
            //执行一个laydate实例
            laydate.render({
                elem: '#start_time', //指定元素
                type: 'datetime'
            });
            laydate.render({
                elem: '#end_time', //指定元素
                type: 'datetime'
            });
            laydate.render({
                elem: '#start_date', //指定元素
                type: 'date'
            });
            laydate.render({
                elem: '#end_date', //指定元素
                type: 'date'
            });
        });
    }
</script>
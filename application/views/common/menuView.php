<style>
.menuIconTitle {
    font-size: 30px !important;
    color: #1E9FFF;
}

.menuIcon {
    font-size: 25px !important;
    color: #1E9FFF;
}
</style>
<div class="layui-side layui-bg-black" id="navMenu">
    <div class="layui-side-scroll" lay-filter="erp-side-menu">

        <ul class="layui-nav layui-nav-tree layui-nav-side" >

            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;"><i class="layui-icon layui-icon-set menuIconTitle"></i>
                    資料維護</a>
                <dl class="layui-nav-child">
                    <dd lay-id="/ERPAccounting/customer">
                        <a href="/ERPAccounting/customer"><i class="layui-icon layui-icon-username menuIcon"></i> 客戶資料</a>
                    </dd>
                    <dd lay-id="/ERPAccounting/vendor">
                        <a href="/ERPAccounting/vendor"><i class="layui-icon layui-icon-user menuIcon"></i> 廠商資料</a>
                    </dd>
                    <dd lay-id="/ERPAccounting/instock">
                        <a href="/ERPAccounting/instock"><i class="layui-icon layui-icon-cart menuIcon"></i> 進貨管理</a>
                    </dd>
                    <dd lay-id="/ERPAccounting/outstock">
                        <a href="/ERPAccounting/outstock"><i class="layui-icon layui-icon-cart-simple menuIcon"></i> 銷貨管理</a>
                    </dd>
                    <dd lay-id="/ERPAccounting/stock">
                        <a href="/ERPAccounting/stock"><i class="layui-icon layui-icon-component menuIcon"></i> 庫存管理</a>
                    </dd>
                </dl>
            </li>

            <li class="layui-nav-item layui-nav-itemed">
                <a href="javascript:;"><i class="layui-icon layui-icon-survey menuIconTitle"></i>
                    統計</a>
                <dl class="layui-nav-child">
                    <dd lay-id="/ERPAccounting/report">
                        <a href="/ERPAccounting/report"><i class="layui-icon layui-icon-form menuIcon"></i> 銷售統計</a>
                    </dd>
                </dl>
            </li>

            <li class="layui-nav-item" style="height: 30px; text-align: center"></li>
        </ul>

    </div>
</div>
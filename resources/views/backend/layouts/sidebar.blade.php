<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <!--<li class="menu-title mt-2" data-key="t-components">@lang('translation.Elements')</li>-->
                <li class=" @if( isset($RouteGroup) && $RouteGroup=='dashboard' ) mm-active @endif ">
                    <a href="{{ route('dashboard.home.index') }}" class="@if( Route::currentRouteName()=='dashboard.home.index' ) active @endif">
                        <i class=" fas fa-home"></i>
                        <span data-key="t-dashboard">@lang('translation.Dashboard')</span>
                    </a>
                </li>

                <li class=" @if( isset($RouteGroup) && $RouteGroup=='products' ) mm-active @endif ">
                    <a href="{{ route('dashboard.products.index') }}" class="@if( Route::currentRouteName()=='dashboard.products.index' ) active @endif">
                        <i class=" fab fa-shopify"></i>
                        <span data-key="t-products">商品列表</span>
                    </a>
                </li>

                <li class=" @if( isset($RouteGroup) && $RouteGroup=='orders' ) mm-active @endif ">
                    <a href="{{ route('dashboard.orders.index') }}" class="@if( Route::currentRouteName()=='dashboard.orders.index' ) active @endif">
                        <i class=" fas fa-shopping-cart"></i>
                        <span data-key="t-orders">訂單銷售</span>
                    </a>
                </li>

                <li class=" @if( isset($RouteGroup) && $RouteGroup=='shipment' ) mm-active @endif ">
                    <a href="{{ route('dashboard.shipment.index') }}" class="@if( Route::currentRouteName()=='dashboard.shipment.index' ) active @endif">
                        <i class="fas fa-box-open"></i>
                        <span data-key="t-shipment">出庫</span>
                    </a>
                </li>

                <li class=" @if( isset($RouteGroup) && $RouteGroup=='stockin' ) mm-active @endif ">
                    <a href="{{ route('dashboard.stockin.index') }}" class="@if( Route::currentRouteName()=='dashboard.stockin.index' ) active @endif">
                        <i class="fas fa-cubes"></i>
                        <span data-key="t-stockin">入庫</span>
                    </a>
                </li>

                <li class=" @if( isset($RouteGroup) && $RouteGroup=='stockrecord' ) mm-active @endif ">
                    <a href="{{ route('dashboard.stockrecord.index') }}" class="@if( Route::currentRouteName()=='dashboard.stockrecord.index' ) active @endif">
                        <i class="far fa-list-alt"></i>
                        <span data-key="t-stockrecord">庫存紀錄</span>
                    </a>
                </li>

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->

<div id="sideBar"
    class="relative flex flex-col flex-wrap bg-white border-r border-gray-300 p-6 flex-none w-64 md:-ml-64 md:fixed md:top-0 md:z-30 md:h-screen md:shadow-xl animated faster">


    <!-- sidebar content -->
    <div class="flex flex-col">

        <!-- sidebar toggle -->
        <div class="text-right hidden md:block mb-4">
            <button id="sideBarHideBtn">
                <i class="fad fa-times-circle"></i>
            </button>
        </div>
        <!-- end sidebar toggle -->

        <p class="uppercase text-xs text-gray-600 mb-4 tracking-wider">homes</p>

        <!-- link -->
        <a href="{{ route('dashboard') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('dashboard') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-chart-pie text-xs mr-2"></i>
            Analytics dashboard
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="./index-1.html"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500">
            <i class="fad fa-shopping-cart text-xs mr-2"></i>
            ecommerce dashboard
        </a>
        <!-- end link -->

        <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Management</p>

        <!-- link -->
        <a href="{{ route('categories.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('categories.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-sitemap text-xs mr-2"></i>
            categories
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('attributes.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('attributes.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-tags text-xs mr-2"></i>
            attributes
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('products.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('products.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-box text-xs mr-2"></i>
            products
        </a>
        <!-- end link -->

        <p class="uppercase text-xs text-gray-600 mb-4 mt-4 tracking-wider">Logika Mutasi Stok</p>

        <!-- link -->
        <a href="{{ route('stock.inbound.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('stock.inbound.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-arrow-alt-down text-xs mr-2 text-green-500"></i>
            Barang Masuk (Inbound)
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('stock.outbound.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('stock.outbound.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-arrow-alt-up text-xs mr-2 text-red-500"></i>
            Barang Keluar (Outbound)
        </a>
        <!-- end link -->

        <!-- link -->
        <a href="{{ route('stock.ledger.index') }}"
            class="mb-3 capitalize font-medium text-sm hover:text-teal-600 transition ease-in-out duration-500 {{ request()->routeIs('stock.ledger.*') ? 'text-teal-600 font-semibold' : '' }}">
            <i class="fad fa-clipboard-list text-xs mr-2 text-indigo-500"></i>
            Kartu Stok (Ledger)
        </a>
        <!-- end link -->

    </div>
    <!-- end sidebar content -->

</div>


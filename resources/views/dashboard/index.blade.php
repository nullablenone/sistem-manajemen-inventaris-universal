@extends('layouts.app')

@section('content')
    <!-- General Report -->
    <div class="grid grid-cols-4 gap-6 xl:grid-cols-1">


        <!-- card 1: Inbound -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-green-700 fad fa-arrow-alt-down"></div>
                        <span class="rounded-full text-white badge bg-green-500 text-xs px-2.5 py-0.5 font-bold font-sans">
                            Inbound
                        </span>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5 font-extrabold text-gray-900">{{ $totalInbound }} Pcs</h1>
                        <p class="text-xs text-gray-500 mt-1 font-sans">Total barang masuk</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->


        <!-- card 2: Outbound -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-red-700 fad fa-arrow-alt-up"></div>
                        <span class="rounded-full text-white badge bg-red-500 text-xs px-2.5 py-0.5 font-bold font-sans">
                            Outbound
                        </span>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5 font-extrabold text-gray-900">{{ $totalOutbound }} Pcs</h1>
                        <p class="text-xs text-gray-500 mt-1 font-sans">Total barang keluar</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->


        <!-- card 3: Products -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-yellow-600 fad fa-box"></div>
                        <span class="rounded-full text-white badge bg-yellow-500 text-xs px-2.5 py-0.5 font-bold font-sans">
                            Catalog
                        </span>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5 font-extrabold text-gray-900">{{ $totalProducts }} Item</h1>
                        <p class="text-xs text-gray-500 mt-1 font-sans">Produk terdaftar</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->


        <!-- card 4: Physical Stock -->
        <div class="report-card">
            <div class="card">
                <div class="card-body flex flex-col">

                    <!-- top -->
                    <div class="flex flex-row justify-between items-center">
                        <div class="h6 text-indigo-700 fad fa-warehouse"></div>
                        <span class="rounded-full text-white badge bg-indigo-500 text-xs px-2.5 py-0.5 font-bold font-sans">
                            Stock
                        </span>
                    </div>
                    <!-- end top -->

                    <!-- bottom -->
                    <div class="mt-8">
                        <h1 class="h5 font-extrabold text-gray-900">{{ $totalStock }} Pcs</h1>
                        <p class="text-xs text-gray-500 mt-1 font-sans">Stok fisik tersedia</p>
                    </div>
                    <!-- end bottom -->

                </div>
            </div>
            <div class="footer bg-white p-1 mx-4 border border-t-0 rounded rounded-t-none"></div>
        </div>
        <!-- end card -->


    </div>
    <!-- End General Report -->

    <!-- strat Analytics -->
    <div class="mt-6 grid grid-cols-2 gap-6 xl:grid-cols-1">

        <!-- update section -->
        <div class="card bg-teal-400 border-teal-400 shadow-md text-white">
            <div class="card-body flex flex-row">

                <!-- image -->
                <div class="img-wrapper w-40 h-40 flex justify-center items-center">
                    <img src="./img/happy.svg" alt="img title">
                </div>
                <!-- end image -->

                <!-- info -->
                <div class="py-2 ml-10">
                    <h1 class="h6">Good Job, Mohamed!</h1>
                    <p class="text-white text-xs">You've finished all of your tasks for this week.</p>

                    <ul class="mt-4">
                        <li class="text-sm font-light"><i class="fad fa-check-double mr-2 mb-2"></i> Finish
                            Dashboard Design</li>
                        <li class="text-sm font-light"><i class="fad fa-check-double mr-2 mb-2"></i> Fix Issue
                            #74</li>
                        <li class="text-sm font-light"><i class="fad fa-check-double mr-2"></i> Publish
                            version 1.0.6</li>
                    </ul>
                </div>
                <!-- end info -->

            </div>
        </div>
        <!-- end update section -->

        <!-- carts -->
        <div class="flex flex-col">

            <!-- alert -->
            <div class="alert alert-dark mb-6">
                Hi! Wait A Minute . . . . . . Follow Me On Twitter
                <a class="ml-2" target="_blank" href="https://twitter.com/MohamedSaid__">@moesaid</a>
            </div>
            <!-- end alert -->

            <!-- charts -->
            <div class="grid grid-cols-2 gap-6 h-full">

                <div class="card">
                    <div class="py-3 px-4 flex flex-row justify-between">
                        <h1 class="h6">
                            <span class="num-4"></span>k
                            <p>page view</p>
                        </h1>

                        <div
                            class="bg-teal-200 text-teal-700 border-teal-300 border w-10 h-10 rounded-full flex justify-center items-center">
                            <i class="fad fa-eye"></i>
                        </div>
                    </div>
                    <div class="analytics_1"></div>
                </div>

                <div class="card">
                    <div class="py-3 px-4 flex flex-row justify-between">
                        <h1 class="h6">
                            <span class="num-2"></span>k
                            <p>Unique Users</p>
                        </h1>

                        <div
                            class="bg-indigo-200 text-indigo-700 border-indigo-300 border w-10 h-10 rounded-full flex justify-center items-center">
                            <i class="fad fa-users-crown"></i>
                        </div>
                    </div>
                    <div class="analytics_1"></div>
                </div>

            </div>
            <!-- charts    -->

        </div>
        <!-- end charts -->


    </div>
    <!-- end Analytics -->

    <!-- Sales Overview -->
    <div class="card mt-6">

        <!-- header -->
        <div class="card-header flex flex-row justify-between">
            <h1 class="h6">Sales Overview</h1>

            <div class="flex flex-row justify-center items-center">

                <a href="">
                    <i class="fad fa-chevron-double-down mr-6"></i>
                </a>

                <a href="">
                    <i class="fad fa-ellipsis-v"></i>
                </a>

            </div>

        </div>
        <!-- end header -->

        <!-- body -->
        <div class="card-body grid grid-cols-2 gap-6 lg:grid-cols-1">

            <div class="p-8">
                <h1 class="h2">5,337</h1>
                <p class="text-black font-medium">Sales this month</p>

                <div class="mt-20 mb-2 flex items-center">
                    <div class="py-1 px-3 rounded bg-green-200 text-green-900 mr-3">
                        <i class="fa fa-caret-up"></i>
                    </div>
                    <p class="text-black"><span class="num-2 text-green-400"></span><span class="text-green-400">% more
                            sales</span> in comparison to last month.</p>
                </div>

                <div class="flex items-center">
                    <div class="py-1 px-3 rounded bg-red-200 text-red-900 mr-3">
                        <i class="fa fa-caret-down"></i>
                    </div>
                    <p class="text-black"><span class="num-2 text-red-400"></span><span class="text-red-400">% revenue per
                            sale</span> in comparison to last month.</p>
                </div>

                <a href="#" class="btn-shadow mt-6">view details</a>

            </div>

            <div class="">
                <div id="sealsOverview"></div>
            </div>

        </div>
        <!-- end body -->

    </div>
    <!-- end Sales Overview -->

    <!-- start numbers -->
    <div class="grid grid-cols-5 gap-6 xl:grid-cols-2">

        <!-- card -->
        <div class="card mt-6">
            <div class="card-body flex items-center">

                <div class="px-3 py-2 rounded bg-indigo-600 text-white mr-3">
                    <i class="fad fa-wallet"></i>
                </div>

                <div class="flex flex-col">
                    <h1 class="font-semibold"><span class="num-2"></span> Sales</h1>
                    <p class="text-xs"><span class="num-2"></span> payments</p>
                </div>

            </div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="card mt-6">
            <div class="card-body flex items-center">

                <div class="px-3 py-2 rounded bg-green-600 text-white mr-3">
                    <i class="fad fa-shopping-cart"></i>
                </div>

                <div class="flex flex-col">
                    <h1 class="font-semibold"><span class="num-2"></span> Orders</h1>
                    <p class="text-xs"><span class="num-2"></span> items</p>
                </div>

            </div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="card mt-6 xl:mt-1">
            <div class="card-body flex items-center">

                <div class="px-3 py-2 rounded bg-yellow-600 text-white mr-3">
                    <i class="fad fa-blog"></i>
                </div>

                <div class="flex flex-col">
                    <h1 class="font-semibold"><span class="num-2"></span> posts</h1>
                    <p class="text-xs"><span class="num-2"></span> active</p>
                </div>

            </div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="card mt-6 xl:mt-1">
            <div class="card-body flex items-center">

                <div class="px-3 py-2 rounded bg-red-600 text-white mr-3">
                    <i class="fad fa-comments"></i>
                </div>

                <div class="flex flex-col">
                    <h1 class="font-semibold"><span class="num-2"></span> comments</h1>
                    <p class="text-xs"><span class="num-2"></span> approved</p>
                </div>

            </div>
        </div>
        <!-- end card -->

        <!-- card -->
        <div class="card mt-6 xl:mt-1 xl:col-span-2">
            <div class="card-body flex items-center">

                <div class="px-3 py-2 rounded bg-pink-600 text-white mr-3">
                    <i class="fad fa-user"></i>
                </div>

                <div class="flex flex-col">
                    <h1 class="font-semibold"><span class="num-2"></span> memebrs</h1>
                    <p class="text-xs"><span class="num-2"></span> online</p>
                </div>

            </div>
        </div>
        <!-- end card -->

    </div>
    <!-- end nmbers -->

    <!-- start quick Info -->
    <div class="grid grid-cols-3 gap-6 mt-6 xl:grid-cols-1">


        <!-- Browser Stats -->
        <div class="card">
            <div class="card-header">Browser Stats</div>

            <!-- brawser -->
            <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
                <div class="flex items-center">
                    <i class="fab fa-chrome mr-4"></i>
                    <h1>google chrome</h1>
                </div>
                <div>
                    <span class="num-2"></span>%
                </div>
            </div>
            <!-- end brawser -->

            <!-- brawser -->
            <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
                <div class="flex items-center">
                    <i class="fab fa-firefox mr-4"></i>
                    <h1>firefox</h1>
                </div>
                <div>
                    <span class="num-2"></span>%
                </div>
            </div>
            <!-- end brawser -->

            <!-- brawser -->
            <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b">
                <div class="flex items-center">
                    <i class="fab fa-internet-explorer mr-4"></i>
                    <h1>internet explorer</h1>
                </div>
                <div>
                    <span class="num-2"></span>%
                </div>
            </div>
            <!-- end brawser -->

            <!-- brawser -->
            <div class="p-6 flex flex-row justify-between items-center text-gray-600 border-b-0">
                <div class="flex items-center">
                    <i class="fab fa-safari mr-4"></i>
                    <h1>safari</h1>
                </div>
                <div>
                    <span class="num-2"></span>%
                </div>
            </div>
            <!-- end brawser -->

        </div>
        <!-- end Browser Stats -->

        <!-- Start Recent Sales -->
        <div class="card col-span-2 xl:col-span-1 border border-gray-200">
            <div class="card-header border-b border-gray-200 font-bold">Transaksi Keluar Terbaru</div>

            <table class="table-auto w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 text-xs uppercase font-semibold">
                        <th class="px-4 py-3 border-b border-gray-200">No Transaksi</th>
                        <th class="px-4 py-3 border-b border-gray-200">Detail Item</th>
                        <th class="px-4 py-3 border-b border-gray-200 text-center">Catatan</th>
                        <th class="px-4 py-3 border-b border-gray-200 text-center">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600">
                    @forelse($recentSales as $sale)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/40 transition-colors">
                            <td class="px-4 py-3 font-mono font-bold text-red-700 text-xs">{{ $sale->transaction_number }}</td>
                            <td class="px-4 py-3 text-xs">
                                <ul class="list-disc pl-4 space-y-1">
                                    @foreach($sale->items as $item)
                                        @php $v = $item->productVariant; @endphp
                                        <li>
                                            <span class="font-semibold text-gray-800">{{ $v->product->name }}</span>
                                            @if($v->product->has_variant && $v->attributeValues->isNotEmpty())
                                                <span class="text-gray-500">({{ $v->attributeValues->pluck('value')->implode('/') }})</span>
                                            @endif
                                            : <span class="bg-red-50 text-red-700 px-1.5 py-0.2 rounded font-bold">{{ $item->quantity }} pcs</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="px-4 py-3 text-xs italic text-center">{{ $sale->note ?? '-' }}</td>
                            <td class="px-4 py-3 text-xs text-center font-medium">{{ $sale->date->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center py-4">
                                    <i class="fad fa-arrow-alt-up text-3xl text-gray-300 mb-1"></i>
                                    <p class="font-semibold">Belum Ada Transaksi Keluar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <!-- End Recent Sales -->


    </div>
    <!-- end quick Info -->
@endsection

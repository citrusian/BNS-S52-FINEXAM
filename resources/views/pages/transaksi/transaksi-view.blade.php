@extends('layouts.app')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Laporan Transaksi'])

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Sales</p>
                                    <h5 class="font-weight-bolder" style="color: #40a603">
                                        {{$monthIncome}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span>
                                        This Month
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Expense</p>
                                    <h5 class="font-weight-bolder" style="color: #e30000">
                                        {{$monthExpense}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span>
                                        This Month
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Staff</p>
                                    <h5 class="font-weight-bolder" style="color: #262626">
                                        {{$totalUser}}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-danger text-sm font-weight-bolder"></span>
                                        &nbsp
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Profit / Loss</p>
                                    <h5 class="font-weight-bolder" style="color: {{ $lossStatus === 0 ? '#40a603' : '#e30000' }}">
                                        {{ $monthProfit }}
                                    </h5>
                                    <p class="mb-0">
                                        <span class="text-success text-sm font-weight-bolder"></span> This month Profit / Loss
                                    </p>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            {{--            <div class="col-lg-7 mb-lg-0 mb-4">--}}
            <div class="col-lg-7 mx-auto">
                {{--                <div class="card z-index-2 h-100">--}}
                <div class="card z-index-2" style="height: 100% ">
                    <div class="card-header pb-0 pt-3 bg-transparent">
                        <h6 class="text-capitalize">Sales overview</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="chart">
                            <div id="chart_div"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card card-carousel overflow-hidden h-100 p-0">
                    <div id="piechart" style="width: 900px; height: 600px;margin-right: 0; margin-left: 0;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4 mx-4">
        <div class="col-12">


















            <div class="row pb-4">
                <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
                    {{--                    col-xl-4 control card size--}}
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row" style="display: flex; justify-content: center;  align-items: center;">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold" style="display: flex; justify-content: center;  align-items: center;">Action</p>
                                        <div class="card-body px-0 pt-0 pb-2" style="display: flex; justify-content: center;  align-items: center;">
                                            <form role="form" method="GET" action={{ route('transaksi-register') }} enctype="multipart/form-data">
                                                {{--                        @csrf--}}
                                                <div class="card-header pb-0">
                                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">New Transaction</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-sm-6 mb-xl-0 mb-4">
                    {{--                    col-xl-4 control card size--}}
                    <div class="card" >
                        <div class="card-body p-3" >
                            <div class="row" style="display: flex; justify-content: center;  align-items: center;">
                                <div class="col-8">
                                    <div class="numbers" >
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold" style="display: flex; justify-content: center;  align-items: center;">Table Filter</p>
                                        <div class="card-body px-0 pt-0 pb-2"style="display: flex; justify-content: center;  align-items: center;" >
                                            <form role="form" method="POST" action={{ route('transaksi-view-nofilter') }} enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="filter" value="0">
                                                <div class="card-header pb-0">
                                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Disable Filter</button>
                                                </div>
                                            </form>
                                            <form role="form" method="POST" action={{ route('transaksi-view-filtersell') }} enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="filter" value="1">
                                                <div class="card-header pb-0">
                                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Jual</button>
                                                </div>
                                            </form>
                                            <form role="form" method="POST" action={{ route('transaksi-view-filterbuy') }} enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="filter" value="2">
                                                <div class="card-header pb-0">
                                                    <button type="submit" class="btn btn-primary btn-sm ms-auto">Beli</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4 ">
                <div class="card-header pb-0">
                    <h6>Transaction List</h6>
                </div>
                <div class="mx-md-11 text-md-center" id="alert">
                    @include('components.alert')
                </div>
                <div class="card-body px-0 pt-0 pb-2">




                    <div class="table-responsive-xl" id="table-id">
                        <table class="table align-items-center table-flush" id="table-id">
                            <thead>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Model Code</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Harga</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis Transaksi</th>
                                @if($role == 0)
                                    <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Edit</th>
                                    <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Delete</th>
                                @endif
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Model Code</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Harga</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis Transaksi</th>
                                @if($role == 0)
                                    <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Edit</th>
                                    <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Delete</th>
                                @endif
                            </tr>
                            </tfoot>

                            <tbody class="Table">
                            @foreach ($q1 as $query)
                                <tr class="paginate">
                                    <td class="Nama align-middle text-center text-wrap">
                                        {{ $query->No_Trans }}
                                    </td>
                                    <td class="Deskripsi align-middle text-center text-wrap" style="width:15%;">
                                        {{ $query->Tanggal }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Product_id }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Price }}
                                    </td>
                                    <td class="Stok align-middle text-center text-wrap">
                                        {{ $query->Customer_Vendor }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Trans_Type }}
                                    </td>
                                    @if($role == 0)
                                        <td class="Action text-center text-wrap">
                                                <form role="form" method="POST" action={{ route('transaksi-edit') }} enctype="multipart/form-data">
                                                    @csrf
                                                    <input class="form-control" style="display: none;" type="text" name="postkey" value="{{ $query->No_Trans }}">
                                                        <button type="submit" class="btn btn-primary">Edit</button>
                                                </form>
                                        </td>
                                        <td class="Action text-center text-wrap">
                                                <form role="form" method="POST" action={{ route('transaksi-delete') }} enctype="multipart/form-data">
                                                    @csrf
                                                    <input class="form-control" style="display: none;" type="text" name="postkey" value="{{ $query->No_Trans }}">
                                                        <button type="button" class="btn btn-primary popupButton">Delete</button>
                                                </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div id="page-nav"></div>
                    </div>
                    @include('layouts.footers.auth.footer-transaksi-view')
                    @include('layouts.footers.auth.footer-transaksi-chart')
                </div>
            </div>
        </div>
@endsection

@extends('layouts.app')
@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Transaction Data'])
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./assets/js/jquery-1.11.2.min.js"></script>
{{--    <script src="./assets/js/simplePagination.js"></script>--}}
    <script src="./assets/js/jquery.simplePagination.enrique26.js"></script>
{{--    <script src="./assets/js/simplePagination-1.6.js"></script>--}}
{{--    <script src="./assets/js/main.js"></script>--}}
{{--    <script src="./assets/js/jquery.pajinate.min.js"></script>--}}
    <link type="text/css" rel="stylesheet" href="./assets/css/simplePagination.css"/>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Action</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form role="form" method="GET" action={{ route('transaksi-register') }} enctype="multipart/form-data">
{{--                        @csrf--}}
                        <div class="card-header pb-0">
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">New Transaction</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card mb-4">
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
{{--                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>--}}
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Item Code</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Harga</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis Transaksi</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Edit</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Delete</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
{{--                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>--}}
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Item Code</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Harga</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis Transaksi</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Edit</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Delete</th>
                            </tr>
                            </tfoot>

                            <tbody class="Table">
                            @foreach ($q1 as $query)
                                <tr  class="paginate">
{{--                                    <td class="No align-middle text-center text-wrap" style="width:3%;">--}}
{{--                                        {{ $query->id }}--}}
{{--                                    </td>--}}
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
                                    <td class="Action text-center text-wrap"  style="width: 5%; height: 5%">
                                        <div class="justify-content-center align-items-center">
                                            <form role="form" method="POST" action={{ route('transaksi-edit') }} enctype="multipart/form-data">
                                                @csrf
                                                <div id='HiddenView' style="display: none;">
                                                    <input class="form-control" type="text" name="postkey" value="{{ $query->No_Trans }}" >
                                                </div>
                                                <div class="card-header py-0">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </div>
                                            </form>

                                        </div>
                                    </td>
                                    <td class="Action text-center text-wrap"  style="width: 5%; height: 5%">
                                        <div class="justify-content-center align-items-center">
                                            <form role="form" method="POST" action={{ route('transaksi-delete') }} enctype="multipart/form-data">
                                                @csrf
                                                <div id='HiddenView' style="display: none;">
                                                    <input class="form-control" type="text" name="postkey" value="{{ $query->No_Trans }}" >
                                                </div>
                                                <div class="card-header py-0">
                                                    <button type="submit" class="btn btn-primary">Delete</button>
                                                </div>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div id="page-nav"></div>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer"  style="border-radius: 0 0;">
                        <script>
                            // pagination 1.6, doesn't work for this type of pagination
                            // $(function() {
                            //     $('#table-id').pagination({
                            //         items: 100,
                            //         itemsOnPage: 10,
                            //         cssStyle: 'light-theme'
                            //     });
                            // });
                            $("#table-id").simplePagination({
                                perPage: 10,
                                currentPage: 1,
                                previousButtonClass: "btn btn-primary",
                                nextButtonClass: "btn btn-primary",
                                paginatorAlign: "center"
                            });
                        </script>
                    </div>
            </div>
        </div>
    </div>
@endsection

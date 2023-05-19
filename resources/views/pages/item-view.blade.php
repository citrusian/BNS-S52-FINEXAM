@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Item Data'])
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./assets/js/jquery-1.11.2.min.js"></script>
    <script src="./assets/js/jquery.simplePagination.enrique26.js"></script>
{{--    public/assets/js--}}
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
                    <h6>Item List</h6>
                </div>
                <div class="mx-md-11 text-md-center" id="alert">
                    @include('components.alert')
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive-xl" id="table-id">
                        <table class="table align-items-center table-flush" id="table-id">
                            <thead>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Model</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Name</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Brand</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Price</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Serial_no</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Prod_date</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Used</th>
{{--                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Action</th>--}}
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Model</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Name</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Brand</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Price</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Serial_no</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Prod_date</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Used</th>
{{--                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Action</th>--}}
                            </tr>
                            </tfoot>

                            <tbody class="Table">
                            @foreach ($q1 as $query)
                                <tr  class="paginate">
                                    <td class="No align-middle text-center text-wrap" style="width:3%;">
                                        {{ $query->id }}
                                    </td>
                                    <td class="Nama align-middle text-center text-wrap">
                                        {{ $query->Model_No }}
                                    </td>
                                    <td class="Deskripsi align-middle text-center text-wrap" style="width:15%;">
                                        {{ $query->Product_Name }}
                                    </td>
                                    <td class="Deskripsi align-middle text-center text-wrap" style="width:15%;">
                                        {{ $query->Brand }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Price }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Serial_no }}
                                    </td>
                                    <td class="Jenis align-middle text-center text-wrap">
                                        {{ $query->Prod_date }}
                                    </td>
                                    <td class="Stok align-middle text-center text-wrap">
                                        {{ $query->Used }}
                                    </td>
{{--                                    <td class="Action text-center text-wrap"  style="width: 5%; height: 5%">--}}
{{--                                        <div class="justify-content-center align-items-center">--}}
{{--                                            <form role="form" method="POST" action={{ route('item-edit') }} enctype="multipart/form-data">--}}
{{--                                                @csrf--}}
{{--                                                <div id='HiddenView' style="display: none;">--}}
{{--                                                    <input class="form-control" type="text" name="postid" value="{{ $query->id }}" >--}}
{{--                                                </div>--}}
{{--                                                <div class="card-header py-0">--}}
{{--                                                    <button type="submit" class="btn btn-primary">Edit</button>--}}
{{--                                                </div>--}}
{{--                                            </form>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div id="page-nav"></div>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer"  style="border-radius: 0 0;">
                        <script>
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
    </div>
@endsection

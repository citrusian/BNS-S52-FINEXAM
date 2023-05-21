@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Item Data'])
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./assets/js/jquery-1.11.2.min.js"></script>
    <script src="./assets/js/simplePagination.js"></script>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Action</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form role="form" method="GET" action={{ route('newtransaction') }} enctype="multipart/form-data">
                        @csrf
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


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive-xl">
                        <table class="table align-items-center table-flush" id="table-id">
                            <thead>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction Id</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Image">Transaction Id</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Nama">Tanggal</th>
                                <th scope="col" class="sort align-middle text-center word-wrap" data-sort="Deskripsi">Jenis</th>
                                <th scope="col" class="sort align-middle text-center text-wrap" data-sort="Jenis">Customer / Vendor</th>
                            </tr>
                            </tfoot>

                            <tbody class="Table">
                            </tbody>
                        </table>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer"  style="border-radius: 0 0;">
                        <script>
                            $("#table-id").simplePagination({
                                perPage: 5,
                                currentPage: 1,
                                previousButtonClass: "btn btn-twitter",
                                nextButtonClass: "btn btn-twitter"
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Transaction Data'])
    <div class="page-header align-items-start pt-5 pb-8 m-3 border-radius-lg">
    </div>
    <div class="container-fluid"style="
     background-image: url('/background/signup-cover.jpg');
     background-position: top;
     height: 100vh;
     background-size: cover;
     background-repeat: no-repeat;">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-md-8">
                <div class="card z-index-0">
                    <div class="card-header text-center pt-4">
                        <h5>Edit Transaksi</h5>
                        <h5>Transaction Number:{{session('postkey')}}</h5>
                    </div>
                        <div id="alert">
                            @include('components.alert')
                        </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaksi-register') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <p class="text-uppercase text-sm">Transaction Data</p>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="Product_id" class="form-control-label">Item Code</label>
                                        <input type="text" name="Product_id" class="form-control" placeholder="APL-4928" aria-label="Product_id" value="{{session('Transaksi_id')}}" readonly>
                                        @error('Product_id') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Product_id" class="form-control-label">Item Code</label>
                                        <input type="text" name="Product_id" class="form-control" placeholder="APL-4928" aria-label="Product_id" value="{{session('Product_id')}}" readonly>
                                        @error('Product_id') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga_Beli" class="form-control-label">Serial Number</label>
                                        <input type="number" name="Serial_no" class="form-control" placeholder="3268204658275" aria-label="Serial_no" value="{{session('Serial_no')}}" readonly>
                                        @error('Serial_no') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
{{--                                                                    ----------------------------}}
                                <hr class="horizontal dark">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Product_Name" class="form-control-label">Product Name</label>
                                        <input type="text" name="Product_Name" class="form-control" placeholder="Zenbook" aria-label="Product_Name" value="{{session('Product_Name')}}" >
                                        @error('Product_Name') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Brand" class="form-control-label">Brand</label>
                                        <input type="text" name="Brand" class="form-control" placeholder="Asus" aria-label="Brand" value="{{session('Brand')}}" >
                                        @error('Brand') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
{{--                                                                    ----------------------------}}
                                <hr class="horizontal dark">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Customer_Vendor" class="form-control-label">Customer / Vendor</label>
{{--                                        fallback to old value if validator failed--}}
                                        <select type="Customer_Vendor" name="Customer_Vendor" class="form-control">
                                            <option value="Customer"  {{ session('Customer_Vendor') == 'Customer' ? 'selected' : '' }}>
                                                Customer
                                            </option>
                                            <option value="Vendor" {{ session('Customer_Vendor') == 'Vendor' ? 'selected' : '' }}>
                                                Vendor
                                            </option>
                                        </select>
                                        @error('Customer_Vendor') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Trans_Type" class="form-control-label">Jenis Transaksi</label>
{{--                                        fallback to old value if validator failed--}}
                                        <select type="Trans_Type" name="Trans_Type" class="form-control" readonly>
                                            <option value="Jual"  {{ session('Trans_Type') == 'Jual' ? 'selected' : '' }}>
                                                Jual
                                            </option>
                                            <option value="Beli" {{ session('Trans_Type') == 'Beli' ? 'selected' : '' }}>
                                                Beli
                                            </option>
                                        </select>
                                        @error('Trans_Type') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
{{--                                                                    ----------------------------}}
                                <hr class="horizontal dark">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Price" class="form-control-label">Harga</label>
                                        <input type="number" name="Price" class="form-control" placeholder="5000000" aria-label="Price" value="{{session('Price')}}" >
                                        @error('Price') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Discount" class="form-control-label">Discount</label>
                                        <input type="number" name="Discount" class="form-control" placeholder="0" aria-label="Discount" value="{{session('Discount')}}" >
                                        @error('Discount') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
{{--                                                                    ----------------------------}}
                                <hr class="horizontal dark">
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Update Transaction</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    </main>--}}
@endsection

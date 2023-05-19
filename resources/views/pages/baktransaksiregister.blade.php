@extends('layouts.app')

@section('content')
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="page-header align-items-start pt-5 pb-11 m-3 border-radius-lg">
    </div>
    <div class="container-fluid"style="
     background-image: url('/background/signup-cover.jpg');
     background-position: top;
     height: 80vh;
     background-size: cover;
     background-repeat: no-repeat;">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            {{--                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">--}}
            <div class="col-md-8">
                <div class="card z-index-0">
                    <div class="card-header text-center pt-4">
                        <h5>Input Barang</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaksicreate') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <p class="text-uppercase text-sm">Item Information</p>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Nama" class="form-control-label">Item Code</label>
                                        <input type="text" name="Nama" class="form-control" placeholder="APL-4928" aria-label="Nama" value="{{ old('Nama') }}" >
                                        @error('Nama') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga_Beli" class="form-control-label">Serial Number</label>
                                        <input type="number" name="Serial_no" class="form-control" placeholder="3268204658275" aria-label="Harga_Beli" value="{{ old('Harga_Beli') }}" >
                                        @error('Harga_Beli') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Jenis" class="form-control-label">Customer / Vendor</label>
                                        {{--                                            fallback to old value if validator failed--}}
                                        <select type="Jenis" name="Jenis" class="form-control">
                                            <option value="Customer"  {{ old('Jenis') == 'Admin' ? 'selected' : '' }}>
                                                Customer
                                            </option>
                                            <option value="Vendor" {{ old('Jenis') == 'Customer' ? 'selected' : '' }}>
                                                Vendor
                                            </option>
                                        </select>
                                        @error('Jenis') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Jenis" class="form-control-label">Jenis Transaksi</label>
                                        {{--                                            fallback to old value if validator failed--}}
                                        <select type="Jenis" name="Jenis" class="form-control">
                                            <option value="Jual"  {{ old('Jenis') == 'Admin' ? 'selected' : '' }}>
                                                Jual
                                            </option>
                                            <option value="Beli" {{ old('Jenis') == 'Customer' ? 'selected' : '' }}>
                                                Beli
                                            </option>
                                        </select>
                                        @error('Jenis') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                {{--                                    ----------------------------}}
                                <hr class="horizontal dark">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga_Beli" class="form-control-label">Harga</label>
                                        <input type="number" name="Harga_Beli" class="form-control" placeholder="5000000" aria-label="Harga_Beli" value="{{ old('Harga_Beli') }}" >
                                        @error('Harga_Beli') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga_Beli" class="form-control-label">Tanggal Tansaksi</label>
                                        <input class="form-control" type="date" name="TTL" value="2023-04-23" id="TTL">
                                        @error('Harga_Beli') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="Harga_Beli" class="form-control-label">Discount %</label>
                                        <input type="number" name="Harga_Beli" class="form-control" placeholder="0" aria-label="Harga_Beli" value="{{ old('Harga_Beli') }}" >
                                        @error('Harga_Beli') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                {{--                                    ----------------------------}}
                                <hr class="horizontal dark">

                                {{--                                    echo "<script>console.log('Debug Objects: " . {{ Session::get('$curid') }} . "' );</script>";--}}

{{--                                <div class="form-check form-check-info text-start">--}}
{{--                                    <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" >--}}
{{--                                    <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                        I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and--}}
{{--                                            Conditions</a>--}}
{{--                                    </label>--}}
{{--                                    @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror--}}
{{--                                </div>--}}
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Add Item</button>
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

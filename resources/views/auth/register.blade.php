@extends('layouts.app')

@section('content')
    <div class="page-header align-items-start pt-5 pb-8 m-3 border-radius-lg">
    </div>
    <div class="container-fluid py-4"style="background-image: url('/background/signup-cover.jpg');
                                            background-position: top;">
        <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
            <div class="col-md-8">
                <div class="card z-index-0">
                    <div class="card-header text-center pt-4">
                        <h5>Register New User</h5>
                    </div>
                    <div id="alert">
                        @include('components.alert')
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register.perform') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <p class="text-uppercase text-sm">User Information</p>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username" class="form-control-label">Username</label>
                                        <input type="text" name="username" class="form-control" placeholder="Username" aria-label="Name" value="{{ old('username') }}" >
                                        @error('username') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="form-control-label">Email address</label>
                                        <input type="email" name="email" class="form-control" placeholder="Email" aria-label="Email" value="{{ old('email') }}" >
                                        @error('email') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password" class="form-control-label">Password</label>
                                        <input type="password" name="password" class="form-control" placeholder="Password" aria-label="Password">
                                        @error('password') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="confirm-password" class="form-control-label">Password Confirmation</label>
                                        <input type="password" name="confirm-password" class="form-control form-control-lg" placeholder="Password Confirmation" aria-label="Password"  >
                                        @error('confirm-password') <p class="text-danger text-xs pt-1"> {{$message}} </p>@enderror
                                    </div>
                                </div>
                                {{--                                    ----------------------------}}
                                <hr class="horizontal dark">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname" class="form-control-label">Firstname</label>
                                        <input type="text" name="firstname" class="form-control" placeholder="Firstname" aria-label="Firstname" value="{{ old('firstname') }}" >
                                        @error('firstname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname" class="form-control-label">Lastname</label>
                                        <input type="text" name="lastname" class="form-control" placeholder="Lastname" aria-label="Lastname" value="{{ old('lastname') }}" >
                                        @error('lastname') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="role" class="form-control-label">Role</label>
                                        {{--                                            fallback to old value if validator failed--}}
                                        <select type="role" name="role" class="form-control">
                                            <option value="0" {{ old('role') == 0 ? 'selected' : '' }}>
                                                Super Admin
                                            </option>
                                            <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>
                                        @error('role') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                {{--                                    ----------------------------}}
                                <hr class="horizontal dark">

                                <p class="text-uppercase text-sm">Contact Information</p>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="form-control-label">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Monumen Nasional, Jalan Tugu Monas, RT.5/RW.2, Gambir, Kecamatan Gambir, Kota Jakarta Pusat" aria-label="Address" value="{{ old('address') }}" >
                                        @error('address') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city" class="form-control-label">City</label>
                                        <input type="text" name="city" class="form-control" placeholder="Jakarta" aria-label="City" value="{{ old('city') }}" >
                                        @error('city') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="country" class="form-control-label">Country</label>
                                        <input type="text" name="country" class="form-control" placeholder="Indonesia" aria-label="Country" value="{{ old('country') }}" >
                                        @error('country') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="postal" class="form-control-label">Zip Code</label>
                                        <input type="tel" maxlength="8"  name="postal" class="form-control" placeholder="10110" aria-label="Zip Code" value="{{ old('postal') }}" >
                                        @error('postal') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
                                    </div>
                                </div>
                                {{--                                    ----------------------------}}
                                <hr class="horizontal dark">
                                <div class="form-check form-check-info text-start">
                                    <input class="form-check-input" type="checkbox" name="terms" id="flexCheckDefault" >
                                    <label class="form-check-label" for="flexCheckDefault">
                                        I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and
                                            Conditions</a>
                                    </label>
                                    @error('terms') <p class='text-danger text-xs'> {{ $message }} </p> @enderror
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">Sign up</button>
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

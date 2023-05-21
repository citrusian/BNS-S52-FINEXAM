@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Your Profile'])
{{--    <div class="card shadow-lg mx-4 card-profile-bottom">--}}
{{--        <div class="card-body p-3">--}}
{{--            <div class="row gx-4">--}}
{{--                <div class="col-auto">--}}
{{--                    <div class="avatar avatar-xl position-relative">--}}
{{--                        <img src="/img/team-1.jpg" alt="profile_image" class="w-100 border-radius-lg shadow-sm">--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-auto my-auto">--}}
{{--                    <div class="h-100">--}}
{{--                        <h5 class="mb-1">--}}
{{--                            {{ auth()->user()->firstname ?? 'Firstname' }} {{ auth()->user()->lastname ?? 'Lastname' }}--}}
{{--                        </h5>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form role="form" method="POST" action={{ route('profile.update') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username" value="{{ old('username', auth()->user()->username) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" >
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email" value="{{ old('email', auth()->user()->email) }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">First name</label>
                                        <input class="form-control" type="text" name="firstname"  value="{{ old('firstname', auth()->user()->firstname) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Last name</label>
                                        <input class="form-control" type="text" name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">Contact Information</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Address</label>
                                        <input class="form-control" type="text" name="address"
                                            value="{{ old('address', auth()->user()->address) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">City</label>
                                        <input class="form-control" type="text" name="city" value="{{ old('city', auth()->user()->city) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Country</label>
                                        <input class="form-control" type="text" name="country" value="{{ old('country', auth()->user()->country) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Postal code</label>
                                        <input class="form-control" type="text" name="postal" value="{{ old('postal', auth()->user()->postal) }}">
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <p class="text-uppercase text-sm">About me</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">About me</label>
                                        <input class="form-control" type="text" name="about"
                                            value="{{ old('about', auth()->user()->about) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile">
                    <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    <img src="/img/profile/{{ Auth::user()->pp_path }}"
                                        class="rounded-circle img-fluid border border-2 border-white">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center">
                        <div class="d-flex justify-content-center" style="">
                            <button type="button" class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block select-button">Select Image</button>
                            <form id="profileForm" role="form" method="POST" action="{{ route('profile_ppicture') }}" enctype="multipart/form-data" style="display: none;">
                                @csrf
                                <label class="form-label" for="inputImage">Selected Image:</label>
                                <input type="file" name="image" id="inputImage" class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <div class="card-header pb-0">
                                    <input type="hidden" name="action" value="register">
                                    <button type="submit" class="btn btn-primary btn-sm ms-auto upload-button">Upload</button>
                                </div>
                            </form>
                        </div>
                    </div>



























                    <div class="card-body pt-0">
                        <div class="text-center mt-4">
                            @csrf
                            <h5>
                                {{ auth()->user()->firstname ?? 'Firstname' }} {{ auth()->user()->lastname ?? 'Lastname' }}
                            </h5>
                            <div class="h6 font-weight-300">
                                {{ auth()->user()->city ?? 'city' }}, {{ auth()->user()->country ?? 'country' }}
                            </div>

                            <hr class="horizontal dark">

                            <div class="col-md-12">
                                <div class="form-group" disabled>
{{--                                    {{ old('role', auth()->user()->role) }}--}}
                                    <label for="example-text-input" class="form-control-label">Role</label>
                                    <select type="role" name="role" class="form-control" disabled="true">
{{--                                        remember to use name=" old name "--}}
                                        <option value="0" {{ old('role', auth()->user()->role) == 0 ? 'selected' : '' }}>
                                            Super Admin
                                        </option>
                                        <option value="1" {{ old('role', auth()->user()->role) == 1 ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    </select>
                                </div>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('layouts.footers.auth.footer-transaksi-view')
    </div>
@endsection

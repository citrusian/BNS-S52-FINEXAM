@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Profile'])
    {{--    <div class="card shadow-lg mx-4 card-profile-bottom">--}}
    {{--    </div>--}}
    <div id="alert">
        @include('components.alert')
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <form role="form" method="POST" action={{ route('updateuser') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <div class="d-flex align-items-center">
                                <p class="mb-0">Edit Profile</p>
                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>
                            </div>
                        </div>
                        <?php
                        $user = DB::table('users')->where('id',session('user'))->get();
                        ?>

                        <div id='HiddenView' style="display: none;">
                            <input class="form-control" type="text" name="postid" value="{{ $user[0]->id }}" >
                        </div>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Information</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Username</label>
                                        <input class="form-control" type="text" name="username" value="{{$user[0]->username}}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" disabled="true">
                                        <label for="example-text-input" class="form-control-label">Email address</label>
                                        <input class="form-control" type="email" name="email" value="{{$user[0]->email}}" disabled="true">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">First name</label>
                                        <input class="form-control" type="text" name="firstname"  value="{{$user[0]->firstname}}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="example-text-input" class="form-control-label">Last name</label>
                                        <input class="form-control" type="text" name="lastname" value="{{$user[0]->lastname}}" >
                                    </div>
                                </div>
                            </div>
                            <hr class="horizontal dark">
                            <div id='Customer_View'>
                                <p class="text-uppercase text-sm">Contact Information</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Address</label>
                                            <input class="form-control" type="text" name="address"
                                                   value="{{$user[0]->address}}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">City</label>
                                            <input class="form-control" type="text" name="city" value="{{$user[0]->city}}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Country</label>
                                            <input class="form-control" type="text" name="country" value="{{$user[0]->country}}" >
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Postal code</label>
                                            <input class="form-control" type="tel" maxlength="8" name="postal" value="{{$user[0]->postal}}" >
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="example-text-input" class="form-control-label">Role</label>
                                            {{--                                            fallback to old value if validator failed--}}
                                            <select type="role" name="role" class="form-control">
                                                <option value="0" {{ old('role', $user[0]->role) == 0 ? 'selected' : '' }}>
                                                    Super Admin
                                                </option>
                                                <option value="1" {{ old('role', $user[0]->role) == 1 ? 'selected' : '' }}>
                                                    Admin
                                                </option>
                                            </select>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </form>
                    <hr class="horizontal dark">
                    <hr class="horizontal dark">
                    <div id='Customer_View2'>
                        <div class="card-body">
                            <p class="text-uppercase text-sm">User Picture</p>
                            <div class="row">
                                <div class="col-sm">
                                    <img src="/img/profile/{{ $user[0]->pp_path }}" width="20%">
                                </div>
                                <div class="col-sm">
                                    <form role="form" method="POST" action="{{ route('updateppicture') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div id='HiddenView' style="display: none;">
                                            <input class="form-control" type="text" name="postid" value="{{ $user[0]->id }}" >
                                        </div>
                                        <label class="form-label" for="inputImage">Select Image:</label>
                                        <input
                                            type="file"
                                            name="image"
                                            id="inputImage"
                                            class="form-control @error('image') is-invalid @enderror">

                                        @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <div class="card-header pb-0">
                                            <button type="submit" class="btn btn-primary btn-sm ms-auto">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-profile">
                    <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                    <img src="/img/profile/{{ $user[0]->pp_path }}"
                                         class="rounded-circle img-fluid border border-2 border-white">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="text-center mt-4">
                            <h5>
                                {{ $user[0]->firstname ?? 'Firstname' }} {{ $user[0]->lastname ?? 'Lastname' }}
                            </h5>
                            <div class="h6 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{ $user[0]->city ?? 'city' }}, {{ $user[0]->country ?? 'country' }}
                            </div>
                        </div>
                    </div>

                    <hr class="horizontal dark">

                    <div class="col-md-12">
                        <div class="form-group" disabled>
                            {{ old('role', $user[0]->role) }}
                            <label for="example-text-input" class="form-control-label">Role</label>
                            <select type="role" name="role" class="form-control" disabled="true">
                                {{--                                        remember to use name=" old name "--}}
                                <option value="0" {{ old('role', $user[0]->role) == 0 ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                                <option value="1" {{ old('role', $user[0]->role) == 1 ? 'selected' : '' }}>
                                    Admin
                                </option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Edit Profile'])
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
{{--                                <button type="submit" class="btn btn-primary btn-sm ms-auto">Save</button>--}}
{{--                                <div class="text-center">--}}
                                    <button type="submit" class="btn btn-sm btn-dark float-right mb-0 ms-auto  popupButton">Update Profile</button>
{{--                                </div>--}}
                            </div>
                        </div>
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

                    {{------------------------------------------------------------------------------------}}
                    {{--Upload PP Button--}}
                    {{------------------------------------------------------------------------------------}}
                    <div class="card-header text-center">
                        <div class="d-flex justify-content-center" style="">
                            <button type="button" class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block select-button">Select Image</button>
                            <form id="profileForm" role="form" method="POST" action="{{ route('updateuser_picture') }}" enctype="multipart/form-data" style="display: none;">
                                @csrf
                                <div id='HiddenView' style="display: none;">
                                    <input class="form-control" type="text" name="postid" value="{{ $user[0]->id }}" data-userid="{{ $user[0]->id }}">
{{--                                    <input class="form-control" type="text" name="postid" value="{{ $user[0]->id }}">--}}
                                </div>

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
                    {{------------------------------------------------------------------------------------}}
                    {{--Name and Role--}}
                    {{------------------------------------------------------------------------------------}}
                    <div class="card-body pt-0">
                        <div class="text-center mt-4">
                            @csrf
                            <h5>
                                {{ $user[0]->firstname ?? 'Firstname' }} {{ $user[0]->lastname ?? 'Lastname' }}
                            </h5>
                            <div class="h6 font-weight-300">
                                {{ $user[0]->city ?? 'city' }}, {{ $user[0]->country ?? 'country' }}
                            </div>

                            <hr class="horizontal dark">

                            <div class="col-md-12">
                                <div class="form-group" disabled>
                                    {{--                                    {{ old('role', auth()->user()->role) }}--}}
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
        </div>
        @include('layouts.footers.auth.footer-profile-management')
    </div>
@endsection

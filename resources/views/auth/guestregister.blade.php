@extends('layouts.app')

@section('content')
    @include('layouts.navbars.guest.navbar')
    <main class="main-content  mt-0" style="background-color:#59565b;">
        <div class="page-header align-items-start pt-5 pb-11 m-3 border-radius-lg"
             style="background-image: url('/background/bg1-compress.jpg');
             background-position: top;
             height: 97vh;
             background-size: cover;
             background-repeat: no-repeat;">
            <span class="mask bg-gradient-dark opacity-9"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-5 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">403 - Unauthorized</h1>
                        <h3 class="text-lead text-white">&nbsp</h3>
                        <h3 class="text-lead text-white">You not supposed to be here.</h3>
                        <h3 class="text-lead text-white">&nbsp</h3>
                        <h3 class="text-lead text-white">Admin Panel Registration is Disabled</h3>
                        <h3 class="text-lead text-white">&nbsp</h3>
                        <h5 class="text-lead text-white">Melihat requirment role adalah Admin & SuperAdmin, maka form registrasi dimatikan.</h5>
                        <h5 class="text-lead text-white">Hanya SuperAdmin yang dapat menahbahkan User melalui form didalam Dashboard</h5>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('layouts.footers.guest.footer')
@endsection

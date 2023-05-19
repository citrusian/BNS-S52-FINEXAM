@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'About'])
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
                    <div class="col-lg-5 mx-auto">
                        <h3 class="text-lead text-white">About</h3>
                        <h3 class="text-lead text-white">&nbsp</h3>
                    </div>
                    <div class="col-lg-5 text-center mx-auto">
                        <h3 class="text-lead text-white">-Credits-</h3>
                    </div>

                    <div class="col-lg-5 mx-auto">
                        <h5 class="text-lead text-white">Website dibuat dengan tujuan ujian Final Exam Binus</h5>
                        <h5 class="text-lead text-white">COMP6621036 – Web Programming</h5>
                        <h5 class="text-lead text-white">&nbsp</h5>
                    </div>

                    <div class="col-lg-5 text-center mx-auto">
                        <h4 class="text-lead text-white">Template:</h4>
                        <h5 class="text-lead text-white">Argon 2</h5>
                        <h5 class="text-lead text-white">
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank" style="color: #0c9ab4">Creative Tim</a>
                            &
                            <a href="https://www.updivision.com" class="font-weight-bold" target="_blank" style="color: #0c9ab4">UPDIVISION</a>

                        </h5>
                        <h3 class="text-lead text-white">&nbsp</h3>
                        <h4 class="text-lead text-white">Script:</h4>
                        <h5 class="text-lead text-white">Simple Pagination</h5>

                        <h5 class="text-lead text-white">
                            <a href="https://github.com/smarulanda/jquery.simplePagination" class="font-weight-bold" target="_blank" style="color: #0c9ab4">Sebastian Marulanda</a>
                            &
                            <a href="https://github.com/enrique26/jquery.simplePagination" class="font-weight-bold" target="_blank" style="color: #0c9ab4">enrique26</a>
                        </h5>


{{--                        <h5 class="text-lead text-white"><a href="https://github.com/smarulanda/jquery.simplePagination" class="font-weight-bold" target="_blank" style="color: #0c9ab4">Sebastian Marulanda</a></h5>--}}
{{--                        <h5 class="text-lead text-white"><a href="https://github.com/enrique26/jquery.simplePagination" class="font-weight-bold" target="_blank" style="color: #0c9ab4">enrique26</a></h5>--}}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

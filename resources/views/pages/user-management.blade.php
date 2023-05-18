@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Item Data'])
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
    <script src="./assets/js/simplePagination.js"></script>
    <div class="row mt-4 mx-4">
        <div class="col-12">

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Customer List</h6>
                </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive-xl">
                        CoUT "{{session('q1')}}"
{{--                            {{$asd}}--}}
                            @foreach ($q1 as $query)
{{--                                <tr>--}}
{{--                                    <td class="No align-middle text-center text-wrap" style="width:3%;">--}}
{{--                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--}}
{{--                                        {{ $query->id }}--}}
{{--                                    </td>--}}
{{--                                    <td class="Nama align-middle text-center text-wrap">--}}
{{--                                        {{ $query->firstname }} {{ $query->lastname }}--}}
{{--                                    </td>--}}
{{--                                </tr>--}}
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

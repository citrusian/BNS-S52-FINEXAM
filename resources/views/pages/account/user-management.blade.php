@extends('layouts.app')

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Item Data'])
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="./assets/js/jquery-1.11.2.min.js"></script>
    <script src="./assets/js/simplePagination.js"></script>
{{--    Assets located at public/assets/js--}}
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Action</h6>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <form role="form" method="GET" action={{ route('create_user') }} enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
{{--                            <div class="card-header">--}}
                            <button type="submit" class="btn btn-primary btn-sm ms-auto">New User</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>User List</h6>
                </div>


                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive-xl">
                        <table class="table align-items-center table-flush" id="table-id">
                            <thead>
                            <tr>
                                <th scope="col" class="sort align-middle text-center " data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Picture">Picture</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Nama">Nama</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Username">Username</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Email">Email</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Address">Address</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Role">Role</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Action">Action</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" class="sort align-middle text-center " data-sort="No">No.</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Picture">Picture</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Nama">Nama</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Username">Username</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Email">Email</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Address">Address</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Role">Role</th>
                                <th scope="col" class="sort align-middle text-center " data-sort="Action">Action</th>
                            </tr>
                            </tfoot>

                            <tbody class="Table">
                            @foreach ($q1 as $query)
                                <tr>
                                    <td class="No align-middle text-center text-wrap" style="width:3%;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ $query->id }}
                                    </td>
                                    <td class="KTP" style="width: 15%; max-height: 20%;">
                                        <img src="img/profile/{{ $query->pp_path }}" width="50%">
                                    </td>
                                    <td class="Nama align-middle text-center text-wrap">
                                        {{ $query->firstname }} {{ $query->lastname }}
                                    </td>
                                    <td class="Gender align-middle text-center text-wrap" style="width:15%;">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        {{ $query->username }}
                                    </td>
                                    <td class="TTL align-middle text-center text-wrap">
                                        {{ $query->email }}
                                    </td>
                                    <td class="Address align-middle text-center text-wrap"  style="width: 10%;">
                                        {{ $query->address }}
                                    </td>
                                    <td class="Type align-middle text-center text-wrap"  style="width: 10%;">
                                        {{ $query->role }}
                                    </td>
                                    <td class="Action align-middle text-center text-wrap"  style="width: 10%;">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            {{--                                            <p class="text-sm font-weight-bold mb-0">Edit</p>--}}
                                            {{--                                            <p class="text-sm font-weight-bold mb-0"><a href="edituser">Edit</a></p>--}}
                                            <form role="form" method="GET" action={{ route('editeuser') }} enctype="multipart/form-data">
                                                @csrf
                                                <div id='HiddenView' style="display: none;">
                                                    <input class="form-control" type="text" name="postid" value="{{ $query->id }}" >
                                                </div>
                                                <div class="card-header pb-0">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </div>

                                            </form>
                                            {{--                                            <p class="text-sm font-weight-bold mb-0 ps-2">Delete</p>--}}
                                            {{--                                            <p class="text-sm font-weight-bold mb-0 ps-2"><a href="edituser">Delete</a></p>--}}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Card footer -->
                    <div class="card-footer"  style="border-radius: 0 0;">
                        <script>
                            $("#table-id").simplePagination({
                                perPage: 5,
                                currentPage: 1,
                                previousButtonClass: "btn btn-primary",
                                nextButtonClass: "btn btn-primary"
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

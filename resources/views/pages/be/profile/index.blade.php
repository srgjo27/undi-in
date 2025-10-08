@extends('layouts.be')

@section('title', 'Profile')

@section('content')
    <div class="container-fluid">

        <div class="profile-foreground position-relative mx-n4 mt-n4">
            <div class="profile-wid-bg">
                <img src="{{ asset('template/be/dist/default/assets/images/profile-bg.jpg') }}" alt=""
                    class="profile-wid-img" />
            </div>
        </div>

        <div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
            <div class="row g-4">
                <div class="col-auto">
                    <div class="avatar-lg">
                        <img src="{{ asset('template/be/dist/default/assets/images/users/avatar-1.jpg') }}" alt="user-img"
                            class="img-thumbnail rounded-circle" />
                    </div>
                </div>
                <div class="col">
                    <div class="p-2">
                        <h3 class="text-white mb-1">{{ Auth::user()->name }}</h3>
                        <p class="text-white-75">{{ Auth::user()->role }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-12">
                <div>
                    <div class="d-flex">
                        <ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
                                    <i class="ri-airplay-fill d-inline-block d-md-none"></i> <span
                                        class="d-none d-md-inline-block">Overview</span>
                                </a>
                            </li>

                        </ul>
                        <div class="flex-shrink-0">
                            <a href="{{ route('profile.edit') }}" class="btn btn-success"><i
                                    class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
                        </div>
                    </div>
                    <div class="tab-content pt-4 text-muted">
                        <div class="tab-pane active" id="overview-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-xxl-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">Info</h5>
                                            <div class="table-responsive">
                                                <table class="table table-borderless mb-0">
                                                    <tbody>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Name :</th>
                                                            <td class="text-muted">{{ Auth::user()->name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">E-mail :</th>
                                                            <td class="text-muted">{{ Auth::user()->email }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Mobile :</th>
                                                            <td class="text-muted">{{ Auth::user()->phone_number }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="ps-0" scope="row">Address :</th>
                                                            <td class="text-muted">{{ Auth::user()->address }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3">About</h5>
                                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod
                                                tempor
                                                incididunt ut labore et dolore magna aliqua.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

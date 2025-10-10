<div class="offcanvas offcanvas-end border-0" tabindex="-1" id="userProfileCanvasExample">
    <!--end offcanvas-header-->
    <div class="offcanvas-body profile-offcanvas p-0">
        <div class="team-cover">
            <img src="{{asset('template/be/dist/default/assets/images/small/img-9.jpg')}}" alt="" class="img-fluid" />
        </div>
        <div class="p-1 pb-4 pt-0">
            <div class="team-settings">
                <div class="row g-0">
                    <div class="col">
                        <div class="btn nav-btn">
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div>
        <div class="p-3 text-center">
            <div class="avatar-lg mx-auto mb-3" id="profileAvatar">
                <div class="avatar-title bg-primary rounded-circle text-white fs-2" id="profileAvatarText">
                    U
                </div>
            </div>
            <div class="mt-3">
                <h5 class="fs-16 mb-1"><a href="javascript:void(0);" class="link-primary username" id="profileUsername">Pilih Percakapan</a></h5>
                <p class="text-muted" id="profileStatus"><i class="ri-checkbox-blank-circle-fill me-1 align-bottom text-success"></i>Online</p>
                <p class="text-muted fs-13" id="profileRole">-</p>
            </div>

            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Message">
                    <span class="avatar-title rounded bg-light text-body">
                        <i class="ri-question-answer-line"></i>
                    </span>
                </button>

                <button type="button" class="btn avatar-xs p-0" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Phone">
                    <span class="avatar-title rounded bg-light text-body">
                        <i class="ri-phone-line"></i>
                    </span>
                </button>

                <div class="dropdown">
                    <button class="btn avatar-xs p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <span class="avatar-title bg-light text-body rounded">
                            <i class="ri-more-fill"></i>
                        </span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                    class="ri-inbox-archive-line align-bottom text-muted me-2"></i>Archive</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                    class="ri-mic-off-line align-bottom text-muted me-2"></i>Muted</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0);"><i
                                    class="ri-delete-bin-5-line align-bottom text-muted me-2"></i>Delete</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-top border-top-dashed p-3">
            <h5 class="fs-15 mb-3">Detail Pengguna</h5>
            <div class="mb-3">
                <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Nomor Telepon</p>
                <h6 id="profilePhone">-</h6>
            </div>
            <div class="mb-3">
                <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Email</p>
                <h6 id="profileEmail">-</h6>
            </div>
            <div class="mb-3">
                <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Role</p>
                <h6 id="profileRoleDetail">-</h6>
            </div>
            <div>
                <p class="text-muted text-uppercase fw-medium fs-12 mb-1">Alamat</p>
                <h6 class="mb-0" id="profileAddress">-</h6>
            </div>
        </div>
    </div><!--end offcanvas-body-->
</div><!--end offcanvas-->

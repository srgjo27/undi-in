<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <div class="navbar-brand-box horizontal-logo">
                    @if (Auth::check())
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="logo logo-dark">
                            @else
                                <a href="{{ route('seller.dashboard') }}" class="logo logo-dark">
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="logo logo-dark">
                    @endif
                    <span class="logo-sm">
                        <img src="{{ asset('template/be/dist/default/assets/images/logo-sm.png') }}" alt=""
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('template/be/dist/default/assets/images/logo-dark.png') }}" alt=""
                            height="17">
                    </span>
                    </a>

                    @if (Auth::check())
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="logo logo-light">
                            @else
                                <a href="{{ route('seller.dashboard') }}" class="logo logo-light">
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="logo logo-light">
                    @endif
                    <span class="logo-sm">
                        <img src="{{ asset('template/be/dist/default/assets/images/logo-sm.png') }}" alt=""
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('template/be/dist/default/assets/images/logo-light.png') }}" alt=""
                            height="17">
                    </span>
                    </a>
                </div>
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                    id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
            <div class="d-flex align-items-center">
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        data-toggle="fullscreen">
                        <i class='las la-window-maximize fs-22'></i>
                    </button>
                </div>
                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button"
                        class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">
                        <i class='las la-moon fs-22'></i>
                    </button>
                </div>
                
                <!-- Messages Notification -->
                <div class="ms-1 header-item">
                    <a href="{{ route('messages.index') }}" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle position-relative">
                        <i class='las la-envelope fs-22'></i>
                        @php
                            $messageCount = Auth::user()->getTotalUnreadMessages();
                        @endphp
                        @if($messageCount > 0)
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-success">
                            {{ $messageCount > 9 ? '9+' : $messageCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                        @endif
                    </a>
                </div>
                
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class='las la-bell fs-22'></i>
                        @php
                            $unreadCount = Auth::user()->getTotalUnreadMessages();
                        @endphp
                        @if($unreadCount > 0)
                        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                            <span class="visually-hidden">unread messages</span>
                        </span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                        aria-labelledby="page-header-notifications-dropdown">
                        <div class="dropdown-head bg-primary bg-pattern rounded-top">
                            <div class="p-3">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h6 class="m-0 fs-16 fw-semibold text-white">Notifikasi</h6>
                                    </div>
                                    <div class="col-auto dropdown-tabs">
                                        @if($unreadCount > 0)
                                        <span class="badge badge-soft-light fs-13">{{ $unreadCount }} Baru</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-content position-relative" id="notificationItemsTabContent">
                            <div class="tab-pane fade show active" id="messages-tab" role="tabpanel">
                                @php
                                    try {
                                        $recentConversations = Auth::user()->conversations()
                                            ->with(['latestMessage.sender', 'activeParticipants'])
                                            ->whereHas('messages', function($q) {
                                                $q->where('sender_id', '!=', Auth::id());
                                            })
                                            ->orderBy('last_activity', 'desc')
                                            ->limit(5)
                                            ->get();
                                    } catch (\Exception $e) {
                                        $recentConversations = collect();
                                    }
                                @endphp
                                
                                @if($recentConversations->count() > 0)
                                    <div class="p-2" data-simplebar style="max-height: 350px;">
                                        @foreach($recentConversations as $conversation)
                                            @php
                                                $otherUser = $conversation->activeParticipants->where('id', '!=', Auth::id())->first();
                                                $latestMessage = $conversation->latestMessage;
                                                $unreadConvCount = $conversation->getUnreadCount(Auth::id());
                                            @endphp
                                            @if($otherUser && $latestMessage)
                                            <div class="text-reset notification-item d-block dropdown-item position-relative {{ $unreadConvCount > 0 ? 'bg-soft-primary' : '' }}">
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar-xs">
                                                            <div class="avatar-title bg-primary rounded-circle text-white">
                                                                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                                            </div>
                                                        </div>
                                                        @if($unreadConvCount > 0)
                                                        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-light rounded-circle">
                                                            <span class="visually-hidden">New message</span>
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <a href="{{ route('messages.index') }}" class="stretched-link text-decoration-none">
                                                            <h6 class="mt-0 mb-1 fs-14 fw-semibold text-dark">{{ $otherUser->name }}</h6>
                                                            <div class="fs-13 text-muted">
                                                                <p class="mb-1 text-truncate" style="max-width: 180px;">
                                                                    @if($latestMessage->sender_id == Auth::id())
                                                                        <i class="ri-reply-line me-1 text-primary"></i><span class="text-primary">Anda:</span> 
                                                                    @else
                                                                        <i class="ri-message-3-line me-1 text-success"></i>
                                                                    @endif
                                                                    {{ Str::limit($latestMessage->content, 40) }}
                                                                </p>
                                                            </div>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <p class="mb-0 fs-11 fw-medium text-muted">
                                                                    <i class="mdi mdi-clock-outline me-1"></i>{{ $latestMessage->created_at->diffForHumans() }}
                                                                </p>
                                                                @if($unreadConvCount > 0)
                                                                    <span class="badge bg-danger">{{ $unreadConvCount }}</span>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <div class="p-2 border-top d-grid">
                                        <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('messages.index') }}">
                                            <i class="mdi mdi-arrow-right-circle me-1"></i> 
                                            <span>Lihat Semua Pesan</span>
                                        </a>
                                    </div>
                                @else
                                    <div class="tab-pane-content p-4">
                                        <div class="w-25 w-sm-50 pt-3 mx-auto">
                                            <img src="{{ asset('template/be/dist/default/assets/images/svg/bell.svg') }}"
                                                class="img-fluid" alt="no-notifications">
                                        </div>
                                        <div class="text-center pb-5 mt-2">
                                            <h6 class="fs-18 fw-semibold lh-base">Belum ada notifikasi</h6>
                                            <p class="text-muted fs-15">Pesan baru akan muncul di sini</p>
                                            <a href="{{ route('messages.index') }}" class="btn btn-primary btn-sm">
                                                <i class="ri-message-line me-1"></i> Buka Pesan
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('template/be/dist/default/assets/images/users/avatar-1.jpg') }}"
                                alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user()->name }}</span>
                                <span
                                    class="d-none d-xl-block ms-1 fs-12 text-muted user-name-sub-text">{{ ucfirst(Auth::user()->role) }}</span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <h6 class="dropdown-header">Welcome {{ Auth::user()->name }}!</h6>
                        <a class="dropdown-item" href="{{ route('profile') }}"><i
                                class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                                class="align-middle">Profile</span></a>
                        <a class="dropdown-item" href="{{ route('messages.index') }}"><i
                                class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i>
                            <span class="align-middle">Messages</span></a>
                        <form id="logout-form" action="{{ route('logout') }}" method="post" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item"
                                style="border: none; background: none; width: 100%; text-align: left;">
                                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>
                                <span class="align-middle" data-key="t-logout">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

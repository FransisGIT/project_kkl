<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <h5 class="mb-0">
                        <span id="tanggal"></span>
                        <span id="jam" class="ms-2"></span>
                    </h5>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">


                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>


                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" id="light-dark-mode">
                        <i data-feather="moon" class="align-middle dark-mode"></i>
                        <i data-feather="sun" class="align-middle light-mode"></i>
                    </button>
                </li>


                <li class="dropdown notification-list topbar-dropdown me-2">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button"
                        aria-haspopup="false" aria-expanded="false">
                        <i data-feather="users" class="noti-icon"></i>
                        <span class="d-none d-md-inline ms-1">{{ Auth::user()->role->name }}</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <div class="dropdown-header">
                            <h6 class="text-overflow m-0">Ganti Peran</h6>
                        </div>
                        <div class="dropdown-divider"></div>

                        @foreach ($roles as $role)
                            <a href="#"
                                class="dropdown-item {{ Auth::user()->id_role == $role->id_role ? 'active' : '' }}"
                                onclick="event.preventDefault(); document.getElementById('switch-role-{{ $role->id_role }}').submit();">
                                <i class="mdi mdi-account-circle me-1"></i>
                                <span>{{ $role->name }}</span>
                                @if (Auth::user()->id_role == $role->id_role)
                                    <i class="mdi mdi-check float-end"></i>
                                @endif
                            </a>
                            <form id="switch-role-{{ $role->id_role }}" action="{{ route('switch-role') }}"
                                method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="role_id" value="{{ $role->id_role }}">
                            </form>
                        @endforeach
                    </div>
                </li>


                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="{{ asset('assets/images/person-2.png') }}" alt="user-image" class="rounded-circle" />
                        {{-- <span class="pro-user-name ms-1">
                            {{ Auth::user()->name }}
                            <i class="mdi mdi-chevron-down"></i>
                        </span> --}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">

                        <div class="dropdown-header noti-title">
                            <h5 class="text-overflow m-0">
                                Informasi Akun
                            </h5>
                            <div class="mt-3">
                                <h6 class="fs-6"><strong>Nama:</strong> {{ Auth::user()->name }}</h6>
                                <h6 class="fs-6"><strong>Peran:</strong> {{ Auth::user()->role->name }}</h6>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>


                        <a href="#" class="dropdown-item notify-item"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                            <span>
                                Keluar
                            </span>
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
    @csrf
</form>

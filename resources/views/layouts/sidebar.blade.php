<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a href="" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-mandala.png') }}" alt="Logo" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-mandala.png') }}" alt="Logo" height="45">
                    </span>
                </a>
                {{-- <a href="" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="" alt="" height="45">
                    </span>
                </a> --}}
                <a href="" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-mandala.png') }}" alt="Logo" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-mandala.png') }}" alt="Logo" height="45">
                    </span>
                </a>
            </div>

            <ul id="side-menu" class="my-4">
                {{-- Menu untuk Mahasiswa (id_role = 3) --}}
                @if (Auth::user()->id_role == 3)
                    <li>
                        <a href="{{ route('beranda.index') }}"
                            class="{{ request()->routeIs('beranda.index') ? 'active' : '' }}">
                            <span class="mdi mdi-view-dashboard-outline fs-18 me-1"></span>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('krs.index') }}"
                            class="{{ request()->routeIs('krs.index') ? 'active' : '' }}">
                            <span class="mdi mdi-file-document-outline fs-18 me-1"></span>
                            <span>KRS</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('jadwal-kuliah.index') }}"
                            class="{{ request()->routeIs('jadwal-kuliah.index') ? 'active' : '' }}">
                            <span class="mdi mdi-calendar-clock fs-18 me-1"></span>
                            <span>Jadwal Kuliah</span>
                        </a>
                    </li>
                @endif

                @if (in_array(Auth::user()->id_role, [1, 2]))
                    <li>
                        <a href="{{ route('persetujuan-krs.index') }}"
                            class="{{ request()->routeIs('persetujuan-krs.*') ? 'active' : '' }}">
                            <span class="mdi mdi-clipboard-check-outline fs-18 me-1"></span>
                            <span>Persetujuan KRS</span>
                        </a>
                    </li>
                @endif
                @if (in_array(Auth::user()->id_role, [1 , 3, 4, 5]))
                <li>
                    <a href="{{ route('dispensasi.index') }}" class="tp-link">
                        <span class="mdi mdi-view-dashboard-outline fs-18 me-1"></span>
                        <span>Dispensasi</span>
                    </a>
                </li>
                @endif
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>

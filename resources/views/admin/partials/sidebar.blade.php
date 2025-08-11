<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em></a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu">
                <em class="icon ni ni-menu"></em></a>
        </div>
        <div class="nk-sidebar-brand">
            <a href="{{ url('dashboard/app') }}" class="logo-link">
                <h4 class="erp">Dashboard</h4>
                <span>School Task</span>
            </a>
        </div>
    </div>

    <div class="nk-sidebar-element nk-sidebar-body">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                @php $role = auth()->user()->role ?? null; @endphp
                <ul class="nk-menu">
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Dasbor</h6>
                    </li>

                    <li class="nk-menu-item ">
                        <a href="{{ route('dashboard') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-home"></em></span>
                            <span class="nk-menu-text">Ikhtisar</span>
                        </a>
                    </li>

                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Kelas</h6>
                    </li>

                    <li
                        class="nk-menu-item ">
                        <a href="{{ route('classes.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-book"></em></span>
                            <span class="nk-menu-text">Semua Kelas</span>
                        </a>
                    </li>

                    @if ($role === 'teacher')
                        <li
                            class="nk-menu-item ">
                            <a href="{{ route('classes.create') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-plus-circle"></em></span>
                                <span class="nk-menu-text">Buat Kelas</span>
                            </a>
                        </li>
                    @endif
                    @if (($role ?? (auth()->user()->role ?? null)) === 'teacher')
                        <li class="nk-menu-heading">
                            <h6 class="overline-title text-primary-alt">Manajemen Pengguna</h6>
                        </li>

                        {{-- Siswa --}}
                        <li class="nk-menu-item ">
                            <a href="{{ route('students.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">Siswa</span>
                            </a>
                        </li>

                        <li
                            class="nk-menu-item">
                            <a href="{{ route('teachers.index') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user-list"></em></span>
                                <span class="nk-menu-text">Guru</span>
                            </a>
                        </li>

                        <li
                            class="nk-menu-item ">
                            <a href="{{ route('teachers.create') }}" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-plus-circle"></em></span>
                                <span class="nk-menu-text">Tambah Guru</span>
                            </a>
                        </li>
                    @endif
                    <li class="nk-menu-heading">
                        <h6 class="overline-title text-primary-alt">Akun</h6>
                    </li>
                    <li class="nk-menu-item">
                        <form action="{{ route('logout') }}" method="POST" class="nk-menu-link p-0">
                            @csrf
                            <button type="submit" class="nk-menu-link w-100 text-start"
                                style="background:none;border:0;">
                                <span class="nk-menu-icon"><em class="icon ni ni-signout"></em></span>
                                <span class="nk-menu-text">Keluar</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div><!-- .nk-sidebar-menu -->
        </div>
    </div>
</div>

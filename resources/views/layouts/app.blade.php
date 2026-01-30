<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>si-SDMO</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        :root {
            --navbar-height: 56px;
        }

body {
            margin: 0;
        }

        /* NAVBAR */
        .navbar-fixed {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            z-index: 1030;
            background: linear-gradient(135deg, #1a3fa4, #0a1844) !important;
        }

.navbar-logo {
    height: 32px;      /* KUNCI TINGGI LOGO */
    width: auto;
    max-height: 32px;
    object-fit: contain;
}

/* ===== NAVBAR MENU ===== */
.navbar .nav-link {
    color: rgba(255, 255, 255, 0.85) !important; /* putih lebih tegas */
    font-weight: 500;
    transition: all 0.3s ease;
}

/* Hover */
.navbar .nav-link:hover {
    color: #ffffff !important;
    font-weight: 600;
}

/* Menu aktif */
.navbar .nav-link.active {
    color: #f4f8aa !important;
    font-weight: 700;               /* BOLD */
    position: relative;
}

/* Garis bawah menu aktif (opsional tapi cakep) */
.navbar .nav-link.active::after {
    content: '';
    position: absolute;
    bottom: -6px;
    left: 0;
    width: 100%;
    height: 3px;
    background-color: #aab7f8;
    border-radius: 2px;
}

        
    </style>
</head>


<body>
<!-- ================= NAVBAR (SELALU DI ATAS) ================= -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-fixed">
    <div class="container-fluid">

        <!-- LOGO -->
	<a class="navbar-brand d-flex align-items-center" href="#">
    <img src="/logo e-sdmo 2.png" alt="e-SDMO" class="navbar-logo">
 </a>


        <!-- TOGGLER (mobile) -->
       <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav">

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}" href="{{ route('pegawai.index') }}">Data Pegawai</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('career-map.*', 'ak.*') ? 'active' : '' }}" href="{{ route('career-map.index') }}">Pengembangan Karier</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('kompetensi.*') ? 'active' : '' }}"
			href="{{ route('kompetensi.index') }}">Pengembangan Kompetensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('informasi.jf') ? 'active' : '' }}" href="{{ route('informasi.jf') }}">Informasi JF</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('sotk') ? 'active' : '' }}" href="{{ route('sotk') }}">Organisasi</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('iku-sdmo', 'cascading.kinerja', 'pic-jabatan-fungsional') ? 'active' : '' }}" href="#" id="monitoringKinerjaDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Monitoring Kinerja
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="monitoringKinerjaDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('iku-sdmo') }}">IKU SDMO</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('cascading.kinerja') }}">Cascading Kinerja</a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('pic-jabatan-fungsional') }}">PIC Jabatan Fungsional</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('halo.sdmo') ? 'active' : '' }}" href="{{ route('halo.sdmo') }}">Halo SDMO</a>
                </li>

                @auth
                @endauth

            </ul>
        </div>

    </div>
    <ul class="navbar-nav ms-auto align-items-center">

    {{-- BELUM LOGIN --}}
    @guest
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
                Login
            </a>
        </li>
    @endguest

    {{-- SUDAH LOGIN --}}
    @auth
        @if(auth()->user()->isAdmin())
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Setting
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                    <li><a class="dropdown-item" href="{{ route('users.index') }}">List Users</a></li>
                    <li><a class="dropdown-item" href="{{ route('users.create') }}">Create User</a></li>
                </ul>
            </li>
        @endif
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#"
               role="button" data-bs-toggle="dropdown">
                {{ auth()->user()->name }}
            </a>

            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="dropdown-item text-danger">
                            Logout
                        </button>
                    </form>
                </li>
            </ul>
        </li>
    @endauth

</ul>
</nav>

<!-- ================= SIDEBAR ================= -->
<div class="sidebar-left">
    @yield('sidebar')
</div>

<!-- ================= CONTENT ================= -->
<div class="content-wrapper">
    @yield('content')
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@stack('scripts')
</body>
</html>


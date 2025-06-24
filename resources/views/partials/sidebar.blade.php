<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-wifi"></i>
        </div>
        <div class="sidebar-brand-text mx-3">WiFi RW 01</div>
    </a>

    <hr class="sidebar-divider my-0">

    <!-- Dashboard / Tagihan -->
    <li class="nav-item active">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a class="nav-link" href="{{ url('/dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        @elseif(auth()->check() && auth()->user()->role === 'user')
            <a class="nav-link" href="{{ url('/user/dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Tagihan</span>
            </a>
        @endif
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(auth()->check() && auth()->user()->role === 'admin')
        <!-- Menu Admin -->
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/users') }}">
                <i class="fas fa-users"></i>
                <span>Data User</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/transaksi') }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Laporan Transaksi</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ url('/tagihan') }}">
                <i class="fas fa-calendar-plus"></i>
                <span>Tagihan Bulanan</span>
            </a>
        </li>
    @else
        <!-- Menu User -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('user.riwayat') }}">
                <i class="fas fa-history"></i>
                <span>Riwayat Pembayaran</span>
            </a>
        </li>
    @endif

    <!-- Logout -->
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-white"
                style="background: none; border: none; width: 100%; text-align: left;">
                <i class="fas fa-sign-out-alt"></i>
                <span style="margin-left: 0.5rem;">Keluar</span>
            </button>
        </form>
    </li>

</ul>
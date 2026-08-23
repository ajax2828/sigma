<nav id="navmenu" class="navmenu">
    <ul>
        <li><a href="{{ route('admin') }}" class="{{ request()->routeIs('admin') ? 'active' : '' }}">Admin</a>
        </li>
        <li><a href="{{ route('scan') }}" class="{{ request()->routeIs('scan') ? 'active' : '' }}">Scan
                Tiket Front</a></li>
        <li><a href="{{ route('scan.back') }}" class="{{ request()->routeIs('scan.back') ? 'active' : '' }}">Scan
                Tiket Back</a></li>
        <li><a href="{{ route('registrasi') }}" class="{{ request()->routeIs('registrasi') ? 'active' : '' }}">Form
                Registrasi</a></li>
        <li><a href="{{ route('registrasi.Seminar.import.show') }}"
                class="{{ request()->routeIs('registrasi.Seminar.import.show') ? 'active' : '' }}">Import
                Registrasi</a></li>
        <li><a href="{{ route('send.certificate') }}"
                class="{{ request()->routeIs('send.certificate') ? 'active' : '' }}">Kirim
                Sertifikat</a></li>
        <li>
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="btn-getstarted">
                    <span>Logout</span>
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </li>
    </ul>
    <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
</nav>

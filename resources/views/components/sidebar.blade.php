<ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon">
            <img src="{{ asset(setting('logo') ? '/storage/' . setting('logo') : 'dist/img/logo/LogoOmega1.png') }}">
        </div>
        <div class="sidebar-brand-text mx-3">Inventory</div>
    </a>
    <hr class="sidebar-divider my-0">

    <x-nav-link text="Dashboard" icon="tachometer-alt" url="{{ route('admin.dashboard') }}"
        active="{{ request()->routeIs('admin.dashboard') ? ' active' : '' }}" />

    @can('member-list')
        <x-nav-link text="Daftar Petugas" icon="users" url="{{ route('admin.member') }}"
            active="{{ request()->routeIs('admin.member') ? ' active' : '' }}" />
    @endcan

    <hr class="sidebar-divider mb-0">

    @can('gudang')
        <x-nav-link text="Daftar Gudang" icon="th" url="{{ route('admin.gudang.index') }}"
            active="{{ request()->routeIs('admin.gudang.index') ? ' active' : '' }}" />
    @endcan

    @can('kategori')
        <x-nav-link text="Daftar Kategori Barang" icon="box" url="{{ route('admin.kategori.index') }}"
            active="{{ request()->routeIs('admin.kategori.index') ? ' active' : '' }}" />
    @endcan


    @can('barang')
        <x-nav-link text="Daftar Barang" icon="box" url="{{ route('admin.barang.index') }}"
            active="{{ request()->routeIs('admin.barang.index') ? ' active' : '' }}" />
            @endcan

    @can('barang-masuk')
        <x-nav-link text="Barang Masuk" icon="sign-in-alt" url="{{ route('admin.barang-masuk.index') }}"
            active="{{ request()->routeIs('admin.barang-masuk.index') ? ' active' : '' }}" />
        @endcan

    @can('barang-keluar')
        <x-nav-link text="Barang Keluar" icon="sign-out-alt" url="{{ route('admin.barang-keluar.index') }}"
            active="{{ request()->routeIs('admin.barang-keluar.index') ? ' active' : '' }}" />
        @endcan

    @can('supplier')
        <x-nav-link text="Daftar Supplier" icon="users" url="{{ route('admin.supplier.index') }}"
            active="{{ request()->routeIs('admin.supplier.index') ? ' active' : '' }}" />

        <hr class="sidebar-divider mb-0">
    @endcan

    @can('riwayat')
        <x-nav-link text="Riwayat Stok" icon="box" url="{{ route('admin.riwayat.index') }}"
            active="{{ request()->routeIs('admin.riwayat.index') ? ' active' : '' }}" />
    @endcan

    @can('proyek')
        <x-nav-link text="Daftar Kebutuhan Proyek" icon="th" url="{{ route('admin.proyek.index') }}"
            active="{{ request()->routeIs('admin.proyek.index') ? ' active' : '' }}" />
    @endcan

    <x-nav-link text="Laporan" icon="file" url="{{ route('admin.laporan.index') }}"
        active="{{ request()->routeIs('admin.laporan.index') ? ' active' : '' }}" />

    @if (Auth::user()->roles->name = 'Estimator')
    <x-nav-link text="Laporan Proyek" icon="file" url="{{ route('admin.laporan.estimator') }}"
    active="{{ request()->routeIs('admin.laporan.estimator') ? ' active' : '' }}" />
    @endif
</ul>

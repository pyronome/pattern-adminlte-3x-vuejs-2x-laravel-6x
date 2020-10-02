    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{ $customization['main-header'] }}">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Widget Configuration -->
                <li class="nav-item dropdown">
                    <a id="buttonWidgetConfig" class="nav-link show_by_permission_must_update" href="javascript:void(0);">
                        <i class="fas fa-palette nav-icon"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Main Sidebar Container -->
        <aside id="mainMenuVueApplication" class="main-sidebar {{ $customization['main-sidebar'] }} elevation-4">
            <!-- Brand Logo -->
            <router-link to="/{{ config('adminlte.main_folder') }}/home"
                    class="brand-link {{ $customization['brand-link'] }}">
                <img src="{{ $brand['logo']}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ $brand['name']}}</span>
            </router-link>
            <router-link to="/{{ config('adminlte.main_folder') }}/profile/detail"
                    class="brand-link">
                <img src="{{ $user['image'] }}" alt="User Image" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ $user['name'] }}</span>
            </router-link>

            <!-- Sidebar -->
            <div class="sidebar">
                @include('adminlte.divmenulayer')
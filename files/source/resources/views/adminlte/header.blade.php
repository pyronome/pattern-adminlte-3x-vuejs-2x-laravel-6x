
	<div class="wrapper">
    	<!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-{{ config('adminlte.navbar_light_dark') }} navbar-{{ config('adminlte.navbar_background_color') }}">
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
                    <a id="buttonWidgetConfig" class="nav-link" href="javascript:void(0);" style="display:none;">
                        <i class="fas fa-palette nav-icon"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- Main Sidebar Container -->
        <aside id="mainMenuApp" class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <router-link :to="/{{ config('adminlte.main_folder') }}/home" class="brand-link">
                <img src="/img/adminlte/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">AdminLTE 3</span>
            </router-link>
            <router-link :to="/{{ config('adminlte.main_folder') }}/profile/detail" class="brand-link">
                <img src="{{ $user['image'] }}" alt="User Image" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ $user['name'] }}</span>
            </router-link>

            <!-- Sidebar -->
            <div class="sidebar">
                @include('adminlte.divmenulayer')
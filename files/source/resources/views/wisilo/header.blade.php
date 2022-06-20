        <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand {{ $customization['navbar'] }}">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li>
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                @if ($user['impersonated'])
                    <li class="nav-item dropdown">
                        <button type="button" 
                            id="buttonTopbarImpersonated" 
                            class="btn btn-block btn-info btn-md impersonated-info"
                            data-toggle="tooltip" data-placement="bottom" title="{{ $user['wisilousergroup_title'] }} / {{ $user['name'] }}">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </button>
                    </li>
                @endif
                <!-- Widget Configuration -->
                @if ($user['admin'])
                <li class="nav-item dropdown" style="margin-left:10px;">
                    <button type="button" id="btnToggleEditMode" class="btn btn-block btn-default btn-md" on-edit-mode="0">
                        <i class="fas fa-lock nav-icon"></i><i class="fas fa-unlock nav-icon"></i> <span>{{ __('Edit Mode') }}</span>
                    </button>
                </li>
                <li class="nav-item dropdown show-on-edit-mode d-none" style="margin-left:10px;">
                    <button type="button" id="btnAddNewWidgets" class="btn btn-block btn-primary btn-md">
                        <i class="fas fa-plus nav-icon"></i>
                    </button>
                </li>
                <li class="nav-item dropdown show-on-edit-mode d-none" style="margin-left:10px;">
                    <button type="button" id="btnSaveWidgets" class="btn btn-block btn-default btn-md">
                        {{ __('Save')}}
                    </button>
                </li>
                @endif
            </ul>
        </nav>
        <!-- Main Sidebar Container -->
        <aside id="mainMenuVueApplication" class="main-sidebar {{ $customization['main-sidebar'] }} elevation-4">
            <!-- Brand Logo -->
            <router-link to="/{{ $main_folder }}/home" class="brand-link {{ $customization['brand'] }}">
                <img src="{{ $brand['logo']}}" alt="Wisilo Logo" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">{{ $brand['name']}}</span>
            </router-link>
            <router-link to="/{{ $main_folder }}/profile/detail" class="brand-link">
                <img src="{{ $user['image'] }}" alt="User Image" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">{{ $user['name'] }}</span>
            </router-link>
            <!-- Sidebar -->
            <div class="sidebar">
                @include('wisilo.divmenulayer')
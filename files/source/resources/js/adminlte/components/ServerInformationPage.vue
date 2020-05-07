<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">
                            {{ $t('Server Information') }}
                        </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/{{ AdminLTEHelper.getMainFolder }}/home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item enabled">{{ $t('Server Information') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content htmldb-section" data-htmldb-table="ServerInformationHTMLDB">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="card card-solid">
                            <div class="card-body pb-0">
                                <div class="row d-flex align-items-stretch">
                                    <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                        <div class="card bg-light server_info_card">
                                            <div class="card-header text-muted border-bottom-0">
                                                {{ $t('Operating System') }}
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b>{{OS_header}}</b></h2>
                                                        <p class="text-muted text-sm">{{OS_detail}}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img data-htmldb-content="/assets/adminlte/img/{{OS_icon_src}}" src="" alt="" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                        <div class="card bg-light server_info_card">
                                            <div class="card-header text-muted border-bottom-0">
                                                {{ $t('Web Server') }}
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b>{{WEB_header}}</b></h2>
                                                        <p class="text-muted text-sm">{{WEB_detail}}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img data-htmldb-content="/assets/adminlte/img/{{WEB_icon_src}}" src="" alt="" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                        <div class="card bg-light server_info_card">
                                            <div class="card-header text-muted border-bottom-0">
                                                {{ $t('Application Server') }}
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b>{{APP_header}}</b></h2>
                                                        <p class="text-muted text-sm">{{APP_detail}}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img data-htmldb-content="/assets/adminlte/img/{{APP_icon_src}}" src="" alt="" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6 d-flex align-items-stretch">
                                        <div class="card bg-light server_info_card">
                                            <div class="card-header text-muted border-bottom-0">
                                                {{ $t('Database Server') }}
                                            </div>
                                            <div class="card-body pt-0">
                                                <div class="row">
                                                    <div class="col-7">
                                                        <h2 class="lead"><b>{{DB_header}}</b></h2>
                                                        <p class="text-muted text-sm">{{DB_detail}}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img data-htmldb-content="/assets/adminlte/img/{{DB_icon_src}}" alt="" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return [

        ];
    },
    mounted() {
        AdminLTEHelper.initializeSideMenu();
    }
}
</script>
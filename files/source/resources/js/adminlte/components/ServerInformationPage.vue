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
                            <li class="breadcrumb-item"><router-link to="home">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item enabled">{{ $t('Server Information') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content" v-show="page.ready">
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
                                                        <h2 class="lead"><b>{{ server_information.os_header }}</b></h2>
                                                        <p class="text-muted text-sm">{{ server_information.os_detail }}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img v-bind:src="server_information.os_icon_src" alt="" class="img-fluid">
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
                                                        <h2 class="lead"><b>{{ server_information.web_header }}</b></h2>
                                                        <p class="text-muted text-sm">{{ server_information.web_detail }}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img v-bind:src="server_information.web_icon_src" alt="" class="img-fluid">
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
                                                        <h2 class="lead"><b>{{ server_information.app_header }}</b></h2>
                                                        <p class="text-muted text-sm">{{ server_information.app_detail }}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img v-bind:src="server_information.app_icon_src" alt="" class="img-fluid">
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
                                                        <h2 class="lead"><b>{{ server_information.db_header }}</b></h2>
                                                        <p class="text-muted text-sm">{{ server_information.db_detail }}</p>
                                                    </div>
                                                    <div class="col-5 text-right">
                                                        <img v-bind:src="server_information.db_icon_src" alt="" class="img-fluid">
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
        return {
            server_information: {
                "os_header": "",
                "os_detail": "",
                "os_icon_src": "",
                "web_header": "",
                "web_detail": "",
                "web_icon_src": "",
                "app_header": "",
                "app_detail": "",
                "app_icon_src": "",
                "db_header": "",
                "db_detail": "",
                "db_icon_src": ""
            },
            page: {
                ready: false
            }
        };
    },
    methods: {
        loadData: function () {
            this.$Progress.start();
            axios.get(AdminLTEHelper.getAPIURL("server_information"))
                    .then(({ data }) => {
                        this.$Progress.finish();
                        this.server_information = data;
                        this.page.ready = true;
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                        this.page.ready = true;
                    });
        }
    },
    mounted() {
        this.page.ready = false;
        this.loadData();
    }
}
</script>
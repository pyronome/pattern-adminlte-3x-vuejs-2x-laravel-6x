<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("AdminLTE User Group Detail") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteusergroup'">{{ $t("User Group List") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("User Group Detail") }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="card">
                            <div class="card-header show_by_permission_must_update">
                                <div class="card-tools">
                                    <router-link tag="a"
                                        class="btn btn-primary btn-xs btn-on-table text-white sbp-item"
                                        menu-permission-token="adminlteusergroup"
                                        model-permission-token="AdminLTEUserGroup-update"
                                        :to="'/' + main_folder + '/adminlteusergroup/edit/' + id">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
                                    </router-link>
                                    <router-link tag="a"
                                        class="btn btn-primary btn-xs btn-on-table text-white sbp-item"
                                        menu-permission-token="adminlteusergroup"
                                        model-permission-token="AdminLTEUserGroup-update"
                                        :to="'/' + main_folder + '/adminlteusergroup/permission/' + id">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Permissions') }}</span>
                                    </router-link>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
									<div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Enabled') }}</label>
                                            <div v-html="data.enabled__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Admin') }}</label>
                                            <div v-html="data.admin__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Widget Edit Permission') }}</label>
                                            <div v-html="data.widget_permission__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Title') }}</label>
                                            <div v-html="data.title__displaytext__"></div>
                                        </div>                                        
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <input type="hidden" id="controller" value="adminlteusergroup">
        <page-variables :has_widgets="false"></page-variables>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            id: 0,
            data: [],
            page: {
                is_data_loading: false,
                is_data_loaded: false,
            }
        };
    },
    methods: {
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
			
			var self = this;

            axios.get(AdminLTEHelper.getAPIURL("adminlteusergroup/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.data = data.list;
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                }).finally(function() {
                    
                });
        }
    },
    mounted() {
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.id = this.$route.params.id;
        this.loadData();
    }
}
</script>
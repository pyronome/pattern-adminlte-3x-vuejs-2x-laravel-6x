<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("AdminLTE User Detail") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteuser'">{{ $t("AdminLTE User List") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("AdminLTE User Detail") }}</li>
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
                                        class="btn btn-primary btn-xs btn-on-table text-white"
                                        :to="'/' + main_folder + '/adminlteuser/edit/' + id">
                                        <i class="fas fa-pencil-alt" aria-hidden="true"></i> <span>{{ $t('Edit') }}</span>
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
                                            <label class="detail-label">{{ $t('AdminLTE User Group') }}</label>
                                            <div v-html="data.adminlteusergroup_id__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Fullname') }}</label>
                                            <div v-html="data.fullname__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Username') }}</label>
                                            <div v-html="data.username__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Email') }}</label>
                                            <div v-html="data.email__displaytext__"></div>
                                        </div>
                                        <div class="detail-container  unvisible-property1 ">
                                            <label class="detail-label">{{ $t('Profile Image') }}</label>
                                            <div v-html="data.profile_img__displaytext__"></div>
                                        </div>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <input type="hidden" id="controller" value="adminlteuser">
		<image-display :init_image_display="init_image_display"></image-display>
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
            },
			init_image_display: false
        };
    },
    methods: {
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
			
			var self = this;

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.data = data.list[0];
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                }).finally(function() {
                    self.init_image_display = true;
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
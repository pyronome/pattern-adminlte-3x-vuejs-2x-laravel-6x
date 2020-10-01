<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("AdminLTEUser Edit") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteuser'">{{ $t("AdminLTEUser List") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("AdminLTEUser Edit") }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
						<form id="AdminLTEUserForm"
                            class=""
                            @submit.prevent="submitForm"
                            @keydown="AdminLTEUserForm.onKeydown($event)">
                            <div class="card">
                                <div class="card-header show_by_permission_must_update">
                                    <div class="card-tools">
        								<router-link tag="a"
        									class="btn btn-danger btn-xs btn-on-table float-right"
        									:to="backbuttonURL">
        									<i class="fas fa-times" aria-hidden="true"></i> <span>{{ $t('Cancel') }}</span>
        								</router-link>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" v-model="AdminLTEUserForm.id" id="id" name="id">
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="AdminLTEUserForm_enabled"
                                                    name="AdminLTEUserForm_enabled"
                                                    class=""
                                                    v-model="AdminLTEUserForm.enabled"/>
                                                <label for="AdminLTEUserForm_enabled" class="detail-label">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_adminlteusergroup_id" class="detail-label">{{  $t('AdminLTE User Group') }}  </label>
                                            <select2-element
                                                data-placeholder=""
                                                id="AdminLTEUserForm_adminlteusergroup_id"
                                                name="AdminLTEUserForm_adminlteusergroup_id"
                                                :options="adminlteusergroup_id_options"
                                                v-model="AdminLTEUserForm.adminlteusergroup_id"
    											class="select2-element">
												<option></option>
                                            </select2-element>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_fullname" class="detail-label">{{ $t('Fullname') }}  </label>
                                            <input type="text"
                                                v-model="AdminLTEUserForm.fullname"
                                                class="form-control "
                                                id="AdminLTEUserForm_fullname"
                                                name="AdminLTEUserForm_fullname">
                                        </div> 
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_username" class="detail-label">{{ $t('Username') }} <span class="required">*</span></label>
                                            <input type="text"
                                                v-model="AdminLTEUserForm.username"
                                                class="form-control "
                                                :class="{ 'is-invalid': AdminLTEUserForm.errors.has('username') }"
                                                id="AdminLTEUserForm_username"
                                                name="AdminLTEUserForm_username">
                                            <has-error :form="AdminLTEUserForm" field="username"></has-error>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_email" class="detail-label">{{ $t('Email') }} <span class="required">*</span></label>
                                            <input type="email"
                                                v-model="AdminLTEUserForm.email"
                                                class="form-control "
                                                :class="{ 'is-invalid': AdminLTEUserForm.errors.has('email') }"
                                                id="AdminLTEUserForm_email"
                                                name="AdminLTEUserForm_email">
                                            <has-error :form="AdminLTEUserForm" field="email"></has-error>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_password" class="detail-label">{{ $t('Password') }}  </label>
                                            <input type="password"
                                                v-model="AdminLTEUserForm.password"
                                                class="form-control "
                                                id="AdminLTEUserForm_password"
                                                name="AdminLTEUserForm_password">
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserForm_profile_img" class="detail-label">{{ $t('Profile Image') }}  </label>
                                            <div class="input-field">
                                                <input type="hidden"
                                                    class="form-control"
                                                    id="AdminLTEUserForm_profile_img"
                                                    name="AdminLTEUserForm_profile_img"
                                                    v-model="AdminLTEUserForm.profile_img"
                                                    :class="{ 'is-invalid': AdminLTEUserForm.errors.has('profile_img') }"
                                                    data-target-field="AdminLTEUser/profile_img"
                                                    data-media-type="2"
                                                    data-max-file-count="1"/>
                                                <button type="button"
                                                    id="buttonBrowseprofile_imgFiles"
                                                    name="buttonBrowseprofile_imgFiles"
                                                    class="buttonBrowseFile btn btn-primary show_by_permission_must_update"
                                                    data-target-file-list="ulprofile_imgFileList">
                                                    <i class="ion-ios-folder"></i>&nbsp;{{ $t('Browse...') }}
                                                </button>
                                                <has-error :form="AdminLTEUserForm" field="profile_img"></has-error>
                                                <ul id="ulprofile_imgFileList" class="col s12 collection ulFileList" data-target-input-id="AdminLTEUserForm_profile_img">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer show_by_permission_must_update">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button :disabled="AdminLTEUserForm.busy"
    										type="submit"
                                            class="btn btn-success btn-xs btn-on-table float-right">
                                            <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <input type="hidden" id="controller" value="adminlteuser">
		<dropzone style="display:none;"></dropzone>
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            id: 0,
            adminlteusergroup_id_options: [],
            files: [],
			AdminLTEUserForm: new Form({
                'debug_mode': false,
                'id': this.id,
				'enabled': false,
                'adminlteusergroup_id': [],
                'fullname': '',
                'username': '',
                'email': '',
                'password': '',
                'profile_img': ''
            }),
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false
            }
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/adminlteuser';
            if (this.id > 0) {
                URL = URL + '/detail/' + this.id;
            }
            return URL;
        }
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded && !this.page.is_adminlteusergroup_id_options_loaded && !this.page.is_files_loaded) {
                this.$Progress.start();
            }
			
			if (!this.page.is_adminlteusergroup_id_options_loaded) {
                this.load_adminlteusergroup_id_options();
            } 

			if (!this.page.is_files_loaded) {
                this.load_files();
            }

 			if (this.page.is_data_loaded) {
				this.$nextTick(function () {
                    this.initializePage();
                });

				this.$Progress.finish();
				this.page.is_ready = true;
			} else {
				this.loadData();
			}
        },
		load_adminlteusergroup_id_options: function () {
            if (this.page.is_adminlteusergroup_id_options_loading) {
                return;
            }

            this.page.is_adminlteusergroup_id_options_loading = true;
			
			axios.get(AdminLTEHelper.getAPIURL("adminltemodeloption/get_model_option_list/AdminLTEUserGroup/title"))
                .then(({ data }) => {
                    this.page.is_adminlteusergroup_id_options_loaded = true;
                    this.page.is_adminlteusergroup_id_options_loading = false;
                    this.adminlteusergroup_id_options = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_adminlteusergroup_id_options_loaded = true;
                    this.page.is_adminlteusergroup_id_options_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
		load_files: function () {
            if (this.page.is_files_loading) {
                return;
            }

            this.page.is_files_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get_files/" + this.id))
                .then(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.files = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    if (this.id > 0) {
                        this.AdminLTEUserForm.fill(data.list[0]);
                    }
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
		initializePage: function () {
			AdminLTEHelper.initializePageFiles(this.files);
		},
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.AdminLTEUserForm.post(AdminLTEHelper.getAPIURL("adminlteuser/post"))
                .then(({ data }) => {
                    this.$Progress.finish();
                    this.$router.push('/adminlte/adminlteuser/detail/' + data.id);
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    },
    mounted() {
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.id = parseInt(this.$route.params.id);    
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
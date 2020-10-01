<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("AdminLTE User Group Edit") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteusergroup'">{{ $t("AdminLTE User Group List") }}</router-link></li>
                            <li class="breadcrumb-item active">{{ $t("AdminLTE User Group Edit") }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
						<form id="AdminLTEUserGroupForm"
                            class=""
                            @submit.prevent="submitForm"
                            @keydown="AdminLTEUserGroupForm.onKeydown($event)">
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
                                    <input type="hidden" v-model="AdminLTEUserGroupForm.id" id="id" name="id">
                                    <div class="row">
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="AdminLTEUserGroupForm_enabled"
                                                    name="AdminLTEUserGroupForm_enabled"
                                                    class=""
                                                    v-model="AdminLTEUserGroupForm.enabled"/>
                                                <label for="AdminLTEUserGroupForm_enabled" class="detail-label">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="AdminLTEUserGroupForm_widget_permission"
                                                    name="AdminLTEUserGroupForm_widget_permission"
                                                    class=""
                                                    v-model="AdminLTEUserGroupForm.widget_permission"/>
                                                <label for="AdminLTEUserGroupForm_widget_permission" class="detail-label">
                                                    {{ $t('Widget Edit Permission') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12 col-md-12 col-xs-12 ">
                                            <label for="AdminLTEUserGroupForm_title" class="detail-label">{{ $t('Title') }}  </label>
                                            <input type="text"
                                                v-model="AdminLTEUserGroupForm.title"
                                                class="form-control "
                                                :class="{ 'is-invalid': AdminLTEUserGroupForm.errors.has('title') }"
                                                id="AdminLTEUserGroupForm_title"
                                                name="AdminLTEUserGroupForm_title">
                                                <has-error :form="AdminLTEUserGroupForm" field="title"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer show_by_permission_must_update">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button :disabled="AdminLTEUserGroupForm.busy"
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
        <input type="hidden" id="controller" value="adminlteusergroup">
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            id: 0,
			AdminLTEUserGroupForm: new Form({
                'debug_mode': false,
                'id': this.id,
				'enabled': false,
                'widget_permission': false,
                'title': ''
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
            let URL = '/' + this.main_folder + '/adminlteusergroup';
            if (this.id > 0) {
                URL = URL + '/detail/' + this.id;
            }
            return URL;
        }
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded) {
                this.loadData();
                this.$Progress.start();
            } else {
				this.$Progress.finish();
				this.page.is_ready = true;
			}
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteusergroup/get/" + this.id))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    if (this.id > 0) {
                        this.AdminLTEUserGroupForm.fill(data.list[0]);
                    }
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.AdminLTEUserGroupForm.post(AdminLTEHelper.getAPIURL("adminlteusergroup/post"))
                .then(({ data }) => {
                    this.$Progress.finish();
                    this.$router.push('/adminlte/adminlteusergroup/detail/' + data.id);
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
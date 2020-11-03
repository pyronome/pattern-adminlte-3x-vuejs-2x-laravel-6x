<template>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $t("User Group Permission") }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                            <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteusergroup'">{{ $t("User Group List") }}</router-link></li>
                            <li class="breadcrumb-item active" v-html="this.PermissionForm.title"></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
						<form id="PermissionForm"
                            class=""
                            @submit.prevent="submitForm"
                            @keydown="PermissionForm.onKeydown($event)">
                            <input type="hidden" v-model="PermissionForm.usergroup_id" id="usergroup_id" name="usergroup_id">
                            <input type="hidden" v-model="PermissionForm.title" id="title" name="title">
                            <input type="hidden" v-model="PermissionForm.permission_data" id="permission_data" name="permission_data">
                            <div class="card">
                                <div class="card-header show_by_permission_must_update">
                                    <div class="card-tools" style="width:100%;">
                                        <div class="row">
                                            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                                                <div class="input-group input-group-sm divSearchBar float-left" style="margin-bottom:1rem;">
                                                    <input type="text"
                                                        id="search_permission" name="search_permission"
                                                        v-model="search_permission"
                                                        v-on:keyup="searchPermission"
                                                        class="form-control float-right inputSearchBar"
                                                        placeholder="Search">
                                                    <div class="input-group-append labelSearchBar">
                                                        <button type="button" class="btn btn-default ">
                                                            <img class="imgLoader" src="/img/adminlte/loader.svg" width="14" height="14"/>
                                                            <i class="fas fa-search text-primary"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-4 col-md-6 col-lg-8">
                                                <router-link tag="a"
                                                    class="btn btn-danger btn-xs btn-on-table float-right"
                                                    :to="backbuttonURL">
                                                    <i class="fas fa-times" aria-hidden="true"></i> <span>{{ $t('Cancel') }}</span>
                                                </router-link>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row permission-group-container" data-metakey="__adminlte_menu">
                                        <h5 class="permission-form-title">{{ $t('Menu Permissions') }}</h5>
                                        <div class="permission-item col-lg-12 col-md-12 col-xs-12" v-for="(item, index) in menu_permission_items" :key="index">
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes __adminlte_menu"
                                                    :id="'gp-__adminlte_menu-' + item.value"
                                                    :data-token="item.value"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span v-html="item.title"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row permission-group-container" data-metakey="__adminlte_model">
                                        <h5 class="permission-form-title">{{ $t('Model Permissions') }}</h5>
                                        <div class="permission-item col-lg-12 col-md-12 col-xs-12" v-for="(model, index) in model_permission_items" :key="index">
			                                <h6>{{model}}</h6>
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes __adminlte_model"
                                                    :id="'gp-__adminlte_model-' + model + '-create'"
                                                    :data-token="model + '-create'"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span>{{ $t('Create') }}</span>
                                            </div>
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes __adminlte_model"
                                                    :id="'gp-__adminlte_model-' + model + '-read'"
                                                    :data-token="model + '-read'"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span>{{ $t('Read') }}</span>
                                            </div>
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes __adminlte_model"
                                                    :id="'gp-__adminlte_model-' + model + '-update'"
                                                    :data-token="model + '-update'"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span>{{ $t('Update') }}</span>
                                            </div>
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes __adminlte_model"
                                                    :id="'gp-__adminlte_model-' + model + '-delete'"
                                                    :data-token="model + '-delete'"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span>{{ $t('Delete') }}</span>
                                            </div>
		                                </div>
                                    </div>
                                    <div class="row permission-group-container" v-for="(group, index) in other_permission_items" :key="index" :data-metakey="group.meta_key">
                                        <h5 class="permission-form-title" v-html="group.title"></h5>
                                        <div class="permission-item col-lg-12 col-md-12 col-xs-12" v-for="(item, index) in group.items" :key="index">
                                            <div class="d-inline permission-controller">
                                                <button type="button"
                                                    v-on:click="toggleYes"
                                                    class="permission-button-yes"
                                                    :class="group.meta_key"
                                                    :id="'gp-' + group.meta_key + '-' + item.value"
                                                    :data-token="item.value"
                                                    data-value="">
                                                    Y
                                                </button>
                                                <span v-html="item.title"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer show_by_permission_must_update">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button :disabled="PermissionForm.busy"
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
        <page-variables :has_widgets="false"></page-variables>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            usergroup_id: 0,
            menu_permission_items: [],
            model_permission_items: [],
            other_permission_items: [],
			PermissionForm: new Form({
                'debug_mode': false,
                'usergroup_id': 0,
                'title': '',
                'permission_data': ''
            }),
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_permission_form_loading: false,
                is_permission_form_loaded: false,
                is_post_success: false
            },
            search_permission: '',
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/adminlteusergroup';
            if (this.usergroup_id > 0) {
                URL = URL + '/detail/' + this.usergroup_id;
            }
            return URL;
        }
    },
    methods: {
        toggleYes: function (event) {
            let button = event.target;

            if ("Y" == button.getAttribute("data-value")) {
                button.setAttribute("data-value", "");
            } else {
                button.setAttribute("data-value", "Y");
            }
        },
        processLoadQueue: function () {
            if (!this.page.is_data_loaded && !this.page.is_permission_form_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_permission_form_loaded) {
                this.load_permission_form();
            }

            if (this.page.is_data_loaded) {
                this.$Progress.finish();
                this.page.is_ready = true;
            } else {
                this.loadData();
            }
        },
        load_permission_form: function () {
            var self = this;
            if (self.page.is_permission_form_loading) {
                return;
            }

            self.page.is_permission_form_loading = true;
            
            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_permission_form"))
                .then(({ data }) => {
                    self.page.is_permission_form_loaded = true;
                    self.page.is_permission_form_loading = false;
                    self.menu_permission_items = data.menu_permission_items;
                    self.model_permission_items = data.model_permission_items;
                    self.other_permission_items = data.other_permission_items;
                }).catch(({ data }) => {
                    self.page.is_permission_form_loaded = true;
                    self.page.is_permission_form_loading = false;
                    self.$Progress.fail();
                    self.processLoadQueue();
                }).finally(function() {
                   self.processLoadQueue();
                });
        },
        loadData: function () {
            var self = this;
            if (self.page.is_data_loading) {
                return;
            }

            self.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlteusergroup/get_permission_data/" + self.usergroup_id))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    if (self.usergroup_id > 0) {
                        self.PermissionForm.fill(data.form_data);
                    }
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.$Progress.fail();
                    self.processLoadQueue();
                }).finally(function() {
                    setTimeout(function() {
                        self.deployPermissions();
                    }, 1000);
                });
        },
        deployPermissions: function() {
            this.PermissionForm.permission_data.forEach(permission_group_data => {
                let meta_key = permission_group_data['meta_key'];
                let buttonIdPrefix = "gp-" + meta_key + "-";
                let permissions = permission_group_data['permissions'];
                let tokens = Object.keys(permission_group_data['permissions']);
                tokens.forEach(token => {
                    if ("Y" == permissions[token]) {
                        document.getElementById(buttonIdPrefix + token).setAttribute("data-value", "Y");
                    }
                });
            });
        },
        collectPermissions: function() {
            var permission_data = [];

            this.$el.querySelectorAll(".permission-group-container").forEach(container => {
                let permission_group_data = {};
                let meta_key = container.getAttribute("data-metakey");
                permission_group_data["meta_key"] = meta_key;
                permission_group_data["permissions"] = {};

                let selectorText = "." + meta_key + ".permission-button-yes[data-value='Y']";
                this.$el.querySelectorAll(selectorText).forEach(button => {
                    let token = button.getAttribute("data-token");
                    permission_group_data["permissions"][token] = 'Y';
                });

                permission_data.push(permission_group_data);
            });

            this.PermissionForm.permission_data = permission_data;
        },
        submitForm: function () {
            var self = this;
            self.collectPermissions();

            self.$Progress.start();
            self.PermissionForm.post(AdminLTEHelper.getAPIURL("adminlteusergroup/post_permission_data"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.is_post_success = true;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.is_post_success = false;
                }).finally(function() {
                    if (self.page.is_post_success) {
                        Vue.swal.fire({
                            toast: true,
                            position: 'top-end',
                            title: '',
                            text: 'Permissions have been saved!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            onClose: () => {
                               self.$router.push('/adminlte/adminlteusergroup/detail/' + self.usergroup_id);
                            }
                        });
                    }
                });
        },
        searchPermission: function() {
            var searchText = this.search_permission;

            this.$el.querySelectorAll("div[data-search]").forEach(element => {
                element.style.display = "none";
                if (element.getAttribute("data-search").search(new RegExp(searchText, "i")) != -1) {
                    element.style.display = "block";
                }                                
            });
        }
    },
    mounted() {
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.usergroup_id = parseInt(this.$route.params.id);    
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
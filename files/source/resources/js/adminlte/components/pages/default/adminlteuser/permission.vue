<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.is_authorized" :type="page.unauthorized_type"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ $t("AdminLTEUser Permission") }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/adminlteuser'">{{ $t("AdminLTEUser List") }}</router-link></li>
                                <li class="breadcrumb-item active" v-html="this.PermissionForm.fullname"></li>
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
                                <input type="hidden" v-model="PermissionForm.user_id" id="user_id">
                                <input type="hidden" v-model="PermissionForm.group_permission_data" id="group_permission_data">
                                <input type="hidden" v-model="PermissionForm.permission_data" id="permission_data">
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
                                                            v-bind:placeholder="$t('Search')">
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
                                                        class="btn btn-danger btn-md btn-on-card float-right"
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
                                                        class="permission-button-yes-disabled __adminlte_menu"
                                                        :id="'gp-__adminlte_menu-' + item.value"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes __adminlte_menu"
                                                        :id="'up-__adminlte_menu-' + item.value + '-yes'"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no __adminlte_menu"
                                                        :id="'up-__adminlte_menu-' + item.value + '-no'"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('N') }}
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
                                                        class="permission-button-yes-disabled __adminlte_model"
                                                        :id="'gp-__adminlte_model-' + model + '-create'"
                                                        :data-token="model + '-create'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-create' + '-yes'"
                                                        :data-token="model + '-create'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-create' + '-no'"
                                                        :data-token="model + '-create'"
                                                        data-value="">
                                                        {{ $t('N') }}
                                                    </button>
                                                    <span>{{ $t('Create') }}</span>
                                                </div>
                                                <div class="d-inline permission-controller">
                                                    <button type="button"
                                                        class="permission-button-yes-disabled __adminlte_model"
                                                        :id="'gp-__adminlte_model-' + model + '-read'"
                                                        :data-token="model + '-read'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-read' + '-yes'"
                                                        :data-token="model + '-read'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-read' + '-no'"
                                                        :data-token="model + '-read'"
                                                        data-value="">
                                                        {{ $t('N') }}
                                                    </button>
                                                    <span>{{ $t('Read') }}</span>
                                                </div>
                                                <div class="d-inline permission-controller">
                                                    <button type="button"
                                                        class="permission-button-yes-disabled __adminlte_model"
                                                        :id="'gp-__adminlte_model-' + model + '-update'"
                                                        :data-token="model + '-update'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-update' + '-yes'"
                                                        :data-token="model + '-update'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-update' + '-no'"
                                                        :data-token="model + '-update'"
                                                        data-value="">
                                                        {{ $t('N') }}
                                                    </button>
                                                    <span>{{ $t('Update') }}</span>
                                                </div>
                                                <div class="d-inline permission-controller">
                                                    <button type="button"
                                                        class="permission-button-yes-disabled __adminlte_model"
                                                        :id="'gp-__adminlte_model-' + model + '-delete'"
                                                        :data-token="model + '-delete'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-delete' + '-yes'"
                                                        :data-token="model + '-delete'"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no __adminlte_model"
                                                        :id="'up-__adminlte_model-' + model + '-delete' + '-no'"
                                                        :data-token="model + '-delete'"
                                                        data-value="">
                                                        {{ $t('N') }}
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
                                                        class="permission-button-yes-disabled"
                                                        :class="group.meta_key"
                                                        :id="'gp-' + group.meta_key + '-' + item.value"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleYes"
                                                        class="permission-button-yes"
                                                        :class="group.meta_key"
                                                        :id="'up-' + group.meta_key + '-' + item.value + '-yes'"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('Y') }}
                                                    </button>
                                                    <button type="button"
                                                        v-on:click="toggleNo"
                                                        class="permission-button-no"
                                                        :class="group.meta_key"
                                                        :id="'up-' + group.meta_key + '-' + item.value + '-no'"
                                                        :data-token="item.value"
                                                        data-value="">
                                                        {{ $t('N') }}
                                                    </button>
                                                    <span v-html="item.title"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer sbp-item sbp-hide" 
                                        menu-permission-token="adminlteuser"
                                        model-permission-token="AdminLTEUser-update">
                                        <div class="col-lg-12 col-md-12 col-xs-12">
                                            <button :disabled="PermissionForm.busy"
                                                type="submit"
                                                class="btn btn-success btn-md btn-on-card float-right">
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
        </div>
        <input type="hidden" id="controller" value="adminlteuser">
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            user_id: 0,
            menu_permission_items: [],
            model_permission_items: [],
            other_permission_items: [],
			PermissionForm: new Form({
                'debug_mode': false,
                'user_id': 0,
                'title': '',
                'fullname': '',
                'group_permission_data': '',
                'permission_data': ''
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                is_authorized: true,
                unauthorized_type: '',
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_permission_form_loading: false,
                is_permission_form_loaded: false
            },
            search_permission: '',
        };
    },
    computed: {
        backbuttonURL() {
            let URL = '/' + this.main_folder + '/adminlteuser';
            if (this.user_id > 0) {
                URL = URL + '/detail/' + this.user_id;
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
                $(button).closest(".permission-controller").find(".permission-button-no").first().attr("data-value", "");
            }
        },
        toggleNo: function (event) {
            let button = event.target;

            if ("N" == button.getAttribute("data-value")) {
                button.setAttribute("data-value", "");
            } else {
                button.setAttribute("data-value", "N");
                $(button).closest(".permission-controller").find(".permission-button-yes").first().attr("data-value", "");
            }
        },
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_authorized) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded && !this.page.is_permission_form_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_permission_form_loaded) {
                    this.load_permission_form();
                }

                if (this.page.is_data_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;
                } else {
                    this.loadData();
                }
            }
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("adminlte/get_page_variables/" + self.pagename))
                .then(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.page.variables = data;
                }).catch(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                   AdminLTEHelper.initializePermissions(self.page.variables, false);
                   let authorize = {};
                   if ("new" == self.id) {
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUserGroup', 'create');
                   } else {
                       authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUserGroup', 'read');
                       if (authorize.status) {
                           authorize = AdminLTEHelper.isUserAuthorized(self.page.variables, self.pagename, 'AdminLTEUserGroup', 'update');
                       }
                   }

                   self.page.is_authorized = authorize.status;
                   self.page.unauthorized_type = authorize.type;
                   self.processLoadQueue();
                });
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
                    self.page.has_server_error = true;
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

            axios.get(AdminLTEHelper.getAPIURL("adminlteuser/get_permission_data/" + self.user_id))
                .then(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    if (self.user_id > 0) {
                        self.PermissionForm.fill(data.form_data);
                    }
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_data_loaded = true;
                    self.page.is_data_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                    setTimeout(function() {
                        self.deployPermissions();
                    }, 1000);
                });
        },
        deployPermissions: function() {
            this.PermissionForm.group_permission_data.forEach(group_permission => {
                let meta_key = group_permission['meta_key'];
                let buttonIdPrefix = "#gp-" + meta_key + "-";
                let permissions = group_permission['permissions'];
                let tokens = Object.keys(group_permission['permissions']);
                tokens.forEach(token => {
                    if ("Y" == permissions[token]) {
                        $(buttonIdPrefix + token).attr("data-value", "Y");
                    }
                });
            });

            this.PermissionForm.permission_data.forEach(user_permission => {
                let meta_key = user_permission['meta_key'];
                let buttonIdPrefix = "#up-" + meta_key + "-";
                let permissions = user_permission['permissions'];
                let tokens = Object.keys(user_permission['permissions']);
                tokens.forEach(token => {
                    if ("Y" == permissions[token]) {
                        $(buttonIdPrefix + token + "-yes").attr("data-value", "Y");
                    } else if ("N" == permissions[token]) {
                        $(buttonIdPrefix + token + "-no").attr("data-value", "N");
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

                let selectorText = "." + meta_key + ".permission-button-yes";
                this.$el.querySelectorAll(selectorText).forEach(button => {
                    let token = button.getAttribute("data-token");
                    let value = button.getAttribute("data-value");
                    permission_group_data["permissions"][token] = value;
                });

                selectorText = "." + meta_key + ".permission-button-no[data-value='N']";
                this.$el.querySelectorAll(selectorText).forEach(button => {
                    let token = button.getAttribute("data-token");
                    permission_group_data["permissions"][token] = 'N';
                });

                permission_data.push(permission_group_data);
            });

            this.PermissionForm.permission_data = permission_data;
        },
        submitForm: function () {
            var self = this;
            self.collectPermissions();
            
            self.$Progress.start();
            self.PermissionForm.post(AdminLTEHelper.getAPIURL("adminlteuser/post_permission_data"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.id = data.id;
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: 'Changes have been saved!',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    self.$router.push('/adminlte/adminlteuser/detail/' + self.id);
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                toast: true,
                                position: 'top-end',
                                title: '',
                                text: self.page.post_error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
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
        this.pagename = AdminLTEHelper.getPagename();
        this.user_id = parseInt(this.$route.params.id);    
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
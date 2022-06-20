<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Menu Configuration') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Menu Configuration') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="card">
                                <form id="formConfiguration" @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                                    <div class="card-body">
                                        <div class="row mb-10 show_by_permission_must_update">
                                            <div class="col-lg-12 col-md-12 col-xs-12">
                                                <a id="buttonNewMenuItem" class="btn btn-primary btn-md btn-on-card float-right" href="javascript:void(0);">
                                                    <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('New') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-xs-12">
                                                <ul id="ulMenuEditor" class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer show_by_permission_must_update">
                                        <button :disabled="form.busy"
                                            type="submit"
                                            class="btn btn-success btn-md btn-on-table float-right">
                                            {{ $t('Save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="modalMenuItem">
                <div class="modal-dialog modal-md">
                    <form id="formMenuItem" name="formMenuItem" method="post" class="form-horizontal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t('Menu Item') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <div class="">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox"
                                                id="__group"
                                                name="__group"
                                                class="item-menu"
                                                v-model="group_checked"
                                                @change="__groupChanged($event)">
                                            <label for="__group" class="">
                                                {{ $t('Group') }}  
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="text">{{ $t('Title') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-menu" name="text" id="text">
                                        <div class="input-group-append" :style="group_style">
                                            <button type="button" id="ulMenuEditor_icon" class="btn btn-outline-secondary"></button>
                                        </div>
                                    </div>
                                    <input type="hidden" name="icon" class="item-menu">
                                </div>
                                <div class="form-group" :style="group_style">
                                    <label for="href">{{ $t('URL') }}</label>
                                    <input type="text" class="form-control item-menu" id="href" name="href">
                                </div>
                                <div class="form-group">
                                    <label for="visibility">{{ $t('Visibility') }}</label>
                                    <select name="visibility" id="visibility" class="form-control item-menu">
                                        <option value="0">{{ $t('No') }}</option>
                                        <option value="1">{{ $t('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modalfooter justify-content-between show_by_permission_must_update">
                                <div class="row">
                                    <div class="col col-lg-12">
                                        <button type="button"
                                            id="buttonAddMenuItem"
                                            name="buttonAddMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button"
                                            id="buttonUpdateMenuItem"
                                            name="buttonUpdateMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modalMenuItemDelete">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Menu Item Delete') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <p>{{ $t('Selected item will be deleted. Do you confirm?') }}</p>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col col-lg-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button type="button"
                                        id="buttonDeleteMenuItem"
                                        name="buttonDeleteMenuItem"
                                        class="btn btn-danger float-right">
                                        {{ $t('Continue') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            form: new Form({
                'menu_json': ''
            }),
            group_checked: false,
            group_style: "",
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false,
                external_files: [
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/menu_editor.js")
                ],
                editor: null
            }
        };
    },
    methods: {
        __groupChanged: function() {
            var self = this;

            if (self.group_checked) {
                self.group_style = "display:none";
            } else {
                self.group_style = "";
            }
        },
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.authorization.status) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded && !this.page.is_data_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (this.page.is_data_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;

                    this.$nextTick(function () {
                        this.updateMenuEditor();
                    });
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

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/" + self.pagename))
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
                    WisiloHelper.initializePermissions(self.page.variables, false);
                    self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename);
                    self.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("menu_configuration"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.fill(data);
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            var str = self.page.editor.getString();
            self.form.menu_json = str;
            
            self.form.post(WisiloHelper.getAPIURL("menu_configuration/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.form.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Your changes could not be saved. Please check your details and try again.");
                    }
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    window.location.reload()
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        },
        updateMenuEditor: function () {
            if (!this.page.is_ready) {
                return;
            }
            
            $("#buttonNewMenuItem").off("click").on("click", function () {
                document.getElementById("text").value = "";
	            document.getElementById("href").value = "";
                document.getElementById("visibility").value = 1;
                
                $("#buttonUpdateMenuItem").hide();
                $("#buttonAddMenuItem").show();
    
                $("#modalMenuItem").modal();
            });

            var rawMenuJSON = decodeURIComponent(this.form.menu_json);
            var menuJSON = JSON.parse(rawMenuJSON);
            
            //icon picker options
            var iconPickerOptions = {
                searchText: '...',
                labelHeader: '{0} / {1}'
            };

            var sortableListOptions = {
                placeholderCss: {"background-color": "#cccccc"}
            };

            this.page.editor = new MenuEditor(
                    "ulMenuEditor",
                    {
                        listOptions: sortableListOptions,
                        iconPicker: iconPickerOptions
                    }
            );
            this.page.editor.setForm($("#formMenuItem"));
            this.page.editor.setUpdateButton($("#buttonUpdateMenuItem"));
            this.page.editor.setData(menuJSON);

            var self = this;

            $("#buttonUpdateMenuItem").off("click").on("click", function(){
                self.page.editor.update();
                $("#modalMenuItem").modal('hide');
            });

            $("#buttonAddMenuItem").off("click").on("click", function(){
                self.page.editor.add();
                $("#modalMenuItem").modal('hide');
            });

            $( "#ulMenuEditor" ).sortable();
            $( "#ulMenuEditor" ).disableSelection();
        },
    },
    mounted() {
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.page.is_ready = false;
        WisiloHelper.loadExternalFiles(
                this.page.external_files,
                this.processLoadQueue());
    }
}
</script>
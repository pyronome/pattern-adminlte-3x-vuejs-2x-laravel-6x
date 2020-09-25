<template>
    <div>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Menu Configuration') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
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
                                                <a id="buttonNewMenuItem" class="btn btn-primary btn-xs btn-on-table float-right" href="javascript:void(0);">
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
                                            <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
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
                                <label for="text">{{ $t('Title') }}</label>
                                <div class="input-group">
                                    <input type="text" class="form-control item-menu" name="text" id="text">
                                    <div class="input-group-append">
                                        <button type="button" id="ulMenuEditor_icon" class="btn btn-outline-secondary"></button>
                                    </div>
                                </div>
                                <input type="hidden" name="icon" class="item-menu">
                            </div>
                            <div class="form-group">
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
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
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
</template>

<script>
export default {
    data() {
        return {
            form: new Form({
                'menu_json': ''
            }),
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                external_files: [
                    ("/js/" + AdminLTEHelper.getURL('menu_editor.js')),
                    ("/js/" + AdminLTEHelper.getURL('/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')),
                    ("/js/" + AdminLTEHelper.getURL('/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js')),
                    ("/js/" + AdminLTEHelper.getURL('/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js'))
                ],
                editor: null
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded) {
                this.loadData();
            }

            if (this.page.is_data_loaded) {
                this.page.is_ready = true;
                this.$Progress.finish();
                this.updateMenuEditor();
            }
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("menu_configuration"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.fill(data);
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
            var str = this.page.editor.getString();
            this.form.menu_json = str;

            this.form.post(AdminLTEHelper.getAPIURL("menu_configuration/post"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
                }).finally(function() {
                    Vue.swal.fire({
                        toast: true,
                        position: 'top-end',
                        title: '',
                        text: 'Menu configuration have been saved!',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        onClose: () => {
                            window.location.reload()
                        }
                    });
                });
        },
        updateMenuEditor: function () {
            if (!this.page.is_ready) {
                return;
            }

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
        this.$Progress.start();
        this.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(
                this.page.external_files,
                this.processLoadQueue);
    }
}
</script>
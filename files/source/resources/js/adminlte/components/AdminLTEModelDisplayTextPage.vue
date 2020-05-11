<template>
    <div>
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Model Display Texts Configuration') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Model Display Texts Configuration') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Model</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyModelList">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="modal fade" id="modal-ModelDisplayTextList" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Model Display Texts') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-top:0px;">
                        <form id="formModelDisplayText"  onsubmit="return false;">
                            <input type="hidden"
                                id="formModelDisplayText-id"
                                name="formModelDisplayText-id"
                                value="">
                            <input type="hidden"
                                id="formModelDisplayText-model"
                                name="formModelDisplayText-model"
                                value=""/>   
                            <input type="hidden"
                                id="formModelDisplayText-display_text_json"
                                name="formModelDisplayText-display_text_json"
                                value=""/>
                        </form>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>Property</th>
                                                <th>Display Text</th>
                                                <th style="width:60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyModelDisplayText">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="button"
                                    id="buttonSave-formModelDisplayText"
                                    name="buttonSave-formModelDisplayText"
                                    class="btn btn-success float-right">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-EditDisplayText" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Edit Display Text') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-top:0px;">
                        <form id="formEditDisplayText"  onsubmit="return false;">
                            <input type="hidden"
                                id="formEditDisplayText-index"
                                name="formEditDisplayText-index"
                                value="">
                        </form>
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                <label for="formEditDisplayText-property" class="detail-label">{{ __('Property') }}</label>
                                <input type="text"
                                    class="form-control"
                                    id="formEditDisplayText-property"
                                    name="formEditDisplayText-property"
                                    value=""
                                    readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                <label for="formEditDisplayText-display_text" class="label-with-btn">{{ __('Display Text') }}</label>
                                <button id="buttonSearchProperty" class="noborder-edit-btn text-primary float-right" style="width:auto;">
                                    <i class="fa fa-search-plus"></i>&nbsp;{{ __('Insert Class Property') }}
                                </button>
                                <textarea id="formEditDisplayText-display_text"
                                    name="formEditDisplayText-display_text"
                                    class="textarea"
                                    rows="5"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modalfooter justify-content-between show_by_permission">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ __('Cancel') }}</button>
                                <button type="button"
                                    id="buttonSave-formEditDisplayText"
                                    name="buttonSave-formEditDisplayText"
                                    class="btn btn-success float-right">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modal-ModelProportyList" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ __('Model Properties') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                <div id="ulModelList" class="nav flex-column nav-pills" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-9 tab-content" id="ulModelContentList">
                                
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ __('Close') }}</button>
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
            this.form.post(AdminLTEHelper.getAPIURL("menu_configuration"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
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

            $("#buttonSave-formConfiguration").off("click").on("click", function () {
                var str = this.page.editor.getString();
                saveMenuConfiguration(str);
            });

            $("#buttonUpdateMenuItem").off("click").on("click", function(){
                this.page.editor.update();
                $("#modalMenuItem").modal('hide');
            });

            $("#buttonAddMenuItem").off("click").on("click", function(){
                this.page.editor.add();
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
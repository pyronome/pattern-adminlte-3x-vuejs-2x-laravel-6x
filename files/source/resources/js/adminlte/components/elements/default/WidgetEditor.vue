<template>
    <div>
        <div class="modal fade" id="modal-WidgetList" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="formConfiguration" @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                        <input type="hidden" id="pagename" name="pagename" v-model="form.pagename">
                        <input type="hidden" id="widgetJSON" name="widgetJSON" v-model="form.widgetJSON">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Widgets') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-top:0px;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <a id="buttonShowInvisibleWidgets" class="float-left" href="javascript:void(0);" style="padding: 7px 0px;">
                                        <span>{{ $t('Show Invisible Widgets') }}</span>
                                    </a>
                                    <a id="buttonHideInvisibleWidgets" class="float-left" href="javascript:void(0);" style="padding: 7px 0px;display:none;">
                                        <span>{{ $t('Hide Invisible Widgets') }}</span>
                                    </a>
                                </div>
                                <div class="col-md-8 col-sm-6 col-xs-12 mb-10">
                                    <div class="input-group input-group-md">
                                        <input type="search"
                                            id="searchWidget" name="searchWidget"
                                            class="form-control float-right inputSearchBar"
                                            placeholder="Search">
                                        <div class="input-group-append labelSearchBar">
                                            <button type="button" class="btn btn-default">
                                                <i class="fas fa-search text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6 col-xs-12 mb-10">
                                    <a id="buttonAddEmptyWidget" class="btn btn-primary btn-md float-right" href="javascript:void(0);" style="padding: 7.5px 10px;">
                                        <i class="fa fa-plus"></i> <span>{{ $t('Add Empty Widget') }}</span>
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <ul id="ulWidgetEditor" class="list-group">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button :disabled="form.busy"
                                        type="submit"
                                        class="btn btn-success btn-md btn-on-table float-right">
                                        <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalWidgetItem" tabindex="1" role="dialog">
            <div class="modal-dialog modal-md">
                <form id="formWidgetItem" name="formWidgetItem" method="post" class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="widgetModalTitle" class="modal-title">{{ $t('Widget Item') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="__widgetconfig-type" name="__widgetconfig-type" class="item-widget">
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-model">{{ $t('Model') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-widget" name="__widgetconfig-model" id="__widgetconfig-model" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-text">{{ $t('Title') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-widget" name="__widgetconfig-text" id="__widgetconfig-text">
                                        <div class="input-group-append infobox-inputs">
                                            <button type="button" id="ulWidgetEditor_icon" class="btn btn-outline-secondary"></button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="__widgetconfig-icon" name="__widgetconfig-icon" class="item-widget">
                                </div>
                            </div>
                            <div class="row mb-20 infobox-inputs">
                                <div class="col-md-12">
                                    <label for="iconbackgroundPicker">{{ $t('Icon Background') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="iconbackgroundPicker" id="iconbackgroundPicker">
                                    </div>

                                    <input type="hidden" id="__widgetconfig-iconbackground" name="__widgetconfig-iconbackground" class="item-widget">
                                </div>
                            </div>
                            <div class="row mb-10 recordgraph-inputs">
                                <div class="col-md-6">
                                    <label for="__widgetconfig-graphtype">{{ $t('Type') }}</label>
                                    <select name="__widgetconfig-graphtype" id="__widgetconfig-graphtype" class="form-control item-widget">
                                        <option value="daily">{{ $t('Daily') }}</option>
                                        <option value="monthly">{{ $t('Monthly') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="__widgetconfig-graphperiod">{{ $t('Show Last') }}</label>
                                    <select name="__widgetconfig-graphperiod" id="__widgetconfig-graphperiod" class="form-control item-widget">
                                        <option value="1">{{ $t('Month') }}</option>
                                        <option value="3">{{ $t('3 Months') }}</option>
                                        <option value="6">{{ $t('6 Months') }}</option>
                                        <option value="12">{{ $t('Year') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-10 notrecordgraph-inputs">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-href">{{ $t('URL') }}</label>
                                    <input type="text" class="form-control item-widget" id="__widgetconfig-href" name="__widgetconfig-href">
                                </div>
                            </div>
                            <div class="row mb-10">
                                <input type="hidden" id="__widgetconfig-size" name="__widgetconfig-size" class="item-widget">
                                <div class="col-md-4">
                                    <label for="large_screen_size">{{ $t('Large Screen Size') }}</label>
                                    <select id="large_screen_size" name="large_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="medium_screen_size">{{ $t('Medium Screen Size') }}</label>
                                    <select id="medium_screen_size" name="medium_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="small_screen_size">{{ $t('Small Screen Size') }}</label>
                                    <select id="small_screen_size" name="small_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-20 recordlist-inputs">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-limit">{{ $t('Page Length') }}</label>
                                    <select name="__widgetconfig-limit" id="__widgetconfig-limit" class="form-control item-widget">
                                        <option value="5" selected>5</option>
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-20 recordlist-inputs">
                                <div class="col-md-12">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox"
                                            id="__widgetconfig-onlylastrecord"
                                            name="__widgetconfig-onlylastrecord"
                                            class="form-control item-widget"
                                            value="1">
                                        <label for="__widgetconfig-onlylastrecord" class="detail-label">
                                            {{ $t('Show Only Last Records') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-10 recordlist-inputs">
                                <input type="hidden" id="__widgetconfig-columns" name="__widgetconfig-columns" class="item-widget">
                                <input type="hidden" id="__widgetconfig-values" name="__widgetconfig-values" class="item-widget">
                                <div class="col-md-12">
                                    <table id="recordlistColumnTable" class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ $t('Column') }}</th>
                                                <th>{{ $t('Value') }}</th>
                                                <th style="width:20px;"></th>
                                                <th class="text-center" style="width:20px;">
                                                    <a id="buttonAddColumn" class="table-icon-primary" href="javascript:void(0);">
                                                        <i class="fa fa-plus"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyRecordListColumns">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-visibility">{{ $t('Visibility') }}</label>
                                    <select name="__widgetconfig-visibility" id="__widgetconfig-visibility" class="form-control item-widget">
                                        <option value="0">{{ $t('No') }}</option>
                                        <option value="1">{{ $t('Yes') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button type="button"
                                        id="buttonUpdateWidgetItem"
                                        name="buttonUpdateWidgetItem"
                                        class="btn btn-success float-right">
                                        {{ $t('Save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal fade" id="modal-RecordListColumns" tabindex="2" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Columns & Values') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="recordlistIndex" name="recordlistIndex" value="0">
                        <div class="col-md-12">
                            <label for="recordlistColumn">{{ $t('Column Title') }}</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="recordlistColumn" id="recordlistColumn">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="recordlistValue">{{ $t('Value') }}</label>
                            <select id="recordlistValue"
                                name="recordlistValue"
                                class="form-control">
                                <option></option>
                                <option v-for="option in attributes"
                                    :key="option.id"
                                    :class="'columnOption' + option.model"
                                    :value="option.attribute">{{option.attribute}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                <button type="button"
                                    id="buttonSave-ColumnValue"
                                    name="buttonSave-ColumnValue"
                                    class="btn btn-success float-right">
                                    {{ $t('Save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalWidgetItemDelete" tabindex="3" role="dialog">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Delete') }}</h4>
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
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                <button type="button"
                                    id="buttonDeleteWidgetItem"
                                    name="buttonDeleteWidgetItem"
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
    props: ['pagename'],
    data() {
        return {
            attributes: [],
            form: new Form({
                'pagename': '',
                'widgetJSON': '',
            }),
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_attributes_loading: false,
                is_attributes_loaded: false,
                is_post_success: false,
                external_files: [
                    ("/js" + AdminLTEHelper.getURL('bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')),
                    ("/js" + AdminLTEHelper.getURL('bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js')),
                    ("/js" + AdminLTEHelper.getURL('bootstrap-iconpicker/js/bootstrap-iconpicker.min.js')),
                    ("/js" + AdminLTEHelper.getURL('bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')),
                    ("/js" + AdminLTEHelper.getURL('bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')),
                    ("/js" + AdminLTEHelper.getURL('widget_editor.js'))
                ],
                editor: null
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded && !this.page.is_attributes_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_attributes_loaded) {
                this.loadAttributes();
            }

            if (this.page.is_data_loaded) {
                this.$Progress.finish();
                this.page.is_ready = true;
            } else {
                this.loadData();
            }
        },
        loadAttributes: function() {
            if (this.page.is_attributes_loading) {
                return;
            }

            this.page.is_attributes_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get_attributes"))
                .then(({ data }) => {
                    this.page.is_attributes_loaded = true;
                    this.page.is_attributes_loading = false;
                    this.attributes = data.attributes;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_attributes_loaded = true;
                    this.page.is_attributes_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;
            var self = this;

            axios.get(AdminLTEHelper.getAPIURL("__layout/get/" + self.pagename))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.widgetJSON = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                }).finally(function() {
                    self.renderWidgetEditor();
                });
        },
        renderWidgetEditor: function() {
            if (!this.page.is_ready) {
                return;
            }

            var rawWidgetJSON = decodeURIComponent(this.form.widgetJSON);
            var widgetJSON = JSON.parse(rawWidgetJSON);

            //icon picker options
            var iconPickerOptions = {
                searchText: '...',
                labelHeader: '{0} / {1}'
            };

            var sortableListOptions = {
                placeholderCss: {"background-color": "#cccccc"}
            };

            this.page.editor = new WidgetEditor("ulWidgetEditor", {listOptions: sortableListOptions, iconPicker: iconPickerOptions});
            this.page.editor.setForm($("#formWidgetItem"));
            this.page.editor.setUpdateButton($("#buttonUpdateWidgetItem"));
            this.page.editor.setData(widgetJSON);
            this.page.editor.initializeWidgetEditor();

            var self = this;

            $("#buttonUpdateWidgetItem").off("click").on("click", function(){
                self.page.editor.update();
                $("#modalWidgetItem").modal("hide");
            });

            $("#buttonAddEmptyWidget").off("click").on("click", function(){
                self.page.editor.add();
                $("#modalWidgetItem").modal("hide");
            });

            $("#ulWidgetEditor").sortable();
            $("#ulWidgetEditor").disableSelection();

            $("#buttonShowInvisibleWidgets").off("click").on("click", function(){
                $(this).css("display", "none");
                $("#buttonHideInvisibleWidgets").css("display", "block");
                $(".li_invisible").css("display", "block");
            });

            $("#buttonHideInvisibleWidgets").off("click").on("click", function(){
                $(this).css("display", "none");
                $("#buttonShowInvisibleWidgets").css("display", "block");
                $(".li_invisible").css("display", "none");
            });

            $("#ulWidgetEditor_icon").on("click", function(){
                $(document).off('focusin.modal');
            });

            $("#searchWidget").off("keyup").on("keyup", function () {
                AdminLTEHelper.doSearchWidget(this);
            });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            var str = self.page.editor.getString();
            self.form.widgetJSON = str;
            
            self.form.post(AdminLTEHelper.getAPIURL("__layout/post/" + self.pagename))
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
                            text: 'Widget configuration have been saved!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            onClose: () => {
                                window.location.reload()
                            }
                        });
                    }
                });
        }
    },
    mounted() {
        this.$Progress.start();
        this.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(this.page.external_files);
    },
    watch: {
        pagename: function(pagename) {
            this.pagename = pagename;
            this.form.pagename = pagename;
            this.processLoadQueue();
        }
    }
}
</script>
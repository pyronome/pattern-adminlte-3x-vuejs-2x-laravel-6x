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
                                                <tr v-for="model_display_text_item in model_display_text_list" v-bind:key="model_display_text_item.id">
                                                    <td class="tdModelDisplayTextEditButton" :data-row-id="model_display_text_item.id">
                                                        <i class="fas fa-cog nav-icon"></i>&nbsp;&nbsp;{{model_display_text_item.model}}
                                                    </td>
                                                </tr>
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
                        <h4 class="modal-title">{{ $t('Model Display Texts') }}</h4>
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
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                <button type="button"
                                    id="buttonSave-formModelDisplayText"
                                    name="buttonSave-formModelDisplayText"
                                    class="btn btn-success float-right">
                                    {{ $t('Save') }}
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
                        <h4 class="modal-title">{{ $t('Edit Display Text') }}</h4>
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
                                <label for="formEditDisplayText-property" class="detail-label">{{ $t('Property') }}</label>
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
                                <label for="formEditDisplayText-display_text" class="label-with-btn">{{ $t('Display Text') }}</label>
                                <button id="buttonSearchProperty" class="noborder-edit-btn text-primary float-right" style="width:auto;">
                                    <i class="fa fa-search-plus"></i>&nbsp;{{ $t('Insert Class Property') }}
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
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                <button type="button"
                                    id="buttonSave-formEditDisplayText"
                                    name="buttonSave-formEditDisplayText"
                                    class="btn btn-success float-right">
                                    {{ $t('Save') }}
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
                        <h4 class="modal-title">{{ $t('Model Properties') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="min-height:350px;">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-3">
                                <div id="ulModelList"
                                    v-for="model_display_text_item in model_display_text_list"
                                    v-bind:key="model_display_text_item.id"
                                    class="nav flex-column nav-pills"
                                    role="tablist"
                                    aria-orientation="vertical">
                                    <a class="nav-link" :id="model_display_text_item.model + 'tab'" data-toggle="pill" :href="'#' + model_display_text_item.model + 'Content'" role="tab" :aria-controls="model_display_text_item.model" aria-selected="false">
                                        {{model_display_text_item.model}}
                                    </a>
                                </div>
                            </div>
                            <div
                                id="ulModelContentList"
                                v-for="model_display_text_item in model_display_text_list"
                                v-bind:key="model_display_text_item.id"
                                class="col-lg-9 col-md-9 col-sm-9 col-9 tab-content">
                                    <div class="tab-pane fade" :id="model_display_text_item.model + 'Content'" role="tabpanel" :aria-labelledby="model_display_text_item.model + '-tab'">
                                        <ul :id="'ul' + model_display_text_item.model + 'PropertyList'"
                                            v-for="model_property_item in model_property_list"
                                            v-bind:key="model_property_item.id"
                                            class="ulModelPropertyList">
                                            <li class="liModelProperty" :data-display-text="model_property_item.model + '/' + model_property_item.property">
                                                {{model_property_item.property}}
                                            </li>
                                        </ul>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/html" id="trEditDisplayTextTemplate">
            <td id="property___INDEX__">__PROPERTY__</td>
            <td><span class="span-display_text" id="display_text___INDEX__">__DISPLAY_TEXT__</span></td>
            <td class="text-center">
                <i id="updated-icon-__INDEX__" class="fas fa-cog nav-icon __SH_CLASS__"></i>
                <i class="fas fa-ban nav-icon text-red __ANTI_SH_CLASS__"></i>
            </td>
        </script>
    </div>
</template>

<script>
export default {
    data() {
        return {
            model_property_list: [],
            model_display_text_list: [],
            form: new Form({
                'menu_json': ''
            }),
            page: {
                is_ready: false,
                is_model_property_list_loading: false,
                is_model_property_list_loaded: false,
                is_model_display_text_list_loading: false,
                is_model_display_text_list_loaded: false
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_model_display_text_list_loaded) {
                this.loadModelDisplayText();
            }

            if (!this.page.is_model_property_list_loaded) {
                this.loadModelPropertyList();
            }

            if (this.page.is_model_display_text_list_loaded
                    && this.page.is_model_property_list_loaded) {
                this.page.is_ready = true;
                this.$Progress.finish();
            }
        },
        loadModelPropertyList: function () {
            if (this.page.is_model_property_list_loading) {
                return;
            }

            this.page.is_model_property_list_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__modeldisplaytext/get_model_property_list"))
                .then(({ data }) => {
                    this.page.is_model_property_list_loaded = true;
                    this.page.is_model_property_list_loading = false;
                    this.model_property_list = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_model_property_list_loaded = true;
                    this.page.is_model_property_list_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        loadModelDisplayText: function () {
            if (this.page.is_model_display_text_list_loading) {
                return;
            }

            this.page.is_model_display_text_list_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("__modeldisplaytext"))
                .then(({ data }) => {
                    this.page.is_model_display_text_list_loaded = true;
                    this.page.is_model_display_text_loading = false;
                    this.model_display_text_list = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_model_display_text_list_loaded = true;
                    this.page.is_model_display_text_list_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            // Submit the form via a POST request
            this.$Progress.start();
            this.form.post(AdminLTEHelper.getAPIURL("__modeldisplaytext"))
                .then(({ data }) => {
                    this.$Progress.finish();
                }).catch(({ data }) => {
                    this.$Progress.fail();
                });
        }
    },
    mounted() {
        this.$Progress.start();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Model Display Settings') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Model Display Settings') }}</li>
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
                                        <table class="table table-striped table-bordered table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Model</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tbodyModelList">
                                                <tr v-for="model_display_text_item in model_display_text_list"
                                                    :key="model_display_text_item.id">
                                                    <td class="tdModelDisplayTextEditButton"
                                                        :id="'tdModelDisplayTextEditButton' + model_display_text_item.id"
                                                        @click="showModelDisplayTextList(model_display_text_item)"
                                                        :data-row-id="model_display_text_item.id">
                                                        <i class="fas fa-cog nav-icon"></i>&nbsp;&nbsp;{{ model_display_text_item.model }}
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
                                        <table class="table table-striped table-bordered table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ $t('Property') }}</th>
                                                    <th>{{ $t('Display Text') }}</th>
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
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button type="button"
                                        id="buttonSave-formModelDisplayText"
                                        name="buttonSave-formModelDisplayText"
                                        @click="saveFormModelDisplayText"
                                        class="btn btn-success float-right">
                                        {{ $t('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>
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
                                    <button id="buttonSearchProperty"
                                        @click="showModelPropertyListModal"
                                        class="noborder-edit-btn text-primary float-right"
                                        style="width:auto;">
                                        <i class="fa fa-search-plus"></i>&nbsp;{{ $t('Insert Class Property') }}
                                    </button>
                                    <textarea id="formEditDisplayText-display_text"
                                        name="formEditDisplayText-display_text"
                                        class="textarea"
                                        rows="5"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonSave-formEditDisplayText"
                                        name="buttonSave-formEditDisplayText"
                                        @click="saveFormEditDisplayText"
                                        class="btn btn-success float-right">
                                        {{ $t('Save') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>
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
                                        class="nav flex-column nav-pills"
                                        role="tablist"
                                        aria-orientation="vertical">
                                        <a class="nav-link"
                                            v-for="model_display_text_item in model_display_text_list"
                                            :key="model_display_text_item.id"
                                            :id="model_display_text_item.model + 'tab'"
                                            data-toggle="pill"
                                            :href="'#' + model_display_text_item.model + 'Content'"
                                            role="tab"
                                            :aria-controls="model_display_text_item.model"
                                            aria-selected="false">
                                            {{ model_display_text_item.model }}
                                        </a>
                                    </div>
                                </div>
                                <div id="ulModelContentList"
                                    class="col-lg-9 col-md-9 col-sm-9 col-9 tab-content">
                                    <div class="tab-pane fade"
                                        v-for="model_display_text_item in model_display_text_list"
                                        :key="model_display_text_item.id"
                                        :id="model_display_text_item.model + 'Content'"
                                        role="tabpanel"
                                        :aria-labelledby="model_display_text_item.model + '-tab'">
                                        <ul :id="'ul' + model_display_text_item.model + 'PropertyList'"
                                            class="ulModelPropertyList">
                                            <li class="liModelProperty"
                                                v-for="model_property_item in getModelPropertyList(model_display_text_item.model)"
                                                :key="model_property_item.id"
                                                @click="addToDisplayText(model_property_item.id)"
                                                :id="'liModelProperty' + model_property_item.id"
                                                :data-display-text="model_property_item.model + '/' + model_property_item.property">
                                                {{ model_property_item.property }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between show_by_permission_must_update">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal">{{ $t('Close') }}</button>
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
    </div>
</template>

<script>

export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            model_property_list: [],
            model_display_text_list: [],
            form: new Form({
                'model': '',
                'display_text_json': ''
            }),
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
                is_model_property_list_loading: false,
                is_model_property_list_loaded: false,
                is_model_display_text_list_loading: false,
                is_model_display_text_list_loaded: false,
                is_post_success: false
            }
        };
    },
    methods: {
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

            if (!this.page.is_variables_loaded && !this.page.is_model_display_text_list_loaded && !this.page.is_model_property_list_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_model_display_text_list_loaded) {
                    this.loadModelDisplayText();
                }

                if (this.page.is_model_property_list_loaded) {
                    this.$nextTick(function () {
                        this.initializePage();
                    });

                    this.$Progress.finish();
                    this.page.is_ready = true;
                } else {
                    this.loadModelPropertyList();
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
        loadModelPropertyList: function () {
            if (this.page.is_model_property_list_loading) {
                return;
            }

            this.page.is_model_property_list_loading = true;

            axios.get(WisiloHelper.getAPIURL("__modeldisplaytext/get_model_property_list"))
                .then(({ data }) => {
                    this.page.is_model_property_list_loaded = true;
                    this.page.is_model_property_list_loading = false;
                    this.model_property_list = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_model_property_list_loaded = true;
                    this.page.is_model_property_list_loading = false;
                    this.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        loadModelDisplayText: function () {
            if (this.page.is_model_display_text_list_loading) {
                return;
            }

            this.page.is_model_display_text_list_loading = true;

            axios.get(WisiloHelper.getAPIURL("__modeldisplaytext/get"))
                .then(({ data }) => {
                    this.page.is_model_display_text_list_loaded = true;
                    this.page.is_model_display_text_loading = false;
                    this.model_display_text_list = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_model_display_text_list_loaded = true;
                    this.page.is_model_display_text_list_loading = false;
                    this.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        initializePage: function () {
            $("#mainVueApplication").data("wisiloModelDisplayTextPage", this);

            this.initializeModelAttributeList();
        },
        initializeModelAttributeList: function () {
            $("#ulModelList a:first-child").tab("show")
        },
        getModelPropertyList: function (model) {
            var propertyCount = this.model_property_list.length;
            var propertyList = [];
            for (var i = 0; i < propertyCount; i++) {
                if (model == this.model_property_list[i].model) {
                    propertyList.push(this.model_property_list[i]);
                }
            }
            return propertyList;
        },
        addToDisplayText: function (id) {
            var sender = document.getElementById("liModelProperty" + id);
            var append = "{{" + sender.getAttribute("data-display-text") + "}}";
            var old_text = $("#formEditDisplayText-display_text").summernote("code");
            $("#formEditDisplayText-display_text").summernote("code", (old_text + append));
            $("#modal-ModelProportyList").modal('hide');
        },
        showModelDisplayTextList: function (object) {
            document.getElementById("formModelDisplayText-id").value = object["id"];
            document.getElementById("formModelDisplayText-model").value = object["model"];
            document.getElementById("formModelDisplayText-display_text_json").value = object["display_text_json"];
            this.initializeModelPropertyDisplayTextList(object["display_text_json"]);
            $("#modal-ModelDisplayTextList").modal();
        },
        showModelPropertyListModal: function () {
            $('#modal-ModelProportyList').modal();
        },
        initializeModelPropertyDisplayTextList: function (display_text_json) {
            var tbodyElement = document.getElementById("tbodyModelDisplayText");
            tbodyElement.innerHTML = "";

            if ("" == display_text_json) {
                return false;
            }
            
            var templateHTML = document.getElementById("trEditDisplayTextTemplate").innerHTML;
            var trHTML = "";
            var tbodyHTML = "";
            var display_text_jsonJSON = decodeURIComponent(display_text_json);
            var display_texts = JSON.parse(display_text_jsonJSON);
            var display_text = "";
            var decoded_display_text = "";
            var sh_class = "";
            var anti_sh_class = "";
            var tr_class = "";
            var warning = "";
            var index = 1;

            var trElement = null;

            for (var property in display_texts) {
                display_text = display_texts[property];
                decoded_display_text = WisiloHelper.decodeHTMLEntities(display_text);
                sh_class = this.get_type_sh_class(property);
                
                if ("" == sh_class) {
                    tr_class = "trEditDisplayText";
                    warning = "";
                    anti_sh_class = "d-none";
                } else {
                    tr_class = "";
                    warning = "This type property display text not editable.";
                    anti_sh_class = "";
                }

                trHTML = templateHTML;
                trHTML = trHTML.replace(/__PROPERTY__/g, property)
                        .replace(/__DISPLAY_TEXT__/g, decoded_display_text)
                        .replace(/__INDEX__/g, index)
                        .replace(/__SH_CLASS__/g, sh_class)
                        .replace(/__ANTI_SH_CLASS__/g, anti_sh_class);
                
                trElement = document.createElement("TR");
                trElement.id = "trEditDisplayText" + index;
                trElement.className = tr_class;
                trElement.setAttribute("data-index", index);
                trElement.title = warning;

                trElement.innerHTML = trHTML;
                $(trElement).data("property", property);
                $(trElement).data("display_text", display_text);
                tbodyElement.appendChild(trElement);

                index++;
            }
            
            $(".trEditDisplayText").off("click").on("click", function () {
                var component = $("#mainVueApplication").data("wisiloModelDisplayTextPage");
                component.showEditDisplayTextModal(this);
            });
        },
        get_type_sh_class: function (property) {
            var sh_class = "";
            var exceptions = ["image", "file", "selection_single", "selection_multiple", "radio", "location"];
            var model = document.getElementById("formModelDisplayText-model").value;
            var trList = this.model_property_list;
            var trLength = trList.length;
            var rowId = 0;
            var result_found = false;

            for (var i = 0; i < trLength; i++) {
                if (result_found) {
                    break;
                }

                rowId = trList[i]['id'];
                result_found = (model == trList[i]['model']);
                result_found = result_found && (property == trList[i]['property']);
                if (result_found) {
                    if (-1 != exceptions.indexOf(trList[i]['type'])) {
                        sh_class = "d-none";
                    }
                }
            }

            return sh_class;
        },
        showEditDisplayTextModal: function (sender) {
            var index = sender.getAttribute("data-index");
            var property = $(sender).data("property");
            var display_text = $(sender).data("display_text");

            var decoded_display_text = WisiloHelper.decodeHTMLEntities(display_text);

            document.getElementById("formEditDisplayText-index").value = index;
            document.getElementById("formEditDisplayText-property").value = property;
            
            $("#formEditDisplayText-display_text").summernote({
                "font-styles": false,
                "height": 150,
                codemirror: {
                    theme: "monokai"
                }
            });

            $("#formEditDisplayText-display_text").summernote("code", decoded_display_text);

            $("#modal-EditDisplayText").modal();
        },
        saveFormEditDisplayText: function () {
            var index = document.getElementById("formEditDisplayText-index").value;
            var property = document.getElementById("formEditDisplayText-property").value;
            var display_text = $("#formEditDisplayText-display_text").summernote('code');

            document.getElementById("property_" + index).innerHTML = property;
            document.getElementById("display_text_" + index).innerHTML = display_text;

            var trEditDisplayText = document.getElementById("trEditDisplayText" + index);
            
            var encoded_display_text = WisiloHelper.encodeHTMLEntities(display_text);
            $(trEditDisplayText).data("property", property);
            $(trEditDisplayText).data("display_text", encoded_display_text);

            document.getElementById("updated-icon-" + index).className = "fas fa-check icon-updated-item";

            $("#modal-EditDisplayText").modal('hide');
        },
        saveFormModelDisplayText: function () {
            var self = this;
            var objectId = parseInt(document.getElementById("formModelDisplayText-id").value);
            var object = self.model_display_text_list[objectId - 1];
            var model = object.model;
          
            var trEditDisplayTextList = $(".trEditDisplayText");
            var countTR = trEditDisplayTextList.length;
            var displayTextList = [];
            var objectDisplayText = {};
            var property = "";
            var display_text = "";

            for (var i = 0; i < countTR; i++) {
                property = $(trEditDisplayTextList[i]).data("property");
                display_text = $(trEditDisplayTextList[i]).data("display_text");

                objectDisplayText = {};
                objectDisplayText[property] = WisiloHelper.encodeHTMLEntities(display_text);

                displayTextList.push(objectDisplayText);
            }

            self.form.model = model;
            self.form.display_text_json = JSON.stringify(displayTextList);
            
            var self = this;
            self.$Progress.start();
            self.form.post(WisiloHelper.getAPIURL("__modeldisplaytext/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
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
                                timerProgressBar: true
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
        }
    },
    mounted() {
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
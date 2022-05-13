<template>
    <div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-query'">
                        Data Source
                    </a>
                </h4>
            </div>
            <div class="collapse"
                :id="instance_id + '-accordion-query'"
                :data-parent="'#' + instance_id + '-accordion'">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-4 clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" 
                                    :id="instance_id + 'calculation_type1'" 
                                    :name="instance_id + 'calculation_type'" 
                                    value="simple"
                                    checked>
                                <label class="detail-label" :for="instance_id + 'calculation_type1'">Simple</label>
                            </div>
                        </div>
                        <div class="form-group col-4 clearfix">
                            <div class="icheck-primary d-inline">
                                <input type="radio" 
                                    :id="instance_id + 'calculation_type2'" 
                                    :name="instance_id + 'calculation_type'"
                                    value="advanced">
                                <label class="detail-label" :for="instance_id + 'calculation_type2'">SQL Query</label>
                            </div>
                        </div>
                    </div>
                    <div v-show="'simple' == calculation_type" class="row">
                        <div class="form-group col-lg-12">
                            <label :for="instance_id + '__ds_simple_model'" class="detail-label">{{ $t('Model') }}</label>
                            <select :id="instance_id + '__ds_simple_model'"  class="form-control">
                                <option v-for="(model, index) in model_options" :key="index" :value="model.id">
                                    {{model.text}}
                                </option>
                            </select>
                        </div>

                        <div class="form-group col-lg-12">
                            <table class="table table-bordered table-hover table-sm field-table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ $t('Fields') }}
                                            <button type="button" 
                                                title="Add Field" 
                                                class="btn-icon btn-icon-primary float-right btn-add-field" 
                                                @click="addField">
                                                <span class="btn-label btn-label-right"><i class="fas fa-plus"></i> Add</span>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody :id="instance_id + '__ds_simple_fields'"></tbody>
                            </table>
                        </div>

                        <div class="form-group col-lg-12">
                            <table class="table table-bordered table-hover table-sm field-table">
                                <thead>
                                    <tr>
                                        <th colspan="2">
                                            {{ $t('Conditions') }}
                                            <button type="button" 
                                                title="Add Condition" 
                                                class="btn-icon btn-icon-primary float-right btn-add-field" 
                                                @click="showConditionDialog">
                                                <span class="btn-label btn-label-right"><i class="fas fa-plus"></i> Add</span>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody :id="instance_id + '__ds_simple_conditions'"></tbody>
                            </table>
                        </div>

                        <div class="form-group col-lg-12">
                            <table class="table table-bordered table-hover table-sm field-table">
                                <thead>
                                    <tr>
                                        <th>
                                            {{ $t('Order') }}
                                            <button type="button" 
                                                title="Add Order Field" 
                                                class="btn-icon btn-icon-primary float-right btn-add-field" 
                                                @click="showOrderFieldsDialog">
                                                <span class="btn-label btn-label-right"><i class="fas fa-plus"></i> Add</span>
                                            </button>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td :id="instance_id + '__fo__selectedorders'">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div>
                            <b>Pagination</b><br>
                            Page: Integer<br>
                            Records per Page: Integer<br>
                        </div>
                    </div>
                    
                    <div v-show="'advanced' == calculation_type" class="row">
                        <div class="form-group col-lg-12 col-md-12 col-sm-12">
                            <label :for="instance_id + 'query'" class="detail-label">
                                {{ $t('SQL Query') }}
                                <insert-variable-button 
                                    :variable_options="['global_parameters','user_parameters','url_parameters','request_parameters']" 
                                    :target="instance_id + 'query'">
                                </insert-variable-button>
                            </label>
                            <textarea rows="5"
                                :id="instance_id + 'query'"
                                :name="instance_id + 'query'"
                                class="form-control"
                                style="font-family: monospace;font-size: 15px;"></textarea>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div :id="instance_id + '__ds__orders_dialog'" class="modal level2 fade" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Order Fields') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <table class="table table-bordered table-hover table-sm field-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                {{ $t('Order') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody :id="instance_id + '__ds_simple_orders'"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modalfooter justify-content-between">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="__ds__orders__buttonSave"
                                    @click="doSaveOrderFields"
                                    data-instance-id=""
                                    class="btn btn-success btn-md btn-on-table float-right">
                                    {{ $t('Save') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
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
    props: ["instance_id"],
    data() {
        return {
            model_options: [],
            property_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_model_options_loading: false,
                is_model_options_loaded: false,
                is_property_options_loading: false,
                is_property_options_loaded: false,
            },
            calculation_type: "",
        };
    },
    methods: {
            processLoadQueue: function () {
                var self = this;
                
                if (self.page.has_server_error) {
                    self.$Progress.finish();
                    self.page.is_ready = true;
                    return;
                }

                if (!self.page.is_model_options_loaded) {
                    self.load_model_options();
                } else {
                    self.initializePage();
                }
            },
            load_model_options: function() {
                var self = this;
                if (self.page.is_model_options_loading) {
                    return;
                }

                self.page.is_model_options_loading = true;
                
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_model_list"))
                    .then(({ data }) => {
                        self.page.is_model_options_loaded = true;
                        self.page.is_model_options_loading = false;
                        self.model_options = data.list;
                        self.processLoadQueue();
                    }).catch(({ data }) => {
                        self.page.is_model_options_loaded = true;
                        self.page.is_model_options_loading = false;
                        self.$Progress.fail();
                        self.page.has_server_error = true;
                        self.processLoadQueue();
                    });
            },
            load_property_options: function(model) {
                var self = this;
                if (self.page.is_property_options_loading) {
                    return;
                }

                self.page.is_property_options_loading = true;

                axios.get(AdminLTEHelper.getAPIURL("__layout/get_model_properties/" + model))
                    .then(({ data }) => {
                        self.page.is_property_options_loaded = true;
                        self.page.is_property_options_loading = false;
                        self.property_options = data.list;
                    }).catch(({ data }) => {
                        self.page.is_property_options_loaded = true;
                        self.page.is_property_options_loading = false;
                        self.$Progress.fail();
                        self.processLoadQueue();
                    }).finally(function() {
                        let elSelect = document.getElementById(self.instance_id + "property");
                        $(elSelect).val(elSelect.getAttribute("val")).trigger('change');
                        self.processLoadQueue();
                    });
            },
            initializePage: function() {
                var self = this;
                var instance_id = self.instance_id;

                var selectorText = 'input[name="' + instance_id + 'calculation_type"]';
                $(selectorText).change(function() {
                    self.calculation_type = this.value;
                });

                $(document.getElementById(instance_id + "model")).off("change").on("change", function (e) {
                    self.load_property_options(this.value);
                });
            },
            setValues: function() {
                var self = this;
                var instance_id = self.instance_id;
                var data = window.mainLayoutInstance.pageWidgets[instance_id].data;

                var data_source = {};

                if (undefined !== data.data_source) {
                    data_source = data.data_source;
                }

                // Value
                var selectorText = 'input[name="' + instance_id + 'calculation_type"][value="' + data_source.calculation_type + '"]';
                $(selectorText).prop('checked', true);

                self.calculation_type = data_source.calculation_type;

                 if ("simple" == data_source.calculation_type) {
                    $(document.getElementById(instance_id + "__ds_simple_model")).val(data_source.meta_data.model).trigger('change');

                    if (data_source.meta_data.fields.length > 0) {
                        window.__ds_simple__fields.renderFields(instance_id, data_source.meta_data.fields);
                    }

                    if (data_source.meta_data.conditions.length > 0) {
                        window.__ds_simple__condition.renderConditions(instance_id, data_source.meta_data.conditions);
                    }

                    self.setOrderFields(data_source.meta_data.order_fields);
                } else if ("advanced" == data_source.calculation_type) {
                    document.getElementById(instance_id + "query").value = data_source.meta_data.query;
                }
            },
            getValues: function() {
                var instance_id = this.instance_id;
                var data_source = {};
                var radioSelectorText = 'input[name="' + instance_id + 'calculation_type"]:checked';
                var calculation_type = $(radioSelectorText).val();

                if ("simple" == calculation_type) {
                    data_source = {
                        "calculation_type": calculation_type,
                        "meta_data" : {
                            "model": document.getElementById(instance_id + "__ds_simple_model").value,
                            "fields": window.__ds_simple__fields.collectFieldData(instance_id),
                            "conditions": window.__ds_simple__condition.getConditionData(instance_id),
                            "order_fields": this.collectOrderFieldsData(),
                            "query": ""
                        }
                    }
                } else if ("advanced" == calculation_type) {
                    data_source = {
                        "calculation_type": calculation_type,
                        "meta_data" : {
                            "query" : document.getElementById(instance_id + "query").value,
                        }
                    }
                }

                return data_source;
            },
            addField: function() {
                var instance_id = this.instance_id;
                window.__ds_simple__fields.addField(instance_id);
            },
            showConditionDialog: function () {
                window.__ds_simple__condition.showConditionDialog();
            },
            showOrderFieldsDialog: function() {
                $(document.getElementById(this.instance_id + "__ds__orders_dialog")).modal();
            },
            doSaveOrderFields: function() {
                var instance_id = this.instance_id;
                var arrCheckedBoxes = $(".select__fo__:checked", document.getElementById(instance_id + "__ds_simple_orders"));
                var orderFieldsHTML = "";

                for (let index = 0; index < arrCheckedBoxes.length; index++) {
                    let data = arrCheckedBoxes[index].id.split("-__fo__");

                    let field_guid = data[0];
                    let type = data[1];
                    let label = arrCheckedBoxes[index].getAttribute("data-label");

                    let typeText = ("asc" == type) ? "ASC" : "DESC";

                    let orderFieldHTML = "<span class=\"span_selected_order_fields\" id=\"" + field_guid + "__selected_fo__-" + type + "\""
                        + " data-guid=\""+ field_guid + "\""
                        + " data-type=\""+ type + "\""
                        + " data-label=\""+ label + "\""
                        + ">"
                        + label 
                        + " <b>" + typeText + "</b>"
                        + "</span>";

                    orderFieldsHTML += orderFieldHTML;
                }

                document.getElementById(instance_id + "__fo__selectedorders").innerHTML = orderFieldsHTML;

                $(document.getElementById(this.instance_id + "__ds__orders_dialog")).modal("hide");
            },
            setOrderFields: function(order_fields) {
                var instance_id = this.instance_id;

                var orderFieldsHTML = "";
                for (let index = 0; index < order_fields.length; index++) {
                    let order_data = order_fields[index];

                    let field_guid = order_data.field_guid;
                    let type = order_data.type;
                    let label = order_data.label;

                    let checkboxId = field_guid + "-__fo__" + type;
                    if (document.getElementById(checkboxId)) {
                        document.getElementById(checkboxId).checked = true;
                    }

                    let typeText = ("asc" == type) ? "ASC" : "DESC";

                    let orderFieldHTML = "<span class=\"span_selected_order_fields\" id=\"" + field_guid + "__selected_fo__-" + type + "\""
                        + " data-guid=\""+ field_guid + "\""
                        + " data-type=\""+ type + "\""
                        + " data-label=\""+ label + "\""
                        + ">"
                        + label 
                        + " <b>" + typeText + "</b>"
                        + "</span>";

                    orderFieldsHTML += orderFieldHTML;
                }

                document.getElementById(instance_id + "__fo__selectedorders").innerHTML = orderFieldsHTML;
            },
            collectOrderFieldsData: function() {
                var arrSpans = $("span", document.getElementById(this.instance_id + "__fo__selectedorders"));
                var orders_data = [];

                for (let index = 0; index < arrSpans.length; index++) {
                    let elSpan = arrSpans[index];

                    let order_data = {
                        "field_guid" : elSpan.getAttribute("data-guid"),
                        "type" : elSpan.getAttribute("data-type"),
                        "label" : elSpan.getAttribute("data-label")
                    }

                    orders_data.push(order_data);
                }

                return orders_data;
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].data_source = this;
            this.processLoadQueue();
        }
}
</script>
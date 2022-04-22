<template>
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
                    <div class="form-group col-4 clearfix">
                        <div class="icheck-primary d-inline">
                            <input type="radio" 
                                :id="instance_id + 'calculation_type3'" 
                                :name="instance_id + 'calculation_type'"
                                value="extreme">
                            <label class="detail-label" :for="instance_id + 'calculation_type3'">Extreme</label>
                        </div>
                    </div>

                </div>
                <div v-show="'simple' == calculation_type" class="row">
                    <div class="form-group col-lg-12">
                        <label :for="instance_id + 'function'" class="detail-label">{{ $t('Function') }}</label>
                        <select :id="instance_id + 'function'" class="form-control">
                            <option value="avg">AVG</option>
                            <option value="count">COUNT</option>
                            <option value="first">FIRST</option>
                            <option value="last">LAST</option>
                            <option value="max">MAX</option>
                            <option value="min">MIN</option>
                            <option value="sum">SUM</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-12">
                        <label :for="instance_id + 'model'" class="detail-label">{{ $t('Model') }}</label>
                        <select :id="instance_id + 'model'"  class="form-control">
                            <option v-for="(model, index) in model_options" :key="index" :value="model.id">
                                {{model.text}}
                            </option>
                        </select>
                    </div>
                    <div class="form-group col-lg-12">
                        <label :for="instance_id + 'property'" class="detail-label">{{ $t('Property') }}</label>
                        <select :id="instance_id + 'property'"  class="form-control">
                            <option v-for="(property, index) in property_options" :key="index" :value="property.id">
                                {{property.text}}
                            </option>
                        </select>
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
                <div v-show="'extreme' == calculation_type" class="row">
                    <div class="form-group col-lg-12">
                        <label :for="instance_id + '__ds_extreme_model'" class="detail-label">{{ $t('Model') }}</label>
                        <select :id="instance_id + '__ds_extreme_model'"  class="form-control">
                            <option v-for="(model, index) in model_options" :key="index" :value="model.id">
                                {{model.text}}
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-lg-12">
                        <input type="hidden" :id="instance_id + '__ds_extreme_fields'" class="item-widget">
                        <table class="table table-bordered table-hover table-sm field-table">
                            <thead>
                                <tr>
                                    <th>
                                        {{ $t('Fields') }}
                                        <button type="button" title="Add Field" class="btn-icon btn-icon-primary btn-add-field"
                                            :id="instance_id + '__ds_extreme_addField'" @click="addField">
                                            <span class="btn-label btn-label-right"><i class="fas fa-plus"></i></span>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody :id="instance_id + '__ds_extreme_fields'"></tbody>
                        </table>
                    </div>
                    
                    
                    <div>
                        <b>Query</b><br>
                        JSQueryBuilder ile where clause kısmını oluşturacağız<br>
                    </div>
                    <div>
                        <b>Order</b><br>
                        Dropdown, Multiple Selection, CustomVariable (Ascending), CustomVariable (Descending)<br>
                    </div>
                    <div>
                        <b>Pagination</b><br>
                        Page: Integer<br>
                        Records per Page: Integer<br>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th><span>Variables</span>&nbsp;</th> 
                                    <th style="width: 70px;"></th>
                                </tr>
                            </thead>
                            <tbody id="tbodyVariableList">
                                <tr>
                                    <td>
                                        <span>Product Price (productPrice)</span>
                                    </td> 
                                    <td>
                                        <button type="button" class="btn-icon btn-icon-primary" style="margin-bottom: 0px;">
                                            <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                        </button> 
                                        <button type="button" class="btn-icon btn-icon-danger">
                                            <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span>Product Name (productName)</span></td>
                                    <td>
                                        <button type="button" class="btn-icon btn-icon-primary" style="margin-bottom: 0px;">
                                            <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                        </button>
                                        <button type="button" class="btn-icon btn-icon-danger">
                                            <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                    $(document.getElementById(instance_id + "model")).val(data_source.meta_data.model).trigger('change');
                    $(document.getElementById(instance_id + "property")).val(data_source.meta_data.property).trigger('change');
                    document.getElementById(instance_id + "property").setAttribute("val", data_source.meta_data.property);
                    $(document.getElementById(instance_id + "function")).val(data_source.meta_data.function).trigger('change');
                } else if ("advanced" == data_source.calculation_type) {
                    document.getElementById(instance_id + "query").value = data_source.meta_data.query;
                } if ("extreme" == data_source.calculation_type) {
                    $(document.getElementById(instance_id + "__ds_extreme_model")).val(data_source.meta_data.model).trigger('change');

                    if (data_source.meta_data.fields.length > 0) {
                        window.__ds_fields.renderFields(instance_id, data_source.meta_data.fields);
                    }
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
                            "model": document.getElementById(instance_id + "model").value,
                            "property" : document.getElementById(instance_id + "property").value,
                            "function" : document.getElementById(instance_id + "function").value,
                        }
                    }
                } else if ("advanced" == calculation_type) {
                    data_source = {
                        "calculation_type": calculation_type,
                        "meta_data" : {
                            "query" : document.getElementById(instance_id + "query").value,
                        }
                    }
                } else if ("extreme" == calculation_type) {
                    data_source = {
                        "calculation_type": calculation_type,
                        "meta_data" : {
                            "model": document.getElementById(instance_id + "__ds_extreme_model").value,
                            "fields": window.__ds_fields.collectFieldData(instance_id),
                            "query": ""
                        }
                    }
                }

                return data_source;
            },
            addField: function() {
                var instance_id = this.instance_id;
                window.__ds_fields.addField(instance_id);
            }
        },
        mounted() {
            window.mainLayoutInstance.pageWidgets[this.instance_id].data_source = this;
            this.processLoadQueue();
        }
}
</script>
<template>
    <div>
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h4 class="card-title w-100">
                    <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-variable-mapping'">
                        Variable Mapping
                    </a>
                </h4>
            </div>
            <div class="collapse"
                :id="instance_id + '-variable-mapping'"
                :data-parent="'#' + instance_id + '-accordion'">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <button type="button" 
                                title="Add VariableMapping" 
                                class="btn btn-primary btn-sm text-white float-right btn-add-field" 
                                style="margin-top: -10px;"
                                @click="addVariableMapping">
                                <i class="fas fa-plus"></i>&nbsp;Add
                            </button>
                        </div>
                        <div class="form-group col-lg-12">
                            <table class="table table-bordered table-hover table-sm field-table">
                                <tbody :id="instance_id + '__variable_mapping_list__'"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["instance_id", "local_variables"],
    methods: {
        addVariableMapping: function() {
            window.__variable_mapping_edit__.addVariableMapping(this.instance_id);
        }
    },
    mounted() {
        window.mainLayoutInstance.pageWidgets[this.instance_id].variable_mapping = this;
        window.mainLayoutInstance.pageWidgets[this.instance_id].variable_mapping.local_variables = this.local_variables;
    }
}
</script>
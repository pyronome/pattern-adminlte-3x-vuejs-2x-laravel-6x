<template>
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h4 class="card-title w-100">
                <a class="d-block w-100" data-toggle="collapse" :href="'#' + instance_id + '-accordion-csettings'">
                    Conditional Settings
                </a>
            </h4>
        </div>
        <div class="collapse"
            :id="instance_id + '-accordion-csettings'"
            :data-parent="'#' + instance_id + '-accordion'">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <button type="button" 
                            class="btn btn-primary btn-sm text-white float-right btn-add-condition" 
                            @click="showConditionDialog()"
                            style="margin-top:-10px;">
                            <i class="fas fa-plus"></i>&nbsp;Add
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-12" :id="instance_id + '-conditionlist'">
                        <table class="table table-striped table-bordered table-hover table-sm">
                            <thead>
                                <tr>
                                    <th colspan="2">
                                        Product/price > 1000
                                    </th>
                                    <th width="60">
                                        <button type="button" title="Edit Condition" class="btn-icon btn-icon-primary">
                                            <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                        </button>
                                        <button type="button" title="Remove Condition" class="btn-icon btn-icon-danger">
                                            <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                                        </button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="25%">
                                        Title
                                    </td>
                                    <td>
                                        Warning
                                    </td>
                                    <td>
                                        <button type="button" title="Edit Value" class="btn-icon btn-icon-primary">
                                            <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        iconbackground
                                    </td>
                                    <td>
                                        #2CCC34
                                    </td>
                                    <td>
                                        <button type="button" title="Edit Value" class="btn-icon btn-icon-primary">
                                            <span class="btn-label btn-label-right"><i class="fas fa-pen"></i></span>
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
    props: ["instance_id", "conditional_fields"],
    /* watch: {
        instance_id: function(instance_id) {
            console.log("watch:" + instance_id)
            this.renderConditionList(instance_id);
        },
        conditional_fields: function(conditional_fields) {
            console.log("watch conditional_fields:" + instance_id)
        }
    }, */
    /* data() {
        return {
            state: this.data.general.enabled
        };
    },*/
    methods: {
        showConditionDialog: function () {
            window.widgetConditionDialog.showConditionDialog(this.instance_id, this.conditional_fields);
        }
    }, 
    mounted() {
    }
}
</script>
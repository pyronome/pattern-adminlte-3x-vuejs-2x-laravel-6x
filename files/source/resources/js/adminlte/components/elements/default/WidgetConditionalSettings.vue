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
                    <div class="form-group col-lg-12" :id="instance_id + '-conditionlist'"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["instance_id", "conditional_fields"],
    methods: {
        showConditionDialog: function () {
            window.__condition_dialog.showConditionDialog(this.conditional_fields);
        }
    }, 
    mounted() {
    }
}
</script>
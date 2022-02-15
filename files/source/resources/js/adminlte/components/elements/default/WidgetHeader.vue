<template>
    <div style="display:inline-block;">
        <button type="button" class="btn-icon btn-icon-primary btn-move-widget">
            <span class="btn-label btn-label-right">
                <i class="fas fa-arrows-alt"></i>
            </span>                    
        </button>
        <button type="button" 
            class="btn-icon btn-icon-primary btn-toggle-widget"
            @click="toggleWidget()">
            <span class="btn-label btn-label-right">
                <i class="fas fa-eye"></i>
            </span>                    
        </button>
        <button type="button"
            class="btn-icon btn-icon-primary btn-edit-widget"
            @click="editWidget()">
            <span class="btn-label btn-label-right">
                <i class="fas fa-cog"></i>
            </span>                    
        </button>
        <button type="button" 
            class="btn-icon btn-icon-primary btn-remove-widget"
            @click="removeWidget()">
            <span class="btn-label btn-label-right">
                <i class="fas fa-times"></i>
            </span>                    
        </button>
    </div>
</template>

<script>
export default {
    props: ['instance_id'],
    watch: {
        instance_id: function() {
            this.initializePage();
        }
    },
    methods: {
        initializePage: function() {
            console.log("widget header init");
        },
        toggleWidget: function () {
            $("#btnSaveWidgets").removeClass("d-none");

            var instance_id = this.instance_id;
            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            var enabled = widgets_form_data[instance_id]["general_data"]["enabled"];

            if (0 == enabled) {
                enabled = 1;
                document.getElementById("container-" + instance_id).style.opacity = "1";
            } else {
                enabled = 0;
                document.getElementById("container-" + instance_id).style.opacity = "0.3";
            }

            widgets_form_data[instance_id]["general_data"]["enabled"] = enabled;
            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);
        },
        removeWidget: function () {
            $("#btnSaveWidgets").removeClass("d-none");

            var instance_id = this.instance_id;

            document.getElementById("container-" + instance_id).remove();

            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            delete widgets_form_data[instance_id];
            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);
        },
        editWidget: function () {
            $("#btnSaveWidgets").removeClass("d-none");

            var instance_id = this.instance_id;

            this.$root.$emit("fill-widget-form-values", instance_id);

            var modal = document.getElementById(instance_id + "ModalSettings");
            $(modal).modal();
        },
    }
}
</script>
<template>
    <div class="widget-header-wrapper">
        <div class="widget-sortable-handle widget-move-handle">
            <span><i class="fa fa-ellipsis-v" aria-hidden="true"></i><i class="fa fa-ellipsis-v" aria-hidden="true"></i></span>
        </div>
        <div class="widget-header-text widget-move-handle" :id="this.instance_id + '-header-text'">{{data.general.title}}</div>
        <div class="widget-settings-menu-container">
            <button type="button" class="btn btn-flat btn-xs dropdown-toggle" data-toggle="dropdown" data-offset="-10" aria-expanded="false">
                <i class="fa fa-cog" aria-hidden="true"></i>
            </button>
            <div class="dropdown-menu" role="menu" style="">
                <button type="button" class="dropdown-item btn-toggle-widget"
                    @click="toggleWidget()">
                    <span v-show="!state">Enabled</span>
                    <span v-show="state">Disabled</span>
                </button>
                <div class="dropdown-divider"></div>
                <button type="button" class="dropdown-item btn-copy-widget"
                    @click="copyWidget()">
                    Duplicate
                </button>
                <div class="dropdown-divider"></div>
                <button type="button" class="dropdown-item btn-remove-widget"
                    @click="removeWidget()">
                    Remove
                </button>
                <div class="dropdown-divider"></div>
                <button type="button" class="dropdown-item btn-edit-widget"
                    @click="editWidget()">
                    Settings...
                </button>
            </div>
        </div>
        <div class="widget-remove-button-container">
            <button type="button" class="btn btn-flat btn-xs" aria-expanded="false"
                @click="removeWidget()">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    props: ["instance_id", "data"],
    data() {
        return {
            state: this.data.general.enabled
        };
    },
    methods: {
        toggleWidget: function () {
            $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");

            var instance_id = this.instance_id;
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;
            var enabled = data.general.enabled;

            if (0 == enabled) {
                enabled = 1;
                $(document.getElementById("container-" + instance_id)).removeClass("widget-disabled");
            } else {
                enabled = 0;
                $(document.getElementById("container-" + instance_id)).addClass("widget-disabled");
            }

            this.state = (1 == enabled);

            data.general.enabled = enabled;
            window.mainLayoutInstance.pageWidgets[instance_id].data = data;
        },
        removeWidget: function () {
            $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");

            document.getElementById("container-" + this.instance_id).remove();
        },
        editWidget: function () {
            var instance_id = this.instance_id;

            $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");

            window.mainLayoutInstance.pageWidgets[instance_id].general_settings.setWidgetFormValues(instance_id);

            document.getElementById(instance_id + "general-tab").click();

            var modal = document.getElementById(instance_id + "ModalSettings");
            $(modal).modal();
        },
        copyWidget: function() {
            $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");

            var instance_id = this.instance_id;
            var data = window.mainLayoutInstance.pageWidgets[instance_id].data;
            window.mainLayoutInstance.vueComponent.copyWidget(data, data.general.widget);
        }
    },
    mounted() {
    }
}
</script>
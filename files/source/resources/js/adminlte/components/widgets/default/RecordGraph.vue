<template>
    <div class="widgetcomponent">
        <div class="card collapsed-card">
            <div class="card-header">
                <h3 class="card-title">{{data.text}}</h3>

                <div class="card-tools">
                    <button type="button" :id="'buttonToggleWidgetRecordGraph' + model" class="btn btn-tool buttonSHWidget" 
                        :state="state" data-card-widget="collapse" v-on:click="toggleWidget" ref="toggleWidget">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" style="display:none;min-height:250px;">
                <canvas :id="this.model + 'RecordGraphContainer'"></canvas>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                model: this.$attrs.model,
                data: [],
                cookie_suffix: ('widgetRecordGraph' + this.$attrs.model),
                state: 0
            };
        },
        methods: {
            loadData: function () {
                axios.get(AdminLTEHelper.getAPIURL("__layout/get_recordgraph/" + this.$attrs.pagename + "/" + this.model))
                    .then(({ data }) => {
                        this.data = data;
                        this.$nextTick(function () {
                            initializeRecordGraphChart(this.model, data.graphJSON);
                            this.initializeWidget();
                        });
                        
                    }).catch(({ data }) => {
                        this.$Progress.fail();
                    });
            },
            toggleWidget: function() {
                this.state = (1 == this.state) ? 0 : 1;
                setWidgetState(this.cookie_suffix, this.state);
            },
            initializeWidget: function() {
                this.state = getWidgetState(this.cookie_suffix);
                
                if (1 == this.state) {
                    $("#buttonToggleWidgetRecordGraph" + this.model).parent().parent().parent().CardWidget('expand');
                }
            }
        },
        mounted() {
            this.loadData();
            
            var eventName = "refresh" + this.model + "Data";
            this.$root.$on(eventName, () => {
                this.loadData();
            });

            this.$nextTick(() => {
                this.$root.$emit("widget-rendered", this.model, 'recordgraph');
            });
        },
        updated() {
            
        }
    }
</script>
<template>
    <div id="widgetsContainer" class="row">
        <component v-for="widget in widgets" v-bind:is="widget.type" :key="widget.id" :pagename="pagename" :model="widget.model" :class="widget.size">
        </component>
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>
    import 'chart.js/dist/Chart.min.css';
    import 'chart.js/dist/Chart.min.js';
    import infobox from './default/InfoBox.vue';
    import recordgraph from './default/RecordGraph.vue';
    import recordlist from './default/RecordList.vue';
    import empty from './default/Empty.vue';

    export default {
        props: ['widgets', 'pagename'],
        data() {
            return {
                total_widget: 0,
                widget_counter: 0,
                body_loader_active: false
            };
        },
        watch: {
            widgets: function (widgets) {
                this.total_widget = this.widgets.length;
                this.widget_counter = 0;
            }
        },
        mounted() {
            var self = this;

            self.body_loader_active = true;

            self.$root.$on('widget-rendered', (model, type) => {
                self.widget_counter++;
                
                if (self.total_widget == self.widget_counter) {
                    setTimeout(function() {
                        self.body_loader_active = false;
                        self.$root.$emit("widgets-loaded");
                    }, 700);
                }
            });
        },
        methods: {
        },
        destroyed() {
        },
        components: {
            infobox,
            recordgraph,
            recordlist,
            empty
        }
    }
</script>
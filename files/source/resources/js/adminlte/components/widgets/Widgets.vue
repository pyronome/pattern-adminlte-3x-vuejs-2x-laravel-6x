<template>
    <div id="widgetsContainer" class="row">
        <component v-for="widget in widgets" v-bind:is="widget.type" :key="widget.id" :pagename="pagename" :model="widget.model" :class="widget.size">
        </component>
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
        <div :v-if="all_widgets_rendered">
            <page-variables :has_widgets="true"></page-variables>
        </div>
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
                body_loader_active: false,
                all_widgets_rendered: false,
            };
        },
        watch: {
            widgets: function (widgets) {
                this.total_widget = this.widgets.length;
                this.widget_counter = 0;
            }
        },
        mounted() {
            this.body_loader_active = true;
            /* let self = this;
                    setTimeout(function() {
                        self.body_loader_active = false;
                    }, 700); */
            this.$root.$on('widget-rendered', (model, type) => {
                console.log("model:" + model);
                console.log("type:" + type);
                this.widget_counter++;
                console.log("widgets rendered: " + this.widget_counter + "/" + this.total_widget);
                
                if (this.total_widget == this.widget_counter) {
                    let self = this;
                    setTimeout(function() {
                        self.body_loader_active = false;
                        self.all_widgets_rendered = true;
                        console.log("widget components loaded");
                    }, 700);
                }
            });            
        },
        methods: {
        },
        destroyed() {
            console.log("Widgets.vue destroyed");
            /* this.$root.$off('widget-rendered'); */
        },
        components: {
            infobox,
            recordgraph,
            recordlist,
            empty
        }
    }
</script>
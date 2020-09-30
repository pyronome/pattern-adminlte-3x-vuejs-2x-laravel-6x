<template>
    <div id="widgetsContainer" class="row">
        <component v-for="widget in widgets" v-bind:is="widget.type" :key="widget.id" :pagename="pagename" :model="widget.model">
        </component>
        <widget-loader :widget_loader_active="widget_loader_active"></widget-loader>
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
                widget_loader_active: false,
            };
        },
        watch: {
            widgets: function (widgets) {
                this.total_widget = this.widgets.length;
                this.widget_counter = 0;
            }
        },
        mounted() {
            this.widget_loader_active = true;
            
            this.$root.$on('widgetComponentRendered', () => {
                this.widget_counter++;
                
                if (this.total_widget == this.widget_counter) {
                    let self = this;
                    setTimeout(function() {
                        self.widget_loader_active = false;
                    }, 700);
                }
            });            
        },
        methods: {
            getPageWidgetsLength() {
                return this.widgets.length;
            }
        },
        destroyed() {
            console.log("Widgets.vue destroyed");
            this.$root.$off('widgetComponentRendered');
        },
        components: {
            infobox,
            recordgraph,
            recordlist,
            empty
        }
    }
</script>
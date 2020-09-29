<template>
    <div id="widgetsContainer" class="row">
        <component v-for="widget in activeWidgets" v-bind:is="widget.type" :key="widget.id" :pagename="pagename" :model="widget.model">
        </component>
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
        mounted() {
            console.log("Widgets.vue mounted")
        },
        computed: {
            activeWidgets: function () {
                return this.widgets.filter(widget => widget.visibility === "1")
            }
        },
        methods: {
        },
        destroyed() {
            console.log("Widgets.vue destroyed")
        },
        components: {
            infobox,
            recordgraph,
            recordlist,
            empty
        }
    }
</script>
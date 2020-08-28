<template>
    <div>
        <div v-if="infoboxColorActive">
            <infoboxColor></infoboxColor>
        </div>
        <div v-if="recordgraphColorActive">
            <recordgraphColor></recordgraphColor>
        </div>
        <div v-if="recordlistColorActive">
            <recordlistColor></recordlistColor>
        </div>
    </div>
</template>

<script>
import 'chart.js/dist/Chart.min.css';
import 'chart.js/dist/Chart.min.js';
import infoboxColor from './widgets/default/infobox/Color.vue';
import recordgraphColor from './widgets/default/recordgraph/Color.vue';
import recordlistColor from './widgets/default/recordlist/Color.vue';

export default {
    props: ['widgets'],
    data() {
        return {
            infoboxColorActive: false,
            recordgraphColorActive: false,
            recordlistColorActive: false
        }
    },
    mounted() {
        console.log("mounted")
    },
    watch: {
        widgets: function (widgets) {
            $("body").data("widgets", widgets);
            this.loadWidgets();
        }
    },
    methods: {
        loadWidgets: function () {
            this.widgets.forEach(element => {
                if("1" == element.visibility) {
                    this.activateWidget(element.type + element.model + "Active");
                }
            });            
        },
        activateWidget: function (state) {
            this[state] = true;
        }
    },
    destroyed() {
        console.log("destroyed")
    },
    components: {
        infoboxColor,
        recordgraphColor,
        recordlistColor
    }
}
</script>

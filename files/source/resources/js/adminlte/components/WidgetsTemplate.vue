<template>
    <div>
        <div class="empty-widget empty-widget-__type__">
        </div>
        <div class="__size__ infobox-__type__ widget-visible__visibility__" data-model="__model__">
            <div class="info-box clickable-infobox" data-href="/__href__">
                <span class="info-box-icon elevation-1" style="background-color:__iconbackground__;"><i class="__icon__"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">__text__</span>
                    <span class="info-box-number" id="__model__InfoboxValue">0</span>
                </div>
            </div>
            <div id="__model__InfoboxHTMLDBTableContainer" class="infobox-htmldb-table-container" data-row-id="__id__"></div>
        </div>
        <div class="__size__ recordgraph-__type__ widget-visible__visibility__" data-model="__model__">
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">__text__</h3>

                    <div class="card-tools">
                        <button type="button" id="buttonSHRecordGraph-__model__" class="btn btn-tool buttonSHWidget" data-display="no" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display:none;min-height:250px;">
                    <canvas id="__model__RecordGraphContainer"></canvas>
                </div>
            </div>
            <div id="__model__GraphHTMLDBTableContainer" class="recordgraph-htmldb-table-container" data-row-id="__id__"></div>
        </div>
        <div class="__size__ recordlist-__type__ widget-visible__visibility__" data-model="__model__">
            <div class="card collapsed-card">
                <div class="card-header">
                    <h3 class="card-title">__text__</h3>

                    <div class="card-tools">
                        <button type="button" id="buttonSHRecords-__model__" class="btn btn-tool buttonSHWidget" data-display="no" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body" style="display:none;">
                    <div id="__model__RecordListSearchContainer" class="recordlist-search-container" data-row-id="__id__"></div>
                    <div id="__model__RecordListContainer" class="recordlist-container __container_state__" data-row-id="__id__"></div>
                    <div id="__model__RecordListPaginationContainer" class="recordlist-pagination-container" data-row-id="__id__"></div>
                    <div class="showAllRecordContainer">
                        <a class="showAllRecord__onlylastrecord__" href="/__href__">{{ $t('View All Records') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import 'chart.js/dist/Chart.min.css';
import 'chart.js/dist/Chart.min.js';
export default {
    props: ['widgets'],
    mounted() {
        console.log("mounted")
    },
    watch: {
        widgets: function (widgets) {
            $("body").data("widgets", widgets);
            loadPageWidgets();
        }
    },
    destroyed() {
        console.log("destroyed")
    }
}
</script>

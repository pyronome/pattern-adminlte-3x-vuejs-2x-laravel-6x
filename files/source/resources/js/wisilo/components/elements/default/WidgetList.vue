<template>
    <div>
        <div class="modal fade" id="modalWidgetList" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Add Widget(s)') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-top:0px;">
                            <div class="row">
                                <div class="col-6 mt-3 mb-3">
                                    <select2-element class="select2-element"
                                        id="widget_page"
                                        name="widget_page"
                                        :options="widget_page_options"
                                        value="__global">
                                    </select2-element>
                                </div>
                                <div class="col-6 mt-3 mb-3">
                                    <div class="input-group input-group-md">
                                        <input type="search"
                                            id="searchNewWidget" name="searchNewWidget"
                                            class="form-control float-right inputSearchBar"
                                            v-bind:placeholderr="$t('Search')">
                                        <div class="input-group-append labelSearchBar">
                                            <button type="button" class="btn btn-default">
                                                <i class="fas fa-search text-primary"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th style="width:30px;">
                                                    <div class="icheck-primary m-0">
                                                        <input type="checkbox"
                                                            @click="select_all_widget($event.target)"
                                                            id="select_all_widget"
                                                            class="select_all_widget">
                                                        <label for="select_all_widget"></label>
                                                    </div>
                                                </th>
                                                <th>
                                                    <span>{{ $t('Widgets') }}</span>&nbsp;
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbodyWidgetList">
                                            <tr v-for="(widgetItem, index) in widgetList" :key="index"
                                                :data-search-text="widgetItem.title + widgetItem.name">
                                                <td>
                                                    <div class="icheck-primary m-0">
                                                        <input type="checkbox"
                                                            :id="'select_widget-' + widgetItem.guid"
                                                            class="select_widget"
                                                            :data-widget="widgetItem.data">
                                                        <label :for="'select_widget-' + widgetItem.guid"></label>
                                                    </div>
                                                </td>
                                                <td>
                                                   <span v-html="widgetItem.title + ' (' + widgetItem.name + ')'"></span>
                                                   <button v-if="widgetItem.has_preview"
                                                        type="button"
                                                        class="btn-icon btn-icon-primary"
                                                        @click="showPreview(widgetItem.name)"
                                                        style="margin-bottom:0;"
                                                        title="Preview">
                                                        <span class="btn-label btn-label-right">
                                                            <i class="fas fa-eye"></i>
                                                        </span>                    
                                                    </button> 
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonSaveSelectedWidgets"
                                        class="btn btn-success btn-md btn-on-table float-right"
                                        @click="addWidgets">
                                        {{ $t('Add') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <section class="container-fluid mt-3">
            <div class="row" id="divWidgetCatalogContainer">
                <div v-for="(winWidget, index) in windowWidgets" :key="index" 
                    class="widget-container widget-catalog" 
                    :id="'container-' + winWidget.instance_id"
                    :data-instance-id="winWidget.instance_id">
                    <div class="widget-main-container">
                        <div class="widget-header">
                            <widget-header 
                                :instance_id="winWidget.instance_id" 
                                :data="winWidget.data">
                            </widget-header>
                        </div>
                        <div class="widget-body">
                            <widget-body :element="winWidget"></widget-body>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
export default {
    props: ['pagename'],
    watch: {
        pagename: function(pagename) {
            this.pagename = pagename;
            this.processLoadQueue();
        }
    },
    data() {
        return {
            windowWidgets: [],
            windowWidgetList: [],
            widgetList: [],
            selectedPageWidgets: [],
            widget_page_options: [],
            page: {
                is_ready: false,
                has_server_error: false,
                is_post_success: false,
                is_widget_page_options_loading: false,
                is_widget_page_options_loaded: false,
                external_files: [
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                ],
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            var self = this;
            
            if (self.page.has_server_error) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_widget_page_options_loaded) {
                self.load_widget_page_options();
            } else {
                self.initializePage();
            }
        },
        initializePage: function() {
            var self = this;

            $("#widget_page").off("change").on("change", function (e) {
                self.pageChanged(this.value);
            });

            $("#searchNewWidget").off("keyup").on("keyup", function () {
                self.doSearchNewWidget(this);
            });

            var windowWidgetList = [];
            var index = 0;

            for (const [widgetname, windowWidget] of Object.entries(window.Widgets)) {
                let instance_id = WisiloHelper.generateGUID("widget");
                window.mainLayoutInstance.pageWidgets[instance_id] = [];

                let general_data = {
                    "enabled" : 1,
                    "__order" : 0,
                    "title" : windowWidget.title,
                    "widget" : widgetname,
                    "grid_size" : windowWidget.grid_size,
                    "icon" : windowWidget.icon
                };

                let child = {
                    "instance_id": instance_id,
                    "widget": windowWidget,
                    "data": {
                        "general": general_data,
                        "content": windowWidget.metadata
                    },
                    "grid_class": ""
                }

                self.windowWidgets.push(child);

                windowWidgetList[index] = [];
                windowWidgetList[index]["guid"] = instance_id;
                windowWidgetList[index]["title"] = windowWidget.title;
                windowWidgetList[index]["name"] = widgetname;
                windowWidgetList[index]["has_preview"] = windowWidget.has_preview;
                windowWidgetList[index]["data"] = JSON.stringify(child.data);
                index++;
            }

            self.windowWidgetList = windowWidgetList;
            self.widgetList = windowWidgetList;
        },
        pageChanged: function(page) {
            var self = this;

            $(".select_widget").prop("checked", false);
            
            if ("__global" == page) {
                self.widgetList = self.windowWidgetList;
            } else {
                self.load_selectedPageWidgets(page);
            }
        },
        load_selectedPageWidgets: function(page) {
            var self = this;

            if ((undefined === page) || ('' == page) || (null === page)) {
                return;
            }
			
			axios.get(WisiloHelper.getAPIURL("__layout/get_widgets/" + page))
                .then(({ data }) => {
                    self.selectedPageWidgets = data.page_widgets;
                    self.renderPageWidgets();
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                });
        },
        renderPageWidgets: function() {
            var self = this;
            var selectedPageWidgetList = [];
            var index = 0;

            self.selectedPageWidgets.forEach(page_widget => {
                let widgetname = page_widget["widget"];

                if (null !== window.Widgets[widgetname]) {
                    let instance_id = WisiloHelper.generateGUID("widget");

                    let data = {
                        "general": {
                            "enabled" : page_widget.enabled,
                            "__order" : page_widget.__order,
                            "title" : page_widget.title,
                            "widget" : widgetname,
                            "grid_size" : page_widget.grid_size,
                            "icon" : page_widget.icon
                        },
                        "content": JSON.parse(page_widget.meta_data_json),
                        "data_source": JSON.parse(page_widget.data_source_json)
                    };
                    
                    selectedPageWidgetList[index] = [];
                    selectedPageWidgetList[index]["guid"] = instance_id;
                    selectedPageWidgetList[index]["title"] = page_widget.title;
                    selectedPageWidgetList[index]["name"] = widgetname;
                    selectedPageWidgetList[index]["has_preview"] = window.Widgets[widgetname].has_preview;
                    selectedPageWidgetList[index]["data"] = JSON.stringify(data);
                    index++;
                }
            });

            self.widgetList = selectedPageWidgetList;
        },
        load_widget_page_options: function() {
            var self = this;
            if (self.page.is_widget_page_options_loading) {
                return;
            }

            self.page.is_widget_page_options_loading = true;
			
			axios.get(WisiloHelper.getAPIURL("__layout/get_filter_options/" + self.pagename))
                .then(({ data }) => {
                    self.page.is_widget_page_options_loaded = true;
                    self.page.is_widget_page_options_loading = false;
                    self.widget_page_options = data.options['widget_page'];
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_widget_page_options_loaded = true;
                    self.page.is_widget_page_options_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        doSearchNewWidget: function(sender) {
            if (!sender) {
                return;
            }

            var searchText = sender.value;

            $("#tbodyWidgetList > tr").addClass("d-none");

            var arrTR = $("#tbodyWidgetList > tr");
            var countTR = arrTR.length;
            var searchedText = "";

            for (var i = 0; i < countTR; i++) {
                searchedText = arrTR[i].getAttribute("data-search-text");

                if (searchedText.search(new RegExp(searchText, "i")) != -1) {
                    $(arrTR[i]).removeClass("d-none");
                }
            }
        },
        select_all_widget: function(sender) {
            $("#tbodyWidgetList > tr > td > div > .select_widget").prop("checked", sender.checked);
        },
        addWidgets: function() {
            var tbodyElement = document.getElementById("tbodyWidgetList");
            var selectedCheckboxes = $(".select_widget:checked", tbodyElement);
            var selectedCount = selectedCheckboxes.length;
            var selectedWidgets = [];
            for (var i = 0; i < selectedCount; i++) {
                selectedWidgets.push(JSON.parse(selectedCheckboxes[i].getAttribute("data-widget")));
            }

            window.mainLayoutInstance.vueComponent.addNewWidgets(selectedWidgets);

            if (selectedCount > 0) {
                $("#btnSaveWidgets").removeClass("btn-default").addClass("btn-success");
            }

            $("#modalWidgetList").modal("hide");
        },
        showPreview: function(widget_name) {
            var modal = document.getElementById(widget_name + "ModalPreview");
            $(modal).modal();
        }
    },
    mounted() {
        this.$Progress.start();
        this.page.is_ready = false;
        WisiloHelper.loadExternalFiles(this.page.external_files);
    }
}
</script>
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
                                <div class="col-12 mt-3 mb-3">
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
                                            <tr v-for="(widget, index) in widgets" :key="index"
                                                :data-search-text="widget.title + widget.name">
                                                <td>
                                                    <div class="icheck-primary m-0">
                                                        <input type="checkbox"
                                                            :id="'select_widget-' + widget.name"
                                                            class="select_widget"
                                                            :data-widget="widget.name">
                                                        <label :for="'select_widget-' + widget.name"></label>
                                                    </div>
                                                </td>
                                                <td v-html="widget.title + ' (' + widget.name + ')'"></td>
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
    </div>
</template>

<script>
export default {
    props: ['pagename'],
    watch: {
        pagename: function(pagename) {
            this.pagename = pagename;
            this.layoutForm.pagename = pagename;
            this.initializePage();
        }
    },
    data() {
        return {
            widgets : window.Widgets,
            layoutForm: new Form({
                'pagename': '',
                'layoutdata': [],
            }),
            page: {
                is_ready: false,
                is_post_success: false,
                external_files: [
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                ],
                editor: null
            }
        };
    },
    methods: {
        initializePage: function() {
            var self = this;
            $( "#ulPageWidgets" ).sortable();

            $("#searchNewWidget").off("keyup").on("keyup", function () {
                self.doSearchNewWidget(this);
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
            var selectedWidgets = "";
            for (var i = 0; i < selectedCount; i++) {
                if ("" != selectedWidgets) {
                    selectedWidgets += ",";
                }

                selectedWidgets += selectedCheckboxes[i].getAttribute("data-widget");
            }

            window.mainLayoutInstance.vueComponent.addNewWidgets(selectedWidgets);

            $("#modalWidgetList").modal("hide");
        },
    },
    mounted() {
        this.$Progress.start();
        this.page.is_ready = false;
        AdminLTEHelper.loadExternalFiles(this.page.external_files);
    }
}
</script>
<template>
    <div>
        <div class="modal fade" id="modal-Elements" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form id="formConfiguration" @submit.prevent="submitForm" @keydown="layoutForm.onKeydown($event)">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Widgets') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding-top:0px;">
                            <div class="row mt-3">
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <button type="button"
                                        class="btn btn-primary btn-md btn-on-card float-right"
                                        @click="showAddWidgetDialog">
                                        <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('Add Widget(s)') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3 mb-3">
                                    <div class="input-group input-group-md">
                                        <input type="search"
                                            id="searchWidget" name="searchWidget"
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
                                    <ul id="ulPageWidgets" class="ulList list-group">
                                        <li v-for="(page_widget, index) in page_widgets" :key="index" 
                                            class="list-group-item li-widget-item" 
                                            :id="'li-' + page_widget.instance_id"
                                            :data-enabled="page_widget.db_data.enabled"
                                            :data-search-text="page_widget.db_data.title + page_widget.db_data.widget">
                                            <div style="overflow=auto;">
                                                <div class="editor-title-container">
                                                    <p class="title" :id="'liTitle-' + page_widget.instance_id" v-html="page_widget.db_data.title"></p>
                                                    <p class="type" v-html="page_widget.db_data.widget"></p>
                                                </div>
                                                <div class="btn-group item-btn-group editor-button-container float-right">
                                                    <button type="button"
                                                        class="btn-icon btn-icon-primary btn-toggle-widget"
                                                        @click="toggleWidget(page_widget.instance_id)"
                                                        style="margin-bottom:0;">
                                                        <span class="btn-label btn-label-right">
                                                            <i class="fas fa-eye"></i>
                                                        </span>                    
                                                    </button>
                                                    <button type="button"
                                                        class="btn-icon btn-icon-primary btnEdit btn-edit-widget"
                                                        @click="editWidget(page_widget.instance_id)"
                                                        style="margin-bottom:0;">
                                                        <span class="btn-label btn-label-right">
                                                            <i class="fas fa-cog"></i>
                                                        </span>                    
                                                    </button>
                                                    <button type="button"
                                                        class="btn-icon btn-icon-primary btnRemove btn-remove-widget"
                                                        @click="removeWidget(page_widget.instance_id)"
                                                        style="margin-bottom:0;">
                                                        <span class="btn-label btn-label-right">
                                                            <i class="fas fa-times"></i>
                                                        </span>                    
                                                    </button>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button :disabled="layoutForm.busy"
                                        type="submit"
                                        class="btn btn-success btn-md btn-on-table float-right">
                                        {{ $t('Save Layout') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>                                    
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

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
    props: ['pagename', 'page_widgets'],
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
                is_page_widgets_loading: false,
                is_page_widgets_loaded: false,
                is_post_success: false,
                external_files: [
                    ("/js/adminlte/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/adminlte/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/adminlte/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/adminlte/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/adminlte/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    /* ("/js/adminlte/widget_editor.js") */
                ],
                editor: null
            }
        };
    },
    methods: {
        initializePage: function() {
            var self = this;
            $( "#ulPageWidgets" ).sortable();

            $("#searchWidget").off("keyup").on("keyup", function () {
                self.doSearchWidget(this);
            });

            $("#searchNewWidget").off("keyup").on("keyup", function () {
                self.doSearchNewWidget(this);
            });
        },
        doSearchWidget: function(sender) {
            if (!sender) {
                return;
            }

            var searchText = sender.value;

            $("#ulPageWidgets > li").css("display", "none");

            var arrLI = $("#ulPageWidgets > li");
            var countLI = arrLI.length;
            var searchedText = "";

            for (var i = 0; i < countLI; i++) {
                searchedText = arrLI[i].getAttribute("data-search-text");

                if (searchedText.search(new RegExp(searchText, "i")) != -1) {
                    arrLI[i].style.display = "block";
                }
            }
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
        toggleWidget: function (instance_id) {
            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            var enabled = widgets_form_data[instance_id]["general_data"]["enabled"];

            if (0 == enabled) {
                enabled = 1;
            } else {
                enabled = 0;
            }

            document.getElementById("li-" + instance_id).setAttribute("data-enabled", enabled);

            widgets_form_data[instance_id]["general_data"]["enabled"] = enabled;
            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);
        },
        removeWidget: function (instance_id) {
            document.getElementById("li-" + instance_id).remove();

            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            delete widgets_form_data[instance_id];
            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);
        },
        editWidget: function (instance_id) {
            this.$root.$emit("fill-widget-form-values", instance_id);

            var modal = document.getElementById(instance_id + "ModalSettings");
            $(modal).modal();
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.layoutForm.layoutdata = self.getLayoutData();
            
            self.layoutForm.post(AdminLTEHelper.getAPIURL("__layout/post_layout"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.is_post_success = true;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.is_post_success = false;
                }).finally(function() {
                    if (self.page.is_post_success) {
                        Vue.swal.fire({
                            position: 'top-end',
                            title: self.$t("Your changes have been saved!"),
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            onClose: () => {
                                /* console.log("posted") */
                                window.location.reload()
                            }
                        });
                    }
                }
            );
        },
        getLayoutData: function() {
            var widgets = [];
            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            var sortedIds = $("#ulPageWidgets").sortable("toArray");
            sortedIds.forEach(item => {
                let instance_id = item.replace("li-", "");
                widgets.push(widgets_form_data[instance_id]);
            });

            return widgets;
        },
        showAddWidgetDialog: function() {
            $("#tbodyWidgetList > tr > td > div > .select_widget").prop("checked", false);
            $("#modalWidgetList").modal();
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

            document.getElementById("new_widgets").value = selectedWidgets;
            $('#new_widgets').trigger('click'); 
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
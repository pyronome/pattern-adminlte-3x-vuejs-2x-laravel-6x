<template>
    <div id="__ds__order_dialog" class="modal level2 fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Order Fields') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-lg-12">
                            <label for="__ds__order-select" class="detail-label">{{ $t('Fields') }}</label>
                            <select2-element class="select2-element"
                                id="__ds__order-select"
                                name="__ds__order-select"
                                :options="order_fields_options">
                            </select2-element>
                        </div>
                    </div>
                </div>
                <div class="modalfooter justify-content-between">
                    <div class="row">
                        <div class="col-md-12">
                            <button
                                type="button"
                                id="__ds__orders__buttonSave"
                                @click="doSaveOrderFields"
                                data-instance-id=""
                                class="btn btn-success btn-md btn-on-table float-right">
                                {{ $t('Save') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script id="__ds_order__rowtemplate" type="text/html">
            <td class="">
                <span id="__guid__-label-__type__">__label__</span>
                <button type="button" title="Remove Order" class="btn-icon btn-icon-danger float-right btn-remove-order">
                    <span class="btn-label btn-label-right"><i class="fas fa-times"></i></span>
                </button>
            </td>
        </script>
    </div>
</template>

<script>
export default {
    data() {
        return {
            order_fields_options: []
        }
    },
    
    methods: {
        refreshOrderFieldsOptions: function() {
            this.order_fields_options = window.__ds__order_dialog.order_fields;
            return;
        },
        insertOrderFieldOptions: function(guid, label) {
            var optionASC = {
                "id": guid + "-asc",
                "text": label + " ASC"
            };

            window.__ds__order_dialog.order_fields.push(optionASC);

            var optionDESC = {
                "id": guid + "-desc",
                "text": label + " DESC"
            };

            window.__ds__order_dialog.order_fields.push(optionDESC);

            this.order_fields_options = window.__ds__order_dialog.order_fields;
        },
        updateOrderFieldOptions: function(guid, label) {
            this.order_fields_options = [];

            var order_fields = [];
            var option = {};

            for(var index = 0; index < window.__ds__order_dialog.order_fields.length; index++) {
                let data = window.__ds__order_dialog.order_fields[index];
                if (guid == data.id.replace("-asc", "")) {
                    option = {
                        "id": data.id,
                        "text": label + " ASC"
                    };

                    order_fields.push(option);

                    if (document.getElementById(guid + "-label-asc")) {
                        document.getElementById(guid + "-label-asc").innerHTML = label + " <b>ASC</b>";
                    }
                } else if (guid == data.id.replace("-desc", "")) {
                    option = {
                        "id": data.id,
                        "text": label + " ASC"
                    };

                    order_fields.push(option);
                    
                    if (document.getElementById(guid + "-label-desc")) {
                        document.getElementById(guid + "-label-desc").innerHTML = label + " <b>DESC</b>";
                    }
                } else {
                    option = {
                        "id": data.id,
                        "text": data.text
                    };

                    order_fields.push(option);
                }
            }

            window.__ds__order_dialog.order_fields = order_fields;
            this.order_fields_options = order_fields;
        },
        removeOrderFieldOptions: function(guid) {
            this.order_fields_options = [];

            var order_fields = [];
            var option = {};

            for(var index = 0; index < window.__ds__order_dialog.order_fields.length; index++) {
                let data = window.__ds__order_dialog.order_fields[index];
                if (guid == data.id.replace("-asc", "")) {
                    if (document.getElementById(guid + "-label-asc")) {
                        document.getElementById(guid + "-label-asc").parentNode.parentNode.remove();
                    }
                } else if (guid == data.id.replace("-desc", "")) {
                    if (document.getElementById(guid + "-label-desc")) {
                        document.getElementById(guid + "-label-desc").parentNode.parentNode.remove();
                    }
                } else {
                    option = {
                        "id": data.id,
                        "text": data.text
                    };

                    order_fields.push(option);
                }
            }

            window.__ds__order_dialog.order_fields = order_fields;
            this.order_fields_options = order_fields;
        },
        doSaveOrderFields: function() {
            var self = this;
            var instance_id = document.getElementById("__ds__orders__buttonSave").getAttribute("data-instance-id");

            var selectElement = document.getElementById("__ds__order-select");
            var selectedOrderField = selectElement.value;
            
            if ("" == selectedOrderField) {
                $("#__ds__order_dialog").modal("hide");
                return;
            }

            var ordersContainer = document.getElementById(instance_id + "__fo__selectedorders");

            let data = selectedOrderField.split("-");
            let guid = data[0];
            let type = data[1];

            if (document.getElementById(guid + "__selected_fo__-" + type)) {
                $("#__ds__order_dialog").modal("hide");
                return;
            }

            var selectedOption = selectElement.options[selectElement.selectedIndex];
            let label = selectedOption.innerHTML.replace(" ASC", "").replace(" DESC", "");
            let typeText = " <b>" + (("asc" == type) ? "ASC" : "DESC") + "</b>";

            let trHTML = document.getElementById("__ds_order__rowtemplate").innerHTML
                .replace(/__guid__/g, guid)
                .replace(/__type__/g, type)
                .replace(/__label__/g, (label +" "+ typeText));

            let tr = document.createElement("tr");
            tr.innerHTML = trHTML;
            tr.id = (guid + "__selected_fo__-" + type);
            tr.className = "span_selected_order_fields";
            tr.setAttribute("data-guid", guid);
            tr.setAttribute("data-type", type);
            tr.setAttribute("data-label", label);

            ordersContainer.appendChild(tr);

            $(".btn-remove-order", ordersContainer).off("click").on("click", function () {
                self.doRemoveOrder(this);
            });

            $(ordersContainer).sortable();
            $(ordersContainer).disableSelection();

            $("#__ds__order_dialog").modal("hide");
        },
        setOrderFields: function(instance_id, order_fields) {
            var self = this;
            var ordersContainer = document.getElementById(instance_id + "__fo__selectedorders");
            ordersContainer.innerHTML = "";
            var trTemplateHTML = document.getElementById("__ds_order__rowtemplate").innerHTML;

            var orderFieldsHTML = "";
            for (let index = 0; index < order_fields.length; index++) {
                let order_data = order_fields[index];

                let field_guid = order_data.field_guid;
                let type = order_data.type;
                let label = order_data.label;

                let checkboxId = field_guid + "-__fo__" + type;
                if (document.getElementById(checkboxId)) {
                    document.getElementById(checkboxId).checked = true;
                }

                let typeText = " <b>" + (("asc" == type) ? "ASC" : "DESC") + "</b>";

                let trHTML = trTemplateHTML
                    .replace(/__guid__/g, field_guid)
                    .replace(/__type__/g, type)
                    .replace(/__label__/g, (label +" "+ typeText));

                let tr = document.createElement("tr");
                tr.innerHTML = trHTML;
                tr.id = (field_guid + "__selected_fo__-" + type);
                tr.className = "span_selected_order_fields";
                tr.setAttribute("data-guid", field_guid);
                tr.setAttribute("data-type", type);
                tr.setAttribute("data-label", label);

                ordersContainer.appendChild(tr);
            }

            $(".btn-remove-order", ordersContainer).off("click").on("click", function () {
                self.doRemoveOrder(this);
            });

            $(ordersContainer).sortable();
            $(ordersContainer).disableSelection();
        },
        doRemoveOrder: function(sender) {
            sender.parentNode.parentNode.remove();
        },
        collectOrderFieldsData: function(instance_id) {
            var arrTR = $("tr", document.getElementById(instance_id + "__fo__selectedorders"));
            var orders_data = [];

            for (let index = 0; index < arrTR.length; index++) {
                let elTR = arrTR[index];

                let order_data = {
                    "field_guid" : elTR.getAttribute("data-guid"),
                    "type" : elTR.getAttribute("data-type"),
                    "label" : elTR.getAttribute("data-label")
                }

                orders_data.push(order_data);
            }

            return orders_data;
        }
    },
    mounted() {
        window.__ds__order_dialog = this;
        window.__ds__order_dialog.list = [];
        window.__ds__order_dialog.order_fields = [];
    }
}
</script>
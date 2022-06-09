<template>
    <div>
        <div class="modal level4 fade" id="divInsertCustomVariableToTarget" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form>
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Insert Custom Variable') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <select2-element class="select2-element"
                                        id="__iv__custom_variable"
                                        name="__iv__custom_variable"
                                        :options="custom_variable_options">
                                    </select2-element>
                                    <!--select id="__iv__custom_variable"  class="form-control">
                                        <option value="0">{{ $t('Please select') }}</option>
                                        <option value="__addnew">{{ $t('Add New') }}</option>
                                        <option v-for="(customvariable, index) in custom_variable_options" 
                                            :key="index" 
                                            :value="customvariable.id" 
                                            :selected="(customvariable.id == selected_customvariable)">
                                            {{customvariable.text}}
                                        </option>
                                    </select-->
                                </div>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between">
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonInsertCustomVariableToTarget"
                                        data-target=""
                                        class="btn btn-success btn-md btn-on-table float-right"
                                        @click="insertCustomVariableToTarget">
                                        {{ $t('Insert') }}
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
    data() {
        return {
            is_custom_variable_options_loaded: false,
            custom_variable_options: [],
        };
    },
    methods: {
        load_custom_variable_options: function(selected_customvariable) {
            $("__iv__custom_variable").off().select2('destroy');
            document.getElementById("__iv__custom_variable").setAttribute("initial-value", selected_customvariable)

            var custom_variables = window.__custom_variables.list
            var options = [];
            var option = {
                "id" : "",
                "text": "Please select"
            }
            options.push(option);

            option = {
                "id" : "__addnew",
                "text": "Add New"
            }
            options.push(option);

            for (let index = 0; index < custom_variables.length; index++) {
                const element = custom_variables[index];
                option = {
                    "id" : "CustomVariables/" + element.name,
                    "text": element.title
                }

                options.push(option);
            }

            this.custom_variable_options = options;

            this.is_custom_variable_options_loaded = true;
            
            $("#__iv__custom_variable").off("change").on("change", function (e) {
                if ("__addnew" == this.value) {
                    window.__custom_variables.addNewCustomVariable();
                }
            });

            return;
        },
        insertAtCursor: function(inputField, variableText) {
            //IE support
            if (document.selection) {
                inputField.focus();
                sel = document.selection.createRange();
                sel.text = variableText;
            }
            //MOZILLA and others
            else if (inputField.selectionStart || inputField.selectionStart == '0') {
                var startPos = inputField.selectionStart;
                var endPos = inputField.selectionEnd;
                inputField.value = inputField.value.substring(0, startPos)
                    + variableText
                    + inputField.value.substring(endPos, inputField.value.length);
            } else {
                inputField.value += variableText;
            }
        },
        insertCustomVariableToTarget: function () {
            var self = this;
            var data = self.getCustomVariable();

            if ("{{__addnew}}" == data.variable) {
                return;
            }

            if (data.has_error) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: data.msg,
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    onClose: () => {}
                });

                return;
            }
            
            var targetId = document.getElementById("buttonInsertCustomVariableToTarget").getAttribute("data-target");
            if (document.getElementById(targetId)) {
                var target = document.getElementById(targetId);

                if ($(target).hasClass("vue-editor")) {
                    $(target).summernote('editor.insertText', data.variable);
                } else {
                    self.insertAtCursor(target, data.variable);
                }
            }

            $("#divInsertCustomVariableToTarget").modal("hide");
        },
        getCustomVariable: function () {
            var result = {
                "has_error": false,
                "msg": "",
                "variable": ""
            };

            var customVariable = $("#__iv__custom_variable").val();

            if (("" == customVariable) || (null === customVariable)) {
                result.has_error = true;
                result.msg = this.$t("Please select a custom variable.");
                return result;
            }

            result.variable = "{{" + customVariable + "}}";

            return result;
        },
        showDialog: function(target) {
            document.getElementById("buttonInsertCustomVariableToTarget").setAttribute("data-target", target);

            $("#divInsertCustomVariableToTarget").off("shown.bs.modal").on("shown.bs.modal", function (e) { 
                $(document).off("focusin.modal"); 
            });

            $("#divInsertCustomVariableToTarget").modal();
        }
    },
    mounted() {
        window.__insert_custom_variable_dialog = this;
    }
}
</script>
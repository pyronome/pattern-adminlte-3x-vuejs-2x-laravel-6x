<template>
    <div class="modal fade" :id="instance_id + 'ModalSettings'" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{ $t('Widget') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-5 col-sm-3">
                            <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                <a class="nav-link active"
                                    :id="instance_id + 'general-tab'" 
                                    data-toggle="pill" 
                                    :href="'#' + instance_id + 'general'"
                                    role="tab" 
                                    :aria-controls="instance_id + 'general'"
                                    aria-selected="true">
                                    General
                                </a>
                                <a class="nav-link" 
                                    :id="instance_id + 'content-tab'" 
                                    data-toggle="pill" 
                                    :href="'#' + instance_id + 'content'"
                                    role="tab" 
                                    :aria-controls="instance_id + 'content'"
                                    aria-selected="false">
                                    Content
                                </a>
                            </div>
                        </div>
                        <div class="col-7 col-sm-9">
                            <div class="tab-content" id="vert-tabs-tabContent">
                                <div class="tab-pane text-left fade show active" :id="instance_id + 'general'" role="tabpanel" :aria-labelledby="instance_id + 'general-tab'">
                                    <div class="row">
                                        <div class="form-group col-lg-12">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    :id="instance_id + 'enabled'"
                                                    class="">
                                                <label :for="instance_id + 'enabled'" class="detail-label">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-12">
                                            <label :for="instance_id + 'title'" class="detail-label">{{ $t('Title') }}</label>
                                            <input type="text"
                                                class="form-control "
                                                :id="instance_id + 'title'">
                                        </div> 
                                    </div>
                                    <div class="row">
                                        <input type="hidden" :id="instance_id + 'size'">
                                        <div class="col-md-4">
                                            <label :for="instance_id + 'large_screen_size'">{{ $t('Large Screen Size') }}</label>
                                            <select :id="instance_id + 'large_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label :for="instance_id + 'medium_screen_size'">{{ $t('Medium Screen Size') }}</label>
                                            <select :id="instance_id + 'medium_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label :for="instance_id + 'small_screen_size'">{{ $t('Small Screen Size') }}</label>
                                            <select :id="instance_id + 'small_screen_size'" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="11">11</option>
                                                <option value="12" selected>12</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" :id="instance_id + 'content'" role="tabpanel" :aria-labelledby="instance_id + 'content-tab'">
                                    <input type="hidden" :id="instance_id + 'content_data'">
                                    <slot></slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button 
                        type="button" 
                        @click="saveWidget()"
                        class="btn btn-success btn-md btn-on-table float-right btnSaveWidget">
                        Ok
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['instance_id'],
    mounted() {
        var self = this;

        self.$root.$on("fill-widget-form-values", (instance_id) => {
            if (self.instance_id == instance_id) {
                self.setWidgetFormValues();
            }
        });
    },
    methods: {
        setWidgetFormValues: function() {
            var self = this;
            var instance_id = self.instance_id;
            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            var general_data = widgets_form_data[instance_id]["general_data"];

            document.getElementById(this.instance_id + 'enabled').checked = (1 == general_data.enabled);

            document.getElementById(instance_id + "title").value = general_data.title;

            var sizes = general_data.grid_size.split(",");
            document.getElementById(this.instance_id + 'large_screen_size').value = sizes[0];
            document.getElementById(this.instance_id + 'medium_screen_size').value = sizes[1];
            document.getElementById(this.instance_id + 'small_screen_size').value = sizes[2];
        },
        saveWidget: function() {
            var self = this;
            var instance_id = self.instance_id;

            self.$root.$emit("collect-widget-form-values", instance_id);

            var widgets_form_data = $("#widgets_form_data").data("widgets_form_data");
            
            // General
            widgets_form_data[instance_id]["general_data"]["enabled"] = document.getElementById(instance_id + 'enabled').checked ? 1 : 0;
            widgets_form_data[instance_id]["general_data"]["title"] = document.getElementById(instance_id + 'title').value;

            var large_screen_size = document.getElementById(instance_id + 'large_screen_size').value;
            var medium_screen_size = document.getElementById(instance_id + 'medium_screen_size').value;
            var small_screen_size = document.getElementById(instance_id + 'small_screen_size').value;

            widgets_form_data[instance_id]["general_data"]["grid_size"] = large_screen_size + "," + medium_screen_size + "," + small_screen_size;

            // Content
            widgets_form_data[instance_id]["content_data"] = $(document.getElementById(instance_id + "content_data")).data("content_data");

            $("#widgets_form_data").data("widgets_form_data", widgets_form_data);

            self.updateList(widgets_form_data[instance_id]["general_data"]);

            Vue.swal.fire({
                position: 'top-end',
                title: self.$t("Your changes have been saved!"),
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
                timerProgressBar: true,
                onClose: () => {
                    $(document.getElementById(instance_id + "ModalSettings")).modal("hide")
                }
            });
        },
        updateList: function(general_data) {
            var li = document.getElementById("li-" + this.instance_id);
            li.setAttribute("data-enabled", general_data["enabled"]);
            $(".editor-title-container > .title", li).html(general_data["title"]);
        }
    }
};
</script>
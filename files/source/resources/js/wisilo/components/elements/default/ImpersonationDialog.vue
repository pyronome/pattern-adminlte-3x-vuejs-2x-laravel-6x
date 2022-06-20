<template>
    <div>
        <div id="divImpersonationDialog" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $t('Impersonation') }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="ImpersonationForm"
                            class=""
                            @submit.prevent="submitImpersonationForm"
                            @keydown="ImpersonationForm.onKeydown($event)">
                            <div class="row">
                                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                                    <label for="ImpersonationForm_user_id" class="detail-label">{{  $t('User') }}  </label>
                                    <select id="ImpersonationForm_user_id">
                                        <optgroup v-for="(data,index) in impersonation_users" :key="index" :label="data.group_title">
                                            <option v-for="(child, c_index) in data.children" :key="c_index" v-html="child.email"  :value="child.id">
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modalfooter justify-content-between show_by_permission_must_update">
                        <div class="row">
                            <div class="col-md-12">
                                <button
                                    type="button"
                                    id="buttonSaveImpersonation"
                                    @click="saveImpersonationForm"
                                    class="btn btn-success btn-md btn-on-table float-right">
                                    {{ $t('Impersonate') }}
                                </button>
                                <button v-if="ImpersonationForm.user_id > 0" type="button" 
                                    @click="removeImpersonate"
                                    class="btn btn-danger btn-md btn-on-table float-right"
                                    style="margin-right:10px;">
                                    {{ $t('Switch Off') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal" style="margin-right:10px;">{{ $t('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            impersonation_users: [],
            ImpersonationForm: new Form({
                'user_id': 0,
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                has_post_error: false,
                post_message: "",
                is_impersonation_users_loading: false,
                is_impersonation_users_loaded: false,
                is_impersonation_data_loading: false,
                is_impersonation_data_loaded: false
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


            if (!self.page.is_impersonation_users_loaded && !self.page.is_impersonation_data_loaded) {
                self.$Progress.start();
            }

            
            if (!self.page.is_impersonation_users_loaded) {
                self.load_impersonation_users();
            } else if (!self.page.is_impersonation_data_loaded) {
                self.load_impersonation_data();
            } else {
                self.$nextTick(function () {
                    setTimeout(function() {
                        self.initializePage();
                    }, 500);                        
                });
                
                self.$Progress.finish();
                self.page.is_ready = true;
            }
        },
        initializePage: function () {
            var self = this;
            self.initailizeSelect2();
        },
        initailizeSelect2(){
            var self = this;

            $("#ImpersonationForm_user_id").select2();
            $("#ImpersonationForm_user_id").val(this.ImpersonationForm.user_id).trigger("change");
            /* $(document.getElementById(selectId)).select2({
                ajax: {
                    url: "ajaxfile.php",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            searchTerm: params.term // search term
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            }); */
        },
        load_impersonation_users: function () {
            var self = this;

            if (self.page.is_impersonation_users_loading) {
                return;
            }

            self.page.is_impersonation_users_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilo/get_impersonation_users"))
                .then(({ data }) => {
                    self.page.is_impersonation_users_loaded = true;
                    self.page.is_impersonation_users_loading = false;
                    self.impersonation_users = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.page.is_impersonation_users_loaded = true;
                    self.page.is_impersonation_users_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                });
        },
        load_impersonation_data: function () {
            if (this.page.is_impersonation_data_loading) {
                return;
            }

            this.page.is_impersonation_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilo/get_impersonation_data"))
                .then(({ data }) => {
                    this.page.is_impersonation_data_loaded = true;
                    this.page.is_impersonation_data_loading = false;
                    this.ImpersonationForm.user_id = data.impersonated_id;

                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_impersonation_data_loaded = true;
                    this.page.is_impersonation_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        saveImpersonationForm: function () {
            var self = this;
            self.$Progress.start();
            self.ImpersonationForm.user_id = document.getElementById("ImpersonationForm_user_id").value;
            
            self.ImpersonationForm.post(WisiloHelper.getAPIURL("wisilo/post_impersonation_data"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_message = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    Vue.swal.fire({
                        position: 'top-end',
                        title: self.$t("An error occurred while processing your request."),
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 10000,
                        timerProgressBar: true
                    });
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    window.location.reload()
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        },
        removeImpersonate: function () {
            var self = this;
            self.$Progress.start();
            self.ImpersonationForm.user_id = 0;
            
            self.ImpersonationForm.post(WisiloHelper.getAPIURL("wisilo/remove_impersonation"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_message = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    Vue.swal.fire({
                        position: 'top-end',
                        title: self.$t("An error occurred while processing your request."),
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 10000,
                        timerProgressBar: true
                    });
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    window.location.reload()
                                }
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.page.post_message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    }
                });
        }
    },
    mounted() {
        var self = this;

        var scriptTag = document.createElement("script");
        scriptTag.src = "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js";
        scriptTag.id = "select2min";
        document.getElementsByTagName('head')[0].appendChild(scriptTag);

        self.processLoadQueue()

        $('[data-toggle="tooltip"]').tooltip();
    }
}
</script>
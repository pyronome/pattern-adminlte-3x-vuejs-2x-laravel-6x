<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $t('Brand Settings') }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="home">{{ $t('Home') }}</a></li>
                            <li class="breadcrumb-item active">{{ $t('Brand Settings') }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content" v-show="page.is_ready">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <div class="card card-primary">
                            <form id="formConfiguration"
                                class=""
                                @submit.prevent="submitForm"
                                @keydown="form.onKeydown($event)">
                                <div class="card-body text-sm">
                                    <div class="row">
                                        <div class="form-group col-lg-5 col-md-5 col-xs-12 ">
                                            <label for="logo" class="detail-label">{{ $t('Logo') }}  </label>
                                            <div class="input-field">
                                                <div class="col-lg-12 col-md-12 col-xs-12 text-center mb-10">
                                                    <img class="img-circle" :src="getLogo()" alt="Brand Logo" style="max-width: 250px;">
                                                </div>
                                                <div class="col-lg-12 col-md-12 col-xs-12 text-center">
                                                    <input type="file" @change="updateLogo" id="logo" name="logo" class="form-input" style="display:none">
                                                    <input type="hidden" v-model="form.file_name">
                                                    <button type="button"
                                                        @click="browseLogo"
                                                        class="buttonBrowseFile btn btn-primary">
                                                        <i class="ion-ios-folder"></i>&nbsp;Browse...
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-lg-7 col-md-7 col-xs-12">
                                            <label for="name" class="detail-label">{{ $t('Name') }}</label>
                                            <input type="text"
                                                v-model="form.name"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.has('name') }"
                                                id="name"
                                                name="name">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer show_by_permission_must_update">
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <button :disabled="form.busy"
                                            type="submit"
                                            class="btn btn-success btn-md btn-on-table float-right">
                                            <i class="far fa-save" aria-hidden="true"></i> {{ $t('Save') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <page-variables :has_widgets="false"></page-variables>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            form: new Form({
                'debug_mode': false,
                'logo': '',
                'name': '',
                'file_name': ''
            }),
            page: {
                is_ready: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false
            }
        };
    },
    methods: {
        processLoadQueue: function () {
            if (!this.page.is_data_loaded) {
                this.loadData();
            } else {
				this.$Progress.finish();
				this.page.is_ready = true;
			}
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(AdminLTEHelper.getAPIURL("branding/get"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.fill(data);
                    this.form.file_name = "logo_not_changed";
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.processLoadQueue();
                });
        },
        browseLogo(){
            document.getElementById("logo").click();
        },
        getLogo(){
            let photo = this.form.logo;
            return photo;
        },
        updateLogo(e){
            let file = e.target.files[0];
            this.form.file_name = file.name;

            let reader = new FileReader();

            let limit = 1024 * 1024 * 2;
            if(file['size'] > limit){
                swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'You are uploading a large file',
                })
                return false;
            }

            reader.onloadend = (file) => {
                this.form.logo = reader.result;
            }

            reader.readAsDataURL(file);
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            
            self.form.post(AdminLTEHelper.getAPIURL("branding/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.is_post_success = true;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    self.page.is_post_success = false;
                }).finally(function() {
                    if (self.page.is_post_success) {
                        Vue.swal.fire({
                            toast: true,
                            position: 'top-end',
                            title: '',
                            text: 'Brand settings have been saved!',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            onClose: () => {
                                window.location.reload()
                            }
                        });
                    }
                });
        }
    },
    mounted() {
        this.main_folder = AdminLTEHelper.getMainFolder();
        this.page.is_ready = false;
        this.processLoadQueue();
    }
}
</script>
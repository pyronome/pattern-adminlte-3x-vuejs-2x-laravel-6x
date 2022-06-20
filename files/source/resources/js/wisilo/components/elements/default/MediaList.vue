<template>
    <div class="widgetcomponent">
        <div class="card collapsed-card recordlist-card">
            <div class="card-header">
                <h3 class="card-title">File List</h3>
                <button id="buttonBulkUpload"
                    @click="bulkUpload()"
                    class="btn btn-primary btn-sm  float-right text-center sbp-item"
                    menu-permission-token="wisilomedia"
                    model-permission-token="wisilomedia-create">
                    <i class="fas fa-upload"></i> <span class="hidden-xxs">{{ $t('Bulk Upload') }}</span>
                </button>
            </div>
            <div class="card-body">
                <div class="recordlist-search-container">
                    <div class="input-group input-group-sm divSearchBar float-right" style="margin-bottom:1rem;">
                        <input type="text"
                            id="searchText" name="searchText"
                            @keyup="search_list" v-model="search_text"
                            class="form-control float-right inputSearchBar"
                            v-bind:placeholder="$t('Search')">
                        <div class="input-group-append labelSearchBar">
                            <button type="button" class="btn btn-default ">
                                <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                <i class="fas fa-search text-primary"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-sm">
                        <thead>
                            <tr>
                                <th class="text-center sbp-item" model-permission-token="wisilomedia-delete">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox"
                                            @click="select_all_row($event.target)"
                                            id="select_WisiloMedia_rows"
                                            class="select_all_row"
                                            data-model="WisiloMedia">
                                        <label for="select_WisiloMedia_rows"></label>
                                    </div>
                                </th>
                                <th>
                                    <button type="button"
                                        id="'button_sort_WisiloMedia_id"
                                        class="button-table-sort"
                                        @click="sort('id')">
                                        <span>Id</span>&nbsp;
                                        <span class="sorting loading">
                                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                        </span>
                                        <span class="sorting active default text-muted">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting desc text-primary">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting asc text-primary">
                                            <i class="fa fa-caret-up"></i>
                                        </span>
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                        id="'button_sort_WisiloMedia_file_title"
                                        class="button-table-sort"
                                        @click="sort('file_title')">
                                        <span>Title</span>&nbsp;
                                        <span class="sorting loading">
                                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                        </span>
                                        <span class="sorting active default text-muted">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting desc text-primary">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting asc text-primary">
                                            <i class="fa fa-caret-up"></i>
                                        </span>
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                        id="'button_sort_WisiloMedia_file_name"
                                        class="button-table-sort"
                                        @click="sort('file_name')">
                                        <span>File</span>&nbsp;
                                        <span class="sorting loading">
                                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                        </span>
                                        <span class="sorting active default text-muted">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting desc text-primary">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting asc text-primary">
                                            <i class="fa fa-caret-up"></i>
                                        </span>
                                    </button>
                                </th>
                                <th>
                                    <button type="button"
                                        id="'button_sort_WisiloMedia_mime_type"
                                        class="button-table-sort"
                                        @click="sort('mime_type')">
                                        <span>Type</span>&nbsp;
                                        <span class="sorting loading">
                                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                        </span>
                                        <span class="sorting active default text-muted">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting desc text-primary">
                                            <i class="fa fa-caret-down"></i>
                                        </span>
                                        <span class="sorting asc text-primary">
                                            <i class="fa fa-caret-up"></i>
                                        </span>
                                    </button>
                                </th>
                                <th class="text-center th-btn-1">
                                    <button id="buttonNewWisiloMedia"
                                        @click="editMedia({})"
                                        class="btn btn-primary btn-xs btn-on-table sbp-item"
                                        menu-permission-token="wisilomedia"
                                        model-permission-token="wisilomedia-create">
                                        <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('Add') }}</span>
                                    </button>
                                    
                                    <button type="button"
                                        id="buttonDeleteWisiloMedia"
                                        @click="deleteSelectedRows()"
                                        class="btn btn-danger btn-xs btn-on-table button-model-delete sbp-item"
                                        model-permission-token="wisilomedia-delete"
                                        style="display:none;">
                                        <i class="fa fa-trash"></i> <span class="hidden-xxs">{{ $t('Delete') }}</span> <span class="selected-count"></span>
                                    </button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbodyWisiloMediaRecordList">
                            <tr v-for="row in list" :key="row.id">
                                <td class="text-center sbp-item" model-permission-token="wisilomedia-delete">
                                    <div class="icheck-primary d-inline">
                                        <input type="checkbox"
                                            @click="select_wisilomedia_row($event.target)"
                                            :id="'select_WisiloMedia_row' + row.id"
                                            class="select_wisilomedia_row"
                                            data-model="WisiloMedia"
                                            :data-row-id="row.id">
                                        <label :for="'select_WisiloMedia_row' + row.id"></label>
                                    </div>
                                </td>
                                <td v-html="row.id"></td>
                                <td v-html="row.file_title"></td>
                                <td>
                                    <button type="button" class="text-btn p-0 file_download" :data-file-id="row.id">
                                        <span v-html="row.file_name"></span>
                                    </button>
                                </td>
                                <td v-html="row.mime_type"></td>
                                <td class="text-center">
                                    <button class="btn btn-outline-primary btn-xs btn-on-table sbp-item btnEditMedia"
                                        @click="editMedia(row)"
                                        menu-permission-token="wisilomedia"
                                        model-permission-token="wisilomedia-read">
                                        <i class="fas fa-pen"></i> <span class="hidden-xxs">{{ $t('Edit') }}</span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="min-height:60px;">
                    <pagination v-if="show_pagination" :data="data" :limit="1" align="right" :show-disabled="false" @pagination-change-page="paginate">
                        <span slot="prev-nav">&lt;</span>
                        <span slot="next-nav">&gt;</span>
                    </pagination>
                </div>
            </div>            
        </div>
        
        <div class="modal fade" id="modalBulkUpload" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="model-log-title">{{ $t('Bulk Upload') }}</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <label for="__media__files" class="detail-label">
                                    <span class="field-label">Files</span>
                                </label>
                                <div class="input-field">
                                    <input type="file" multiple 
                                        id="__media__files" name="__media__files" 
                                        class="form-input d-none">
                                    <button type="button" id="btnFilesTrigger" class="btn btn-primary">
                                        Browse...
                                    </button>
                                </div>
                                

                            </div>
                        </div>
                        <div class="row" id="__media__file_list">
                        </div>
                        <script type="text/html" id="btnRemoveFileTemplate">
                            <div class="col-lg-12">
                                <span>__file_name__</span>
                                <button type="button" 
                                    class="text-btn text-danger btn_file_remove" 
                                    data-guid="__guid__" 
                                    title="Remove file">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </script>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('Cancel') }}</button>
                        <button 
                            type="button" 
                            @click="submitBulkUpload()"
                            class="btn btn-success btn-md btn-on-table float-right ">
                            {{ $t('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalEditMedia" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="model-log-title">{{ $t('Media') }}</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" id="__media__id">
                            <input type="hidden" id="__media__mime_type">
                            <input type="hidden" id="__media__file_size">
                            <input type="hidden" id="__media__file_type">
                        
                            <div class="form-group col-lg-12 d-none">
                                <label for="__media__group" class="detail-label">
                                    <span>{{ $t('Group') }}</span>
                                </label>
                                <input type="text" class="form-control " id="__media__group">
                            </div>
                            <div class="form-group col-lg-12 d-none">
                                <label for="__media__guid" class="detail-label">
                                    <span>{{ $t('GUID') }}</span>
                                </label>
                                <input type="text" class="form-control " id="__media__guid">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="__media__file_title" class="detail-label">
                                    <span>{{ $t('Title') }}</span>
                                </label>
                                <input type="text" class="form-control " id="__media__file_title">
                            </div>
                            <div class="form-group col-lg-12 d-none">
                                <label for="__media__file_name" class="detail-label">
                                    <span>{{ $t('Name') }}</span>
                                </label>
                                <input type="text" class="form-control " id="__media__file_name">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="__media__description" class="detail-label">
                                    <span>{{ $t('Description') }}</span>
                                </label>
                                <textarea rows="5"
                                    id="__media__description"
                                    name="__media__description"
                                    class="form-control"></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="__media__file" class="detail-label">
                                    <span class="field-label">File</span>
                                </label>
                                <div class="input-field">
                                    <input type="file" id="__media__file" name="__media__file" data-type="file" class="form-input d-none">
                                    <button type="button" id="btnFileTrigger" class="btn btn-primary">
                                        Browse...
                                    </button>
                                    <span id="__media__spanFileName"></span>
                                    <input type="hidden" id="__media__file-file_value" value="">
                                    <button type="button" 
                                        id="__media__file-download_btn" 
                                        class="text-btn file_download" 
                                        data-file-id="" 
                                        style="display: none;">
                                        <span></span>
                                    </button>
                                    <button type="button" 
                                        id="__media__file-file_remove_btn" 
                                        class="text-btn text-danger file_remove" 
                                        data-key="__media__file" 
                                        title="Remove file"
                                        style="display: none;">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="config-parameter-error" id="__media__file-error">
                                    <span class="error invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ $t('Cancel') }}</button>
                        <button 
                            type="button" 
                            @click="submitMediaForm()"
                            class="btn btn-success btn-md btn-on-table float-right ">
                            {{ $t('Save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalDeleteMedia" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-danger">
                        <div class="model-log-title">{{ $t('Delete Confirmation') }}</div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <input type="hidden" id="selected_ids" v-model="formDelete.selected_ids">
                                <div class="icheck-primary d-inline">
                                    <input type="checkbox"
                                        id="hard_delete"
                                        v-model="formDelete.hard_delete"
                                        class="">
                                    <label for="hard_delete" class="detail-label">
                                        {{ $t('Hard Delete') }}  
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <p>{{ $t('Selected records will be deleted.') }}</p>
                                <p>{{ $t('Do you confirm?') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button 
                            type="button" 
                            @click="submitDeleteForm()"
                            class="btn btn-danger btn-md btn-on-table float-right ">
                            Continue
                        </button>
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
                main_folder: '',
                model: "WisiloMedia",
                lowercase_model: "wisilomedia",
                data: {},
                list: [],
                search_text: '',
                current_page: 1,
                show_pagination: false,
                sort_variable: 'id',
                sort_direction: 'desc',            
                formDelete: new Form({
                    'hard_delete': false,
                    'selected_ids': []
                }),
                delete_form: {
                    has_error: false,
                    error_msg: ''
                },
                uploadedFile: null,
                uploadedFiles: {},
                page: {
                    is_ready: false,
                    has_server_error: false,
                    has_post_error: false,
                    post_error_msg: "",
                },
            };
        },
        methods: {
            initializeComponentEvents: function() {
                var self = this;

                self.cleanCheckedBoxes();

                $("#btnFilesTrigger").on('click', function(e){
                    document.getElementById("__media__files").click();
                }); 

                $("#__media__files").on('change', function(e){
                    self.updateFiles(this); 
                });     

                $("#btnFileTrigger").on('click', function(e){
                    document.getElementById("__media__file").click();
                });

                $("#__media__file").on('change', function(e){
                    self.updateFile(this); 
                });

                $(".file_download").on('click', function(e){
                    self.downloadFile(this.getAttribute("data-file-id"));
                });

                /* $(".file_remove").on('click', function(e){
                    self.removeFile(this.getAttribute("data-key"));
                }); */
            },
            bulkUpload: function() {
                document.getElementById("__media__file_list").innerHTML = "";
                $("#modalBulkUpload").modal();
            },
            updateFiles(fileInput) {
                var self = this;
                var btnRemoveFileTemplate = document.getElementById("btnRemoveFileTemplate").innerHTML;
                var fileListHTML = "";
                let files = fileInput.files;

                for (const key in files) {
                    if (Object.hasOwnProperty.call(files, key)) {
                        const file = files[key];
                        let guid = WisiloHelper.generateGUID("media");
                        self.uploadedFiles[guid] = file;
                        
                        fileListHTML = fileListHTML + btnRemoveFileTemplate.replace(/__file_name__/g, file.name).replace(/__guid__/g, guid);
                    }
                }

                document.getElementById("__media__file_list").innerHTML = fileListHTML;

                $(".btn_file_remove").off("click").on("click", function(e){
                    self.removeFile(this);
                });

                /* let limit = 1024 * 1024 * 2;
                if(file['size'] > limit){
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    })
                    return false;
                } */
            },
            removeFile: function(btn) {
                let guid = btn.getAttribute("data-guid");
                this.uploadedFiles[guid] = null;
                btn.parentNode.remove();
            },
            submitBulkUpload: function() {
                var self = this;
                let formData = new FormData();

                var uploadedFiles = this.uploadedFiles;
                var guid_csv = "";

                for (const guid in uploadedFiles) {
                    if (Object.hasOwnProperty.call(uploadedFiles, guid)) {
                        if (null !== uploadedFiles[guid]) {
                            formData.append(guid, uploadedFiles[guid]);

                            if ("" != guid_csv) {
                                guid_csv += ",";
                            }

                            guid_csv += guid;
                        }
                    }
                }

                formData.append('guid_csv', guid_csv);

                if ("" == guid_csv) {
                    $("#modalBulkUpload").modal("hide")
                    return;
                }

                self.$Progress.start();

                axios.post( 
                        WisiloHelper.getAPIURL("wisilomedia/post_bulkupload"),
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                    .then(({ data }) => {
                        self.$Progress.finish();
                        self.id = data.id;
                        self.page.has_post_error = data.has_error;
                        self.page.post_error_msg = data.error_msg;
                        self.page.has_server_error = false;
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                        self.page.has_server_error = true;
                    }).finally(function() {
                        if (!self.page.has_server_error) {
                            if (!self.page.has_post_error) {
                                self.loadData(function(){
                                    Vue.swal.fire({
                                        position: 'top-end',
                                        title: self.$t("Your changes have been saved!"),
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        onClose: () => {
                                            $("#modalBulkUpload").modal("hide")
                                        }
                                    });
                                });
                            } else {
                                Vue.swal.fire({
                                    position: 'top-end',
                                    title: self.page.post_error_msg,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 10000,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });                    
            },
            updateFile(fileInput) {
                var self = this;
                let file = fileInput.files[0];

                self.uploadedFile = file;

                document.getElementById("__media__file_name").value = file.name;
                document.getElementById("__media__spanFileName").innerHTML = file.name;
                $("#__media__spanFileName").show();

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
                    document.getElementById("__media__file-download_btn").style.display = "none";
                    document.getElementById("__media__file-file_remove_btn").style.display = "none";
                }

                reader.readAsDataURL(file);
            },
            editMedia: function(object) {
                document.getElementById("__media__id").value = 0;
                document.getElementById("__media__group").value = "";
                document.getElementById("__media__guid").value = WisiloHelper.generateGUID("media");
                document.getElementById("__media__file_title").value = "";
                document.getElementById("__media__file_name").value = "";
                document.getElementById("__media__description").value = "";
                document.getElementById("__media__mime_type").value = "";
                document.getElementById("__media__file_size").value = "";
                document.getElementById("__media__file_type").value = "";
                
                $("#__media__spanFileName").hide();
                let btnDownload = document.getElementById("__media__file-download_btn");
                $(btnDownload).hide();

                if (undefined !== object.id) {
                    document.getElementById("__media__id").value = object.id;
                    document.getElementById("__media__group").value = object.group;
                    document.getElementById("__media__guid").value = object.guid;
                    document.getElementById("__media__file_title").value = object.file_title;
                    document.getElementById("__media__file_name").value = object.file_name;
                    document.getElementById("__media__description").value = object.description;
                    document.getElementById("__media__mime_type").value = object.mime_type;
                    document.getElementById("__media__file_size").value = object.file_size;
                    document.getElementById("__media__file_type").value = object.file_type;
                    
                    btnDownload.setAttribute("data-file-id", object.id);
                    $("span", btnDownload).html(object.file_name);
                    $(btnDownload).show();
                }

                $("#modalEditMedia").modal();
            },
            submitMediaForm: function() {
                var self = this;
                let formData = new FormData();
                formData.append('id', document.getElementById("__media__id").value);
                formData.append('group', document.getElementById("__media__group").value);
                formData.append('guid', document.getElementById("__media__guid").value);
                formData.append('file_title', document.getElementById("__media__file_title").value);
                formData.append('file_name', document.getElementById("__media__file_name").value);
                formData.append('description', document.getElementById("__media__description").value);
                formData.append('mime_type', document.getElementById("__media__mime_type").value);
                formData.append('file_size', document.getElementById("__media__file_size").value);
                formData.append('file_type', document.getElementById("__media__file_type").value);

                formData.append("file", self.uploadedFile);

                self.$Progress.start();

                axios.post( 
                        WisiloHelper.getAPIURL("wisilomedia/post_media"),
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    )
                    .then(({ data }) => {
                        self.$Progress.finish();
                        self.id = data.id;
                        self.page.has_post_error = data.has_error;
                        self.page.post_error_msg = data.error_msg;
                        self.page.has_server_error = false;
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                        self.page.has_server_error = true;
                    }).finally(function() {
                        if (!self.page.has_server_error) {
                            if (!self.page.has_post_error) {
                                self.loadData(function(){
                                    Vue.swal.fire({
                                        position: 'top-end',
                                        title: self.$t("Your changes have been saved!"),
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        timerProgressBar: true,
                                        onClose: () => {
                                            $("#modalEditMedia").modal("hide")
                                        }
                                    });
                                });
                            } else {
                                Vue.swal.fire({
                                    position: 'top-end',
                                    title: self.page.post_error_msg,
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 10000,
                                    timerProgressBar: true
                                });
                            }
                        }
                    });                    
            },
            downloadFile: function (file_id) {
                axios.get(WisiloHelper.getAPIURL("wisilomedia/download_file/" + file_id))
                    .then(({ data }) => {
                        var a = document.createElement("a"); //Create <a>
                        a.href = data.url; //Image Base64 Goes here
                        a.download = data.filename; //File name Here
                        a.click(); //Downloaded file
                        URL.revokeObjectURL(a.href)
                    }).catch(({ data }) => {
                        console.log("error!")
                    });
            },
            loadData: function (callback) {
                var self = this;
                var query = WisiloHelper.getURLQuery(self);
                axios.get(WisiloHelper.getAPIURL("wisilomedia/get_recordlist/" + query))
                    .then(({ data }) => {
                        self.data = data;
                        self.list = data.data.list;
                        self.show_pagination = data.show_pagination;
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                    }).finally(function() {
                        callback();
                        self.initializeComponentEvents();
                    });
            },
            search_list: _.debounce(function (e) {
                var search_input = e.target;
                
                WisiloHelper.activateSearchLoader(search_input);

                this.search_text = search_input.value;
                this.current_page = 1;

                this.loadData(function(){
                    WisiloHelper.deactivateSearchLoader(search_input)
                });
            }, 1000),
            paginate: function (page = 1) {
                this.current_page = page;
                this.loadData(function(){});
            },
            sort: function (variable) {
                WisiloHelper.activateSortLoader(`button_sort_WisiloMedia_${variable}`);

                this.sort_variable = variable;
                this.sort_direction = ('asc' == this.sort_direction) ? 'desc' : 'asc';
                this.current_page = 1;

                var self = this;
                this.loadData(function() {
                    WisiloHelper.deactivateSortLoader(`button_sort_WisiloMedia_${self.sort_variable}`, self.sort_direction)
                });
            },
            select_all_row: function(sender) {
                if (!sender) {
                    return;
                }
                
                $(".select_wisilomedia_row").prop("checked", sender.checked);
                this.updateCheckboxStates();
            },
            select_wisilomedia_row: function() {
                this.updateCheckboxStates();
            },
            updateCheckboxStates: function() {
                var checkboxCount = $(".select_wisilomedia_row").length;
                var selectedCount = $(".select_wisilomedia_row:checked").length;
                
                if (0 == selectedCount) {
                    $("#select_WisiloMedia_rows").prop("checked", false);
                    
                    $("#buttonDeleteWisiloMedia").hide();
                    $("#buttonNewWisiloMedia").show();
                } else {
                    $("#buttonDeleteWisiloMedia > .selected-count").html(selectedCount);
                    
                    $("#buttonNewWisiloMedia").hide();
                    $("#buttonDeleteWisiloMedia").show();

                    if (selectedCount == checkboxCount) {
                        $("#select_WisiloMedia_rows").prop("checked", true);
                    } else {
                        $("#select_WisiloMedia_rows").prop("checked", false);
                    }
                }
            },
            cleanCheckedBoxes: function() {
                $(".select_wisilomedia_row").prop("checked", false);
                this.updateCheckboxStates();
            },
            deleteSelectedRows: function() {
                var selectedCheckboxes = $(".select_wisilomedia_row:checked");
                var selectedIds = [];
                for (var i = 0; i < selectedCheckboxes.length; i++) {
                    selectedIds.push(selectedCheckboxes[i].getAttribute("data-row-id"));
                }

                this.formDelete.selected_ids = selectedIds;

                $("#modalDeleteMedia").modal();
                /* Vue.swal.fire({
                    title: self.$t("Selected records will be deleted."),
                    text: self.$t("Do you confirm?"),
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: self.$t("Continue"),
                    cancelButtonText: self.$t("Cancel")
                }).then((result) => {
                    if (result.isConfirmed) {
                        self.formDelete.selected_ids = WisiloHelper.getTableSelectedRowIds("WisiloMedia");
                        self.submitDeleteForm();
                    }
                }); */
            },
            submitDeleteForm: function () {
                var self = this;
                self.$Progress.start();
                self.formDelete.post(WisiloHelper.getAPIURL("wisilomedia/delete_files"))
                    .then(({ data }) => {
                        self.$Progress.finish();
                        self.delete_form.has_error = data.has_error;
                        self.delete_form.error_msg = data.error_msg;
                    }).catch(({ data }) => {
                        self.$Progress.fail();
                    }).finally(function(){
                        if (!self.delete_form.has_error) {
                            self.loadData(function(){
                                Vue.swal.fire({
                                    position: 'top-end',
                                    title: self.$t("Selected records have been deleted."),
                                    icon: 'success',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                    onClose: () => {
                                        $("#modalDeleteMedia").modal("hide")
                                    }
                                });
                            });
                        } else {
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.delete_form.error_msg,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 10000,
                                timerProgressBar: true
                            });
                        }
                    });
            }
        },
        mounted() {
            this.main_folder = WisiloHelper.getMainFolder();

            this.loadData(function(){
                WisiloHelper.setDefaultSortButton("button_sort_WisiloMedia_id");
            });
        },
        updated() {
            
        }
    }
</script>
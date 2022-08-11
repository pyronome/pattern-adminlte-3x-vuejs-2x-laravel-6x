<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/profile/detail'">{{ $t("Profile Detail") }}</router-link></li>
                                <li class="breadcrumb-item active">{{ $t("Profile Edit") }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>
            <section class="content" id="sectionSearch">
                <div class="container-fluid">
                    <div class="card recordlist-card">
                        <div class="card-body">
                            <div class="">
                                <div class="input-group input-group-sm divSearchBar float-right" style="">
                                    <input type="text"
                                        id="searchText" name="searchText"
                                        @keyup="search_list" v-model="search_text"
                                        class="form-control float-right inputSearchBar"
                                        v-bind:placeholder="$t('Search')" autocomplete="off">
                                    <div class="input-group-append labelSearchBar">
                                        <button type="button" class="btn btn-default ">
                                            <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                                            <i class="fas fa-search text-primary"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>           
                    </div>  
                </div>
            </section>
            
            <section class="content">
                <div class="container-fluid" id="modelFormcontainer">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12">
                            <form id="WisiloUserForm"
                                class=""
                                @submit.prevent="submitForm"
                                @keydown="WisiloUserForm.onKeydown($event)">
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="lead mb-0"><b>{{ $t('Profile Information') }}</b></h2>
                                        <p class="text-muted text-sm config-desc">
                                            {{  $t('You can edit profile information using this section.') }}
                                        </p>
                                    </div>
                                    <div class="card-body">
                                        <input type="hidden" v-model="WisiloUserForm.id" id="id" name="id">
                                        <div class="row">
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 searchable-container" data-search-text="fullname">
                                                <label for="WisiloUserForm_fullname" class="detail-label">{{ $t('Fullname') }}  </label>
                                                <input type="text"
                                                    v-model="WisiloUserForm.fullname"
                                                    class="form-control "
                                                    id="WisiloUserForm_fullname"
                                                    name="WisiloUserForm_fullname">
                                            </div> 
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 searchable-container" data-search-text="username">
                                                <label for="WisiloUserForm_username" class="detail-label">{{ $t('Username') }} <span class="required">*</span></label>
                                                <input type="text"
                                                    v-model="WisiloUserForm.username"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': WisiloUserForm.errors.has('username') }"
                                                    id="WisiloUserForm_username"
                                                    name="WisiloUserForm_username">
                                                <has-error :form="WisiloUserForm" field="username"></has-error>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 searchable-container" data-search-text="email">
                                                <label for="WisiloUserForm_email" class="detail-label">{{ $t('Email') }} <span class="required">*</span></label>
                                                <input type="email"
                                                    v-model="WisiloUserForm.email"
                                                    class="form-control "
                                                    :class="{ 'is-invalid': WisiloUserForm.errors.has('email') }"
                                                    id="WisiloUserForm_email"
                                                    name="WisiloUserForm_email">
                                                <has-error :form="WisiloUserForm" field="email"></has-error>
                                            </div>
                                            <div class="form-group col-lg-12 col-md-12 col-xs-12 searchable-container" data-search-text="profile image">
                                                <label for="WisiloUserForm_profile_img" class="detail-label">{{ $t('Profile Image') }}  </label>
                                                <div class="input-field">
                                                    <input type="hidden"
                                                        class="form-control"
                                                        id="WisiloUserForm_profile_img"
                                                        name="WisiloUserForm_profile_img"
                                                        v-model="WisiloUserForm.profile_img"
                                                        :class="{ 'is-invalid': WisiloUserForm.errors.has('profile_img') }"
                                                        data-target-field="WisiloUser/profile_img"
                                                        data-media-type="2"
                                                        data-max-file-count="1"/>
                                                    <button type="button"
                                                        id="buttonBrowseprofile_imgFiles"
                                                        name="buttonBrowseprofile_imgFiles"
                                                        class="buttonBrowseFile btn btn-primary show_by_permission_must_update"
                                                        data-target-file-list="ulprofile_imgFileList">
                                                        <i class="ion-ios-folder"></i>&nbsp;{{ $t('Browse...') }}
                                                    </button>
                                                    <has-error :form="WisiloUserForm" field="profile_img"></has-error>
                                                    <ul id="ulprofile_imgFileList" class="col s12 collection ulFileList" data-target-input-id="WisiloUserForm_profile_img">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <dropzone upload-url="/api/wisilomedia/post" style="display:none;"></dropzone>

                <div class="container-fluid"  id="WisiloUserConfigFormContainer" style="padding-bottom:100px;">
                </div>

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-xs-12">
                        <button type="button"
                            id="saveConfigForm" 
                            @click="submitForm"
                            class="btn btn-success btn-md btn-on-card float-right sticky-btn">
                            {{ $t('Save') }}
                        </button>
                    </div>
                </div>  
            </section>
        </div>
        <input type="hidden" id="controller" value="configuration">

        <div class="scriptTemplates">
            <script type="text/html" id="noResultTemplate">
                <div class="alert alert-warning" role="alert">
                    {{ $t('No results found.') }}
                </div>
            </script>
            
            <script type="text/html" id="groupMainTemplate">
                <div class="card config-maingroup toggle-able" data-key="__group_key__">
                    <div class="card-header">
                        <h2 class="lead mb-0"><b>{{ $t('__group_title__') }}</b></h2>
                        <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12" id="groupContainer__group_key__">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-md-3 col-sm-12">
                                <div class="nav flex-column nav-tabs__delete__ h-100 hide-on-mobile" 
                                    id="groupTabContainer__group_key__" 
                                    role="tablist" 
                                    aria-orientation="vertical">
                                </div>
                                <select class="dropdownTabGroup__delete__ show-on-mobile" 
                                    id="groupDropdownTabContainer__group_key__" 
                                    data-tab-container-id="groupTabContentContainer__group_key__"></select>
                            </div>
                            <div class="col-lg-8 col-md-9 col-sm-12">
                                <div class="tab-content" id="groupTabContentContainer__group_key__">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </script>
            <!-- <script type="text/html" id="groupTemplateLevel0">
                <div class="row config-maingroup toggle-able" data-key="__group_key__">
                    <div class="col-lg-4 col-md-4 col-xs-12 ">
                        <h4 class="form-part-header">{{ $t('__group_title__') }}</h4>
                        <h6 class="form-part-instructions">
                            {{ $t('__description__') }}
                        </h6>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12">
                        <div class="row" id="groupContainer__group_key__">
                        </div>                    
                    </div>
                </div>
            </script> -->


            <script type="text/html" id="groupTabTemplate">
                <a class="nav-link toggle-able" 
                    data-key="__group_key__" 
                    id="__group_key_converted__-tab" 
                    data-toggle="pill" 
                    href="#__group_key_converted__-content" 
                    role="tab" 
                    aria-controls="__group_key_converted__-content" 
                    aria-selected="false">
                    {{ $t('__group_title__') }}
                </a>
            </script>
            <script type="text/html" id="groupDropdownTabTemplate">
                <option value="__group_key_converted__-tab">
                    {{ $t('__group_title__') }}
                </option>
            </script>

            <script type="text/html" id="groupContentTemplate">
                <div class="tab-pane fade toggle-able" 
                    data-key="__group_key__" 
                    id="__group_key_converted__-content" 
                    role="tabpanel" 
                    aria-labelledby="__group_key_converted__-tab">
                    <div class="row" id="groupContainer__group_key__">
                    </div>
                </div>
            </script>

            <script type="text/html" id="groupCardTemplate">
                <div class="card toggle-able" data-key="__group_key__">
                    <div class="card-header">
                        <h2 class="lead mb-0"><b>{{ $t('__group_title__') }}</b></h2>
                        <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row" id="groupContainer__group_key__">
                        </div>
                    </div>
                </div>
            </script>

            <script type="text/html" id="groupTemplate">
                <div class="col-lg-12 toggle-able" data-key="__group_key__">
                    <div style="border-bottom: 1px solid #6c757d;">
                        <label style="font-size: 1.1rem;font-weight: 400;margin: 0;">{{ $t('__group_title__') }}</label>
                        <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    </div>
                    <div class="row mt-4 mb-4" id="groupContainer__group_key__">
                    </div>
                </div>
            </script>

            <script type="text/html" id="selection_groupTemplateLevel0">
                <div class="row config-maingroup toggle-able" data-key="__group_key__">
                    <div class="col-lg-4 col-md-4 col-xs-12 ">
                        <h4 class="form-part-header">
                            <span id="__group_key__-label" class="field-label">{{ $t('__group_title__') }}</span> <span class="__required_class__">*</span>
                            <button type="button"
                                class="use-parameter-default-value__delete__"
                                data-type="selection_group"
                                data-key="__group_key__"
                                default-value="__default_value__"
                                title="__use_default_title__">
                                <span>{{ $t('Default') }}</span>
                            </button>
                        </h4>
                        <h6 class="form-part-instructions">
                            {{ $t('__description__') }}
                        </h6>
                    </div>
                    <div class="col-lg-8 col-md-8 col-xs-12">
                        <div class="row config-parameter__delete__ config-selection-group"
                            data-type="selection_group"
                            data-key="__group_key__"
                            data-min-selection="__min_selection__"
                            data-max-selection="__max_selection__"
                            id="groupContainer__group_key__">
                        </div>
                        <div class="row">
                            <div class="col-12 text-muted small">__selection_hint__</div>
                        </div>
                        <div class="row config-parameter-error" id="__group_key__-error">
                            <span class="col-12 error invalid-feedback"></span>
                        </div>                
                    </div>
                </div>
            </script>

            <script type="text/html" id="selection_groupTemplateLevel1">
                <div class="card toggle-able" data-key="__group_key__">
                    <div class="card-header">
                        <h2 class="lead mb-0">
                            <b><span id="__group_key__-label" class="field-label">{{ $t('__group_title__') }}</span></b> <span class="__required_class__">*</span>
                            <button type="button"
                                class="use-parameter-default-value__delete__"
                                data-type="selection_group"
                                data-key="__group_key__"
                                default-value="__default_value__"
                                title="__use_default_title__">
                                <span>{{ $t('Default') }}</span>
                            </button>
                        </h2>
                        <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    </div>
                    <div class="card-body">
                        <div class="row config-parameter__delete__ config-selection-group"
                            data-type="selection_group"
                            data-key="__group_key__"
                            data-min-selection="__min_selection__"
                            data-max-selection="__max_selection__"
                            id="groupContainer__group_key__">
                        </div>
                        <div class="row">
                            <div class="col-12 text-muted small">__selection_hint__</div>
                        </div>
                        <div class="row config-parameter-error" id="__group_key__-error">
                            <span class="col-12 error invalid-feedback"></span>
                        </div>
                    </div>
                </div>
            </script>

            <script type="text/html" id="selection_groupTemplate">
                <div class="col-lg-12 toggle-able" data-key="__group_key__">
                    <div style="border-bottom: 1px solid #6c757d;">
                        <label style="font-size: 1.1rem;font-weight: 400;margin: 0;">
                            <span id="__group_key__-label" class="field-label">{{ $t('__group_title__') }}</span> <span class="__required_class__">*</span>
                            <button type="button"
                                class="use-parameter-default-value__delete__"
                                data-type="selection_group"
                                data-key="__group_key__"
                                default-value="__default_value__"
                                title="__use_default_title__">
                                <span>{{ $t('Default') }}</span>
                            </button>
                        </label>
                        <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    </div>
                    <div class="row mt-4 config-parameter__delete__ config-selection-group"
                        data-type="selection_group"
                        data-key="__group_key__"
                        data-min-selection="__min_selection__"
                        data-max-selection="__max_selection__"
                        id="groupContainer__group_key__">
                    </div>
                    <div class="row">
                        <div class="col-12 text-muted small">__selection_hint__</div>
                    </div>
                    <div class="row mb-4 config-parameter-error" id="__group_key__-error">
                        <span class="col-12 error invalid-feedback"></span>
                    </div>
                </div>
            </script>

            <script type="text/html" id="selection_itemTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox"
                            id="__field_key__"
                            name="__field_key__"
                            class="selection-item__delete__ __parent_key__-selection_item"
                            data-parentkey="__parent_key__"
                            data-type="checkbox">
                        <label for="__field_key__" class="detail-label">
                            {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                            <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                        </label>
                    </div>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>
            
            <script type="text/html" id="checkboxTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox"
                            id="__field_key__"
                            name="__field_key__"
                            class="config-parameter__delete__"
                            data-type="checkbox">
                        <label for="__field_key__" class="detail-label">
                            {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                            <button type="button"
                                class="use-parameter-default-value__delete__"
                                data-type="checkbox"
                                data-key="__field_key__"
                                default-value="__default_value__"
                                title="__use_default_title__">
                                <span>{{ $t('Default') }}</span>
                            </button>
                            <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                        </label>
                    </div>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>

            <script type="text/html" id="colorpickerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="colorpicker"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <div class="input-group">
                        <input type="text"
                            class="form-control color-picker__delete__ config-parameter__delete__"
                            data-type="colorpicker"
                            id="__field_key__"
                            name="__field_key__">
                        <div class="input-group-append">
                            <span class="input-group-text" id="__field_key__append" style="padding-left:100px;"></span>
                        </div>
                    </div>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>

            <script type="text/html" id="datepickerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="datepicker"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="date"
                        class="form-control config-parameter__delete__"
                        data-type="datepicker"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>

            <script type="text/html" id="datetimepickerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="datetimepicker"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="datetime-local"
                        class="form-control config-parameter__delete__"
                        data-type="datetimepicker"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>

            <script type="text/html" id="dropdownTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="dropdown"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            data-multiple="__multiple__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <select __multiple__
                        id="__field_key__"
                        name="__field_key__"
                        class="form-control configselect2__delete__ config-parameter__delete__"
                        data-type="dropdown"
                        data-key="__field_key__"
                        style="width:100%;">
                    </select>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="fileTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="file"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <div class="input-field">
                        <input type="file" id="__field_key__" name="__field_key__" 
                            accept="__file_types__"
                            data-type="file" 
                            class="form-input config-file__delete__ config-parameter__delete__"
                            style="display:none;">
                        <button type="button" class="btn btn-primary btn-file-trigger__delete__" data-triggered-id="__field_key__">
                            {{ $t('Browse...') }}
                        </button>
                        <span id="spanFileName__field_key__"></span>
                        <input type="hidden" id="__field_key__-file_name">
                        <input type="hidden" id="__field_key__-file_value">
                        <input type="hidden" id="__field_key__-file_process_type">
                        <button type="button" id="__field_key__-download_btn" class="text-btn file_download__delete__"
                            data-key="__field_key__">
                            <span>{{ $t('__value__') }}</span>
                        </button>
                        <button type="button" id="__field_key__-download_default_btn" class="text-btn default_file_download__delete__"
                            data-key="__field_key__">
                            <span>{{ $t('__default_value__') }}</span>
                        </button>
                        <button type="button" id="__field_key__-file_remove_btn" 
                            class="text-btn text-danger file_remove__delete__" 
                            data-key="__field_key__"
                            title="__remove_file_title__">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="htmlEditorTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="html_editor"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <textarea id="__field_key__"
                        name="__field_key__"
                        data-type="html_editor"
                        class="textarea vue-editor__delete__ config-parameter__delete__"
                        rows="5"></textarea>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="iconPickerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="iconpicker"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <button type="button" id="__field_key__" class="btn btn-outline-secondary icon-picker__delete__"></button>
                    <input type="hidden" id="__field_key__-value" name="__field_key__-value" data-key="__field_key__" data-type="iconpicker" class="item-widget config-parameter__delete__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="integerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="shorttext"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="number"
                        class="form-control config-parameter__delete__"
                        data-type="shorttext"
                        id="__field_key__"
                        name="__field_key__"
                        min="__min__"
                        max="__max__"
                        step="__step__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="link_buttonTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <a class="btn btn-primary btn-md btn-on-card text-white"
                        target="_blank"
                        href="__url__">
                        <span>{{ $t('__field_title__') }}</span>
                    </a>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>
            <script type="text/html" id="link_textTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <a class=""
                        target="_blank"
                        href="__url__">
                        <span>{{ $t('__field_title__') }}</span>
                    </a>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>
            <script type="text/html" id="numberTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="shorttext"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="number"
                        class="form-control config-parameter__delete__"
                        data-type="shorttext"
                        id="__field_key__"
                        name="__field_key__"
                        min="__min__"
                        max="__max__"
                        step="__step__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="passwordTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="shorttext"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="password"
                        class="form-control config-parameter__delete__"
                        data-type="shorttext"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="radioTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="radio"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <div id="container_radio___field_key__" 
                        class="clearfix config-parameter__delete__"
                        data-type="radio"   
                        data-key="__field_key__">
                        __radio_options_html__
                    </div>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="radioOptionTemplate">
                <div class="icheck-primary">
                    <input type="radio"
                        id="__field_key____option_index__"
                        name="__field_key__"
                        class="blue-style"
                        value="__option_value__">
                    <label for="__field_key____option_index__" class="detail-label">{{ $t('__option_title__') }}</label>
                </div>
            </script>
            <script type="text/html" id="readonly_contentTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <h6>{{ $t('__field_title__') }}</h6>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <p>{{ $t('__content__') }}</p>
                </div>
            </script>
            <script type="text/html" id="shorttextTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="shorttext"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            v-bindtitle="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="text"
                        class="form-control config-parameter__delete__"
                        data-type="shorttext"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="switchTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <input type="checkbox"
                        id="__field_key__"
                        name="__field_key__"
                        class="vue-switch__delete__ config-parameter__delete__"
                        data-type="switch"
                        data-key="__field_key__"
                        data-bootstrap-switch>
                    <label for="__field_key__" class="switch-label">
                        <div class="bootstrap-switch bootstrap-switch-wrapper fake-switch-container" style="width: 88px;">
                            <div class="bootstrap-switch-container" style="width: 129px; margin-left: 0px;">
                                <span class="bootstrap-switch-handle-on bootstrap-switch-primary" style="width: 43px;">{{ $t('ON') }}</span>
                                <span class="bootstrap-switch-label" style="width: 43px;">&nbsp;</span>
                                <span class="bootstrap-switch-handle-off bootstrap-switch-default" style="width: 43px;">{{ $t('OFF') }}</span>
                            </div>
                        </div>
                        {{ $t('__field_title__') }}
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="switch"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>
            <script type="text/html" id="textareaTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="textarea"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <textarea rows="5"
                        id="__field_key__"
                        name="__field_key__"
                        class="form-control config-parameter__delete__"
                        data-type="textarea"></textarea>
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="timepickerTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <label for="__field_key__" class="detail-label">
                        <span id="__field_key__-label" class="field-label">{{ $t('__field_title__') }}</span> <span class="__required_class__">*</span>
                        <button type="button"
                            class="use-parameter-default-value__delete__"
                            data-type="timepicker"
                            data-key="__field_key__"
                            default-value="__default_value__"
                            title="__use_default_title__">
                            <span>{{ $t('Default') }}</span>
                        </button>
                        <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                    </label>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                    <input type="time"
                        class="form-control config-parameter__delete__"
                        data-type="timepicker"
                        id="__field_key__"
                        name="__field_key__">
                    <div class="config-parameter-error" id="__field_key__-error">
                        <span class="error invalid-feedback"></span>
                    </div>
                </div>
            </script>
            <script type="text/html" id="toggleTemplate">
                <div class="__grid_class__ mb-20 toggle-able" data-key="__field_key__">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox"
                            id="__field_key__"
                            name="__field_key__"
                            class="config-parameter__delete__ config-toggle__delete__"
                            data-toggle-elements="__toggle_elements__"
                            data-type="toggle">
                        <label for="__field_key__" class="detail-label">
                            {{ $t('__field_title__') }} <span class="__required_class__">*</span>
                            <i class="fas fa-info-circle __hint_class__" data-toggle="__data_toggle__" data-placement="top" title="__hint__"></i>
                        </label>
                    </div>
                    <p class="text-muted text-sm config-desc">{{ $t('__description__') }}</p>
                </div>
            </script>
        </div>

        <span class="d-none" id="btnUseDefaultTitle">{{ $t('Set default value') }}</span>
        <span class="d-none" id="btnRemoveFileTitle">{{ $t('Remove file') }}</span>
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>

export default {
    data() {
        return {
            current_id: 0,
            main_folder: '',
            pagename: '',
            files: [],
            WisiloUserForm: new Form({
                'debug_mode': false,
                'id': this.current_id,
                'fullname': '',
                'username': '',
                'email': '',
                'profile_img': ''
            }),
            has_config_parameter: false,
            list: [],
            search_text: '',
            current_page: 1,
            show_pagination: false,
            sort_variable: 'id',
            sort_direction: 'desc',  
            formConfig: new Form({
                'config_data': []
            }),
            uploadedFiles: {},                  
            formDelete: new Form({
                'selected_ids': []
            }),
            delete_form: {
                has_error: false,
                error_msg: ''
            },
            listByKey: [],
            init_toggles:false,
            page: {
                is_ready: false,
                has_server_error: false,
                has_post_error: false,
                post_error_msg: '',
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                is_configlist_loading: false,
                is_configlist_loaded: false,
                is_data_loading: false,
                is_data_loaded: false,
                is_files_loading: false,
                is_files_loaded: false,
                external_files: [
                    ("/js/wisilo/bootstrap-switch/js/bootstrap-switch.js"),
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/wisilo/select2/dist/js/select2.min.js"),
                ],
            },
            body_loader_active: false,
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

            if (!self.page.authorization.status) {
                self.$Progress.finish();
                self.page.is_ready = true;
                return;
            }

            if (!self.page.is_variables_loaded 
                && !self.page.is_data_loaded 
                && !self.page.is_files_loaded 
                && !self.page.is_configlist_loaded) {
                self.$Progress.start();
            }

            if (!self.page.is_variables_loaded) {
                self.loadPageVariables();
            } else {
                if (!self.page.is_data_loaded) {
                    self.loadModelData();
                } else if (!self.page.is_files_loaded) {
                    self.load_files();
                } else if (self.page.is_configlist_loaded) {
                    self.$Progress.finish();
                    self.page.is_ready = true;
                } else {
                    self.loadData(
                        function() {
                            WisiloHelper.initializePermissions(self.page.variables, true);
                            self.renderForm();
                            if (!self.has_config_parameter) {
                                document.getElementById("sectionSearch").style.display = "none";
                                document.getElementById("WisiloUserConfigFormContainer").style.display = "none";
                            }
                            WisiloHelper.initializePageFiles(self.files);
                        }
                    );
                }
            }
        },
        load_files: function () {
            if (this.page.is_files_loading) {
                return;
            }

            this.page.is_files_loading = true;

            axios.get(WisiloHelper.getAPIURL("profile/get_files"))
                .then(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.files = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_files_loaded = true;
                    this.page.is_files_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        loadModelData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("profile/get"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.WisiloUserForm.fill(data.object);
                    this.current_id = this.WisiloUserForm.id;
                    this.has_config_parameter = data.has_config_parameter;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            self.$Progress.start();
            self.WisiloUserForm.post(WisiloHelper.getAPIURL("profile/post"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.WisiloUserForm.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Your changes could not be saved. Please check your details and try again.");
                    }
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!self.page.has_post_error) {
                            self.submitConfigForm();
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
        setListByKey: function() {
            var listByKey = {};
            var elements = this.list;

            for (let index = 0; index < elements.length; index++) {
                const element = elements[index];
                listByKey[element.__key] = element;
            }

            this.listByKey = listByKey;
        },
        renderForm: function() {
            document.getElementById("WisiloUserConfigFormContainer").innerHTML = "";
            
            var self = this;
            var elementHTML = "";
            var parentKey = "";
            var temp = "";

            if (0 == self.list.length) {
                document.getElementById("WisiloUserConfigFormContainer").innerHTML = document.getElementById("noResultTemplate").innerHTML;
            }

            self.list.forEach(element => {
                elementHTML = "";
                if ("group" == element.type) {
                    if (0 == element.level) {
                        elementHTML = self.getGroupMainHTML(element);
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    } else if (1 == element.level) {
                        parentKey = self.getParentKey(element.__key);
                        
                        elementHTML = self.getGroupTabHTML(element);
                        document.getElementById("groupTabContainer" + parentKey).innerHTML += elementHTML;

                        elementHTML = self.getGroupDropdownTabHTML(element);
                        document.getElementById("groupDropdownTabContainer" + parentKey).innerHTML += elementHTML;
                        
                        elementHTML = self.getGroupContentHTML(element);
                        document.getElementById("groupTabContentContainer" + parentKey).innerHTML += elementHTML;
                    } else if (2 == element.level) {
                        parentKey = self.getParentKey(element.__key);
                        elementHTML = self.getGroupCardHTML(element);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        parentKey = self.getParentKey(element.__key);
                        elementHTML = self.getGroupHTML(element);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    }
                } else if ("selection_group" == element.type) {
                    elementHTML = self.getSelectionGroupHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("selection_item" == element.type) {
                    elementHTML = self.getSelectionItemHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("checkbox" == element.type) {
                    elementHTML = self.getCheckboxHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("colorpicker" == element.type) {
                    elementHTML = self.getColorPickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("datepicker" == element.type) {
                    elementHTML = self.getDatePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("datetimepicker" == element.type) {
                    elementHTML = self.getDateTimePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("dropdown" == element.type) {
                    elementHTML = self.getDropdownHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }

                    self.setDropdownOptions(element);
                } else if ("file" == element.type) {
                    elementHTML = self.getFileHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }

                    if ("" != element.value) {
                        document.getElementById(element.__key + "-download_default_btn").style.display = "none";
                    } else {
                        document.getElementById(element.__key + "-download_btn").style.display = "none";
                        document.getElementById(element.__key + "-file_remove_btn").style.display = "none";

                        if ("" == element.default_value) {
                            document.getElementById(element.__key + "-download_default_btn").style.display = "none";
                        }
                    }
                } else if ("html_editor" == element.type) {
                    elementHTML = self.getHTMLEditorHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("iconpicker" == element.type) {
                    elementHTML = self.getIconPickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("integer" == element.type) {
                    elementHTML = self.getIntegerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("link_button" == element.type) {
                    elementHTML = self.getLinkButtonHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("link_text" == element.type) {
                    elementHTML = self.getLinkTextHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("number" == element.type) {
                    elementHTML = self.getNumberHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("password" == element.type) {
                    elementHTML = self.getPasswordHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("radio" == element.type) {
                    elementHTML = self.getRadioHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("readonly_content" == element.type) {
                    elementHTML = self.getReadonlyContentHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("shorttext" == element.type) {
                    elementHTML = self.getShorttextHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("switch" == element.type) {
                    elementHTML = self.getSwitchHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("textarea" == element.type) {
                    elementHTML = self.getTextareaHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("timepicker" == element.type) {
                    elementHTML = self.getTimePickerHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                } else if ("toggle" == element.type) {
                    elementHTML = self.getToggleHTML(element);

                    if (element.__key.includes(".")) {
                        parentKey = self.getParentKey(element.__key);
                        if (document.getElementById("groupContainer" + parentKey)) {
                            document.getElementById("groupContainer" + parentKey).innerHTML += elementHTML;
                        } else {
                            document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                        }
                    } else {
                        document.getElementById("WisiloUserConfigFormContainer").innerHTML += elementHTML;
                    }
                }  
            });

            var switchInputs = self.$el.querySelectorAll(".vue-switch");
            switchInputs.forEach(switchInput => {
                WisiloHelper.updateSwitch(switchInput);
            });

            $(".config-toggle").off('change').on('change', function () {
                self.doConfigToggleChange(this);
            });

            self.initToggleElements();

            $(".textarea.vue-editor").summernote({
                "font-styles": false,
                "height": 150,
                codemirror: {
                    theme: "monokai"
                },
                callbacks: {
                    onBlur: function() {
                        this.dispatchEvent(new Event('input'));
                    }
                }
            });

            $(".color-picker").colorpicker();

            $(".color-picker").on("colorpickerChange", function(event) {
                var colorHex = event.color.toString();
                var elAppend = document.getElementById(this.id + "append")
                elAppend.style.background = colorHex;
                elAppend.style.borderColor = colorHex;
            });
            
            //initialize ederken
            /* $("#iconbackgroundPicker").val(data.iconbackground);
            $("#iconbackgroundPicker").trigger('change');
            $("#ulWidgetEditor_icon").css("background", data.iconbackground); */

            var iconPickerOptions = {
                searchText: "...",
                labelHeader: "{0} / {1}",
                cols: 4, rows: 4, 
                footer: false, 
                iconset: "fontawesome5"
            };

            var iconPicker = $(".icon-picker").iconpicker(iconPickerOptions);
            iconPicker.on("change", function (e) {
                document.getElementById(this.id + "-value").value = e.icon;
            });

            $(".btn-file-trigger").on('click', function(e){
                document.getElementById(this.getAttribute("data-triggered-id")).click();
            });

            $(".config-file").on('change', function(e){
                self.updateFile(this); 
            });

            $(".file_download").on('click', function(e){
                self.downloadFile(this.getAttribute("data-key"));
            });

            $(".default_file_download").on('click', function(e){
                self.downloadDefaultFile(this.getAttribute("data-key"));
            });

            $(".file_remove").on('click', function(e){
                self.removeFile(this.getAttribute("data-key"));
            });

            self.initailizeSelect2();

            $('[data-toggle="tooltip"]').tooltip(); 

            $(".selection-item").off('change').on('change', function () {
                self.selectionItemChanged(this);
            });

            $('.nav-tabs > .nav-link:first-child').trigger('click')

            $(".dropdownTabGroup").off('change').on('change', function () {
                self.dropdownTabGroupChanged(this);
            });

            var dropdownTabs = $(".dropdownTabGroup");
            for (let index = 0; index < dropdownTabs.length; index++) {
                let elementKey = dropdownTabs[index].id.replace("groupDropdownTabContainer", "");
                if (0 == $("option", dropdownTabs[index]).length) {
                    document.getElementById("groupTabContainer" + elementKey).style.display = "none";
                    document.getElementById("groupDropdownTabContainer" + elementKey).style.display = "none";
                    document.getElementById("groupTabContentContainer" + elementKey).style.display = "none";
                }
            }

            setTimeout(function(){
                $(".use-parameter-default-value").on('click', function(e){
                    self.setDefaultValue(this);
                });

                self.setValues();
            }, 300);
        },
        dropdownTabGroupChanged: function(select) {
            /* var tabContentContainer = document.getElementById(select.getAttribute("data-tab-container-id"));
            $(".tab-pane", tabContentContainer).hide();
            $(document.getElementById(select.value)).show(); */
            document.getElementById(select.value).click();
        },
        selectionItemChanged: function(changedItem) {
            /* var parentkey = changedItem.getAttribute("data-parentkey");
            var parentContainer = document.getElementById("groupContainer" + parentkey);
            var min_selection = parentContainer.getAttribute("data-min-selection");
            var max_selection = parentContainer.getAttribute("data-max-selection");
            var selectionItems = $(".selection-item", parentContainer);
            for (let index = 0; index < selectionItems.length; index++) {
                const selectionItem = selectionItems[index];

                if (selectionItem.id == changedItem.id) {
                    console.log("selectionItem_id:" + selectionItem.id)
                }                
            } */
        },
        getSelectionGroupHTML: function(element) {
            var templateHTML = "";
            if (0 == element.level) {
                templateHTML = document.getElementById("selection_groupTemplateLevel0").innerHTML
            } else if (1 == element.level) {
                templateHTML = document.getElementById("selection_groupTemplateLevel1").innerHTML
            } else {
                templateHTML = document.getElementById("selection_groupTemplate").innerHTML
            }

            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var min_selection = element.min_selection;
            var max_selection = element.max_selection;

            var selectionHint = "";
            if ((0 == min_selection) && (max_selection > 0)) {
                selectionHint = "You can choose maximum " + max_selection + " option(s).";
            } else if ((min_selection > 0) && (max_selection > 0)) {
                selectionHint = "You must choose minimum " + min_selection + " option(s). You can choose maximum " + max_selection + " option(s).";
            } else if ((min_selection > 0) && (0 == max_selection)) {
                selectionHint = "You must choose minimum " + min_selection + " option(s).";
            }

            return templateHTML
                    .replace(/__delete__/g, "")
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key)
                    .replace(/__min_selection__/g, min_selection)
                    .replace(/__max_selection__/g, max_selection)
                    .replace(/__default_value__/g, element.default_value)
                    .replace(/__required_class__/g, requiredClass)
                    .replace(/__selection_hint__/g, selectionHint)
                    .replace(/__use_default_title__/g, document.getElementById("btnUseDefaultTitle").innerHTML);
        },
        getSelectionItemHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("selection_itemTemplate").innerHTML);
            return resultHTML.replace(/__parent_key__/g, element.parent);
        },
        getGroupMainHTML: function(element) {
            var templateHTML = document.getElementById("groupMainTemplate").innerHTML
            return templateHTML
                    .replace(/__delete__/g, "")
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key);
        },
        getGroupTabHTML: function(element) {
            var templateHTML = document.getElementById("groupTabTemplate").innerHTML
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key)
                    .replace(/__group_key_converted__/g, element.__key.replace(/\./g, ""));
        },
        getGroupDropdownTabHTML: function(element) {
            var templateHTML = document.getElementById("groupDropdownTabTemplate").innerHTML
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key)
                    .replace(/__group_key_converted__/g, element.__key.replace(/\./g, ""));
        },
        getGroupContentHTML: function(element) {
            var templateHTML = document.getElementById("groupContentTemplate").innerHTML
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key)
                    .replace(/__group_key_converted__/g, element.__key.replace(/\./g, ""));
        },
        getGroupCardHTML: function(element) {
            var templateHTML = document.getElementById("groupCardTemplate").innerHTML;
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key);
        },
        getGroupHTML: function(element) {
            var templateHTML = document.getElementById("groupTemplate").innerHTML;
            return templateHTML
                    .replace(/__group_title__/g, element.title)
                    .replace(/__description__/g, element.description)
                    .replace(/__group_key__/g, element.__key);
        },
        getCheckboxHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("checkboxTemplate").innerHTML);
        },
        getColorPickerHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("colorpickerTemplate").innerHTML);
        },
        getDatePickerHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("datepickerTemplate").innerHTML);
        },
        getDateTimePickerHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("datetimepickerTemplate").innerHTML);
        },
        getDropdownHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("dropdownTemplate").innerHTML);

            var multiple = "";
            if (element.multiple) {
                multiple = "multiple";
            }

            return resultHTML
                .replace(/__multiple__/g, multiple)
                .replace(/__field_key_converted__/g, element.__key.replace(/\./g, ""));
        },
        setDropdownOptions(element) {
            //var selectId = element.__key.replace(/\./g, "");
            var selectId = element.__key;
            var values = element.option_values.split("\n");
            
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                document.getElementById(selectId).innerHTML +=
                '<option value="' + values[index] + '">' + titles[index] + '</option>'
            }
        },
        getDropdownOptions(element) {
            var options = [];
            var option = {};
            var values = element.option_values.split("\n");
            
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                option["id"] = values[index];
                option["text"] = titles[index];
                options.push(option);
            }

            return options;
        },
        initailizeSelect2(){
            $(".configselect2").select2();

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
        getFileHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("fileTemplate").innerHTML);

            var fileTypes = "";
            if ("" != element.file_types) {
                fileTypes = element.file_types;
            }

            return resultHTML
                .replace(/__file_types__/g, fileTypes)
                .replace(/__value__/g, element.value)
                .replace(/__remove_file_title__/g, document.getElementById("btnRemoveFileTitle").innerHTML);
        },
        getHTMLEditorHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("htmlEditorTemplate").innerHTML);
        },
        getIconPickerHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("iconPickerTemplate").innerHTML);
        },
        getIntegerHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("integerTemplate").innerHTML);

            return resultHTML
                .replace(/__min__/g, element.min)
                .replace(/__max__/g, element.max)
                .replace(/__step__/g, element.step);
        },
        getLinkButtonHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("link_buttonTemplate").innerHTML);

            return resultHTML.replace(/__url__/g, element.url);
        },
        getLinkTextHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("link_textTemplate").innerHTML);

            return resultHTML.replace(/__url__/g, element.url);
        },
        getNumberHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("numberTemplate").innerHTML);

            return resultHTML
                .replace(/__min__/g, element.min)
                .replace(/__max__/g, element.max)
                .replace(/__step__/g, element.step);
        },
        getPasswordHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("passwordTemplate").innerHTML);
        },
        getRadioHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("radioTemplate").innerHTML);
            var radioOptionsHTML = this.getRadioOptionsHTML(element);
            return resultHTML.replace(/__radio_options_html__/g, radioOptionsHTML);
        },
        getRadioOptionsHTML: function(element) {
            var optionsHTML = "";
            var optionTemplateHTML = document.getElementById("radioOptionTemplate").innerHTML;
            var templateHTML = "";

            var values = element.option_values.split("\n");
            var titles = element.option_titles.split("\n");
            var length = values.length;

            for (let index = 0; index < length; index++) {
                templateHTML = optionTemplateHTML;
                templateHTML = templateHTML
                    .replace(/__field_key__/g, element.__key)
                    .replace(/__option_index__/g, index)
                    .replace(/__option_value__/g, values[index])
                    .replace(/__option_title__/g, titles[index]);

                optionsHTML += templateHTML;
            }
            
            return optionsHTML;
        },
        getReadonlyContentHTML: function(element) {
            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("readonly_contentTemplate").innerHTML);
            return resultHTML.replace(/__content__/g, element.content);
        },
        getShorttextHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("shorttextTemplate").innerHTML);
        },
        getSwitchHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("switchTemplate").innerHTML);
        },
        getTextareaHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("textareaTemplate").innerHTML);
        },
        getTimePickerHTML: function(element) {
            return this.replaceTemplateHTML(element, document.getElementById("timepickerTemplate").innerHTML);
        },
        getToggleHTML: function(element) {
            this.init_toggles = true;

            var resultHTML = this.replaceTemplateHTML(element, document.getElementById("toggleTemplate").innerHTML);
            return resultHTML.replace(/__toggle_elements__/g, element.toggle_elements);
        },
        initToggleElements: function() {
            if (!this.init_toggles) {
                return;
            }

            var toggleElements = $(".config-toggle");

            for (let index = 0; index < toggleElements.length; index++) {
                const toggle = toggleElements[index];
                
                toggle.getAttribute("data-toggle-elements").split(",").forEach(element => {
                    $(".toggle-able[data-key='" + element + "']").addClass("d-none")
                });
            }
        },
        getParentKey: function(elementKey) {
            var parts = elementKey.split(".");
            parts.pop();
            return parts.join(".");
        },
        doConfigToggleChange: function(toggleCheckbox) {
            var toggle_elements = toggleCheckbox.getAttribute("data-toggle-elements").split(",");
            
            if (toggleCheckbox.checked) {
                toggle_elements.forEach(element => {
                   $(".toggle-able[data-key='" + element + "']").removeClass("d-none")
                });
            } else {
                toggle_elements.forEach(element => {
                   $(".toggle-able[data-key='" + element + "']").addClass("d-none")
                });
            }
        },
        
        replaceTemplateHTML: function(element, templateHTML) {
            var requiredClass = "d-none";
            if (element.required) {
                requiredClass = "required";
            }

            var hintClass = "d-none";
            var dataToggle = "";
            if ("" != element.hint) {
                hintClass = "";
                dataToggle = "tooltip";
            }

            return templateHTML
                .replace(/__grid_class__/g, element.grid_class)
                .replace(/__data_toggle__/g, dataToggle)
                .replace(/__default_value__/g, element.default_value)
                .replace(/__delete__/g, "")
                .replace(/__description__/g, element.description)
                .replace(/__field_key__/g, element.__key)
                .replace(/__field_title__/g, element.title)
                .replace(/__hint__/g, element.hint)
                .replace(/__hint_class__/g, hintClass)
                .replace(/__required_class__/g, requiredClass)
                .replace(/__use_default_title__/g, document.getElementById("btnUseDefaultTitle").innerHTML);
        },

        setDefaultValue: function(btn) {
            var self = this;
            var type = btn.getAttribute("data-type");
            var elementKey = btn.getAttribute("data-key");
            var val = btn.getAttribute("default-value");

            if ("checkbox" == type) {
                if ("on" == val) {
                    document.getElementById(elementKey).checked = true;
                }
            } else if (("colorpicker" == type ) && ("" != val)) {
                var colorPicker = document.getElementById(elementKey);
                $(colorPicker).val(val);
                $(colorPicker).trigger('change');
            } else if ("datepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("datetimepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("dropdown" == type) {
                if ("multiple" == btn.getAttribute("data-multiple")) {
                    $(document.getElementById(elementKey)).val(val.split(",")).trigger('change');
                } else {
                    $(document.getElementById(elementKey)).val(val).trigger('change');
                }
            } else if ("file" == type) {
                document.getElementById(elementKey + "-file_process_type").value = "set_default";
                document.getElementById(elementKey + "-download_btn").style.display = "none";
                document.getElementById(elementKey + "-file_remove_btn").style.display = "none";
                document.getElementById(elementKey + "-download_default_btn").style.display = "inline-block";
            } else if ("html_editor" == type) {
                $(document.getElementById(elementKey)).summernote("code", val);
            } else if ("iconpicker" == type) {
                if ("" == val || undefined === val) {
                    console.log("empty icon")
                    /* $(document.getElementById(elementKey)).iconpicker('setIcon', 'empty'); */
                } else{
                    $(document.getElementById(elementKey)).iconpicker('setIcon', val);
                }
            } else if ("integer" == type) {
                document.getElementById(elementKey).value = val;
            } /* else if ("link_button" == type) {
            } else if ("link_text" == type) {
            } */ else if ("number" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("password" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("radio" == type) {
                var selectorText = 'input[name="' + elementKey + '"][value="' + val + '"]';
                $(selectorText).prop('checked', true);

            }/*  else if ("readonly_content" == type) {
            } */ else if ("shorttext" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("switch" == type) {
                if ("on" == val) {
                    $(document.getElementById(elementKey)).bootstrapSwitch("state", true);
                } else {
                    $(document.getElementById(elementKey)).bootstrapSwitch("state", false);
                }
            } else if ("textarea" == type) {
                $(document.getElementById(elementKey)).val(val);
            } else if ("timepicker" == type) {
                document.getElementById(elementKey).value = val;
            } else if ("toggle" == type) {
                if ("on" == val) {
                    $(document.getElementById(elementKey)).attr("checked", true).trigger("change");
                }
            } else if ("selection_group" == type) {
                self.setSelectionGroupValue(elementKey, val);
            }
        },

        resetValues: function() {
            
        },
        setValues: function() {
            var self = this;
            self.resetValues();

            var elementKey = "";
            var val = "";
            self.list.forEach(element => {
                elementKey = element.__key;

                val = ((null != element.value) && ("" != element.value)) ? element.value : element.default_value;

                if ("checkbox" == element.type) {
                    if ("on" == val) {
                        document.getElementById(elementKey).checked = true;
                    }
                } else if (("colorpicker" == element.type ) && ("" != val)) {
                    var colorPicker = document.getElementById(elementKey);
                    $(colorPicker).val(val);
                    $(colorPicker).trigger('change');
                } else if ("datepicker" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("datetimepicker" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("dropdown" == element.type) {
                    if (element.multiple) {
                        $(document.getElementById(elementKey)).val(val.split(",")).trigger('change');
                    } else {
                        $(document.getElementById(elementKey)).val(val).trigger('change');
                    }
                } else if ("html_editor" == element.type) {
                    $(document.getElementById(elementKey)).summernote("code", val);
                } else if ("iconpicker" == element.type) {
                    if ("" == val || undefined === val) {
                        console.log("empty icon")
                        /* $(document.getElementById(elementKey)).iconpicker('setIcon', 'empty'); */
                    } else{
                        $(document.getElementById(elementKey)).iconpicker('setIcon', val);
                    }
                } else if ("integer" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("number" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("password" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("radio" == element.type) {
                    var selectorText = 'input[name="' + elementKey + '"][value="' + val + '"]';
                    $(selectorText).prop('checked', true);
                } else if ("shorttext" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("switch" == element.type) {
                    if ("on" == val) {
                        $(document.getElementById(elementKey)).bootstrapSwitch("state", true);
                    } else {
                        $(document.getElementById(elementKey)).bootstrapSwitch("state", false);
                    }
                } else if ("textarea" == element.type) {
                    $(document.getElementById(elementKey)).val(val);
                } else if ("timepicker" == element.type) {
                    document.getElementById(elementKey).value = val;
                } else if ("toggle" == element.type) {
                    if ("on" == val) {
                        $(document.getElementById(elementKey)).attr("checked", true).trigger("change");
                    }
                } else if ("selection_group" == element.type) {
                    self.setSelectionGroupValue(elementKey, val);
                }
            });

            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/configuration"))
                .then(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.page.variables = data;
                }).catch(({ data }) => {
                    self.page.is_variables_loaded = true;
                    self.page.is_variables_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                   /* WisiloHelper.initializePermissions(self.page.variables, true);
                   self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, "configuration");
                   */
                   self.processLoadQueue();
                });
        },
        downloadFile: function (__key) {
            axios.get(WisiloHelper.getAPIURL("profile/download_file/uploaded/" + __key))
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
        downloadDefaultFile: function (__key) {
            axios.get(WisiloHelper.getAPIURL("profile/download_file/default/" + __key))
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
        removeFile: function (__key) {
            document.getElementById(__key + "-file_process_type").value = "removed";
            document.getElementById(__key + "-download_btn").style.display = "none";
            document.getElementById(__key + "-file_remove_btn").style.display = "none";
        },
        loadData: function (callback) {
            var self = this;

            if (self.page.is_configlist_loading) {
                return;
            }

            var query = WisiloHelper.getURLQuery(self);
            axios.get(WisiloHelper.getAPIURL("profile/get_config_data/" + query))
                .then(({ data }) => {
                    self.page.is_configlist_loaded = true;
                    self.page.is_configlist_loading = false;
                    self.data = data;
                    self.list = data.data.list;
                    self.show_pagination = data.show_pagination;
                    self.setListByKey();
                }).catch(({ data }) => {
                    self.page.is_configlist_loaded = true;
                    self.page.is_configlist_loading = false;
                    self.$Progress.fail();
                    self.page.has_server_error = true;
                    self.processLoadQueue();
                }).finally(function() {
                    callback();
                    WisiloHelper.cleanCheckedBoxes('WisiloUserConfig');
                });
        },
        search_list: _.debounce(function (e) {
            var self = this;
            var search_input = e.target;
            
            WisiloHelper.activateSearchLoader(search_input);

            self.searchForm(search_input.value);

            self.search_text = search_input.value;
            self.current_page = 1;

            self.loadData(function(){
                WisiloHelper.deactivateSearchLoader(search_input);
                self.renderForm();
            });
        }, 1000),
        searchForm: function(search_text) {
            $("#modelFormcontainer").addClass("d-none");
            $(".searchable-container").addClass("d-none");

            var found = false;
            var searchText = search_text.toLowerCase();
            var items = $(".searchable-container");
            var itemLength = items.length;
            var strData = "";
            for (var i = 0; i < itemLength; i++) {
                strData = items[i].getAttribute("data-search-text");
                if (strData.search(new RegExp(searchText, "i")) != -1) {
                    $(items[i]).removeClass("d-none");
                    found = true;
                }
            }

            if (found) {
                $("#modelFormcontainer").removeClass("d-none");
            }
        },
        paginate: function (page = 1) {
            this.current_page = page;
            this.loadData(function(){});
        },
        sort: function (variable) {
            WisiloHelper.activateSortLoader(`button_sort_WisiloUserConfig_${variable}`);

            this.sort_variable = variable;
            this.sort_direction = ('asc' == this.sort_direction) ? 'desc' : 'asc';
            this.current_page = 1;

            var self = this;
            this.loadData(function() {
                WisiloHelper.deactivateSortLoader(`button_sort_WisiloUserConfig_${self.sort_variable}`, self.sort_direction)
            });
        },
        select_all_row: function(target) {
            WisiloHelper.doCheckAllCheckboxClick(target);
        },
        select_row: function(target) {
            WisiloHelper.doCheckboxClick(target);
        },
        submitConfigForm: function () {
            var self = this;

            $(".config-parameter-error > span").hide();
            $(".field-label.text-danger").removeClass("text-danger");

            let formData = new FormData();
            
            self.collectConfigData(formData);

            formData.append('config_data', JSON.stringify(this.formConfig.config_data));

            self.$Progress.start();

            axios.post( 
                    WisiloHelper.getAPIURL("profile/post_config_data"),
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
                            Vue.swal.fire({
                                position: 'top-end',
                                title: self.$t("Your changes have been saved!"),
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true,
                                onClose: () => {
                                    self.reloadPage();
                                }
                            });
                        } else {
                            self.renderFormErrors(self.page.post_error_msg);
                        }
                    }
                });
        },
        reloadPage: function() {
            var mainFolder = this.main_folder;
            var newMainFolder = "";
            var currentURL = window.location.href;
            var newURL = "";

            if (null !== document.getElementById("wisilo.generalsettings.mainfolder")) {
                newMainFolder = document.getElementById("wisilo.generalsettings.mainfolder").value;
                newURL = currentURL.replace(
                    ("/" + mainFolder + "/"),
                    ("/" + newMainFolder + "/")
                );
            } else {
                newURL = currentURL;
            }

            window.location.href = newURL;
        },
        renderFormErrors: function(errors) {
            var firstErrorContainer = null;
            for (const [__key, msg] of Object.entries(errors)) {
                let errorContainer = null;
                if (errorContainer = document.getElementById(__key + "-error")) {
                    $("span", errorContainer).html(msg).show();
                    document.getElementById(__key + "-label").className = "field-label text-danger";
                    if (null === firstErrorContainer) {
                        firstErrorContainer = errorContainer;
                    }
                }
            }

            if (null !== firstErrorContainer) {
                $([document.documentElement, document.body]).animate({
                    scrollTop: $(firstErrorContainer.parentNode).offset().top - 20
                }, 2000);
            }
        },
        collectConfigData: function(formData) {
            var self = this;
            var config_data = [];

            self.$el.querySelectorAll(".config-parameter").forEach(element => {
                let parameter_data = {};

                if ("radio" == element.getAttribute("data-type")) {
                    let id = element.getAttribute("data-key");
                    parameter_data["type"] = "radio";
                    parameter_data["key"] = id;
                    let selectorText = 'input[name="' + id + '"]:checked';
                    parameter_data["val"] = $(selectorText).val();

                } else if ("dropdown" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "dropdown";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = $(element).val();
                   
                    var attr = $(element).attr('multiple');
                    if (typeof attr !== 'undefined' && attr !== false) {
                        parameter_data["val"] = $(element).val().join(",");
                    }
                } else if ("file" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "file";
                    parameter_data["key"] = element.id;

                    let file_data = {};
                    file_data["file_name"] = document.getElementById(element.id + "-file_name").value;
                    file_data["file_value"] = document.getElementById(element.id + "-file_value").value;

                    parameter_data["val"] = element.id.replace(".", "_");

                    formData.append(parameter_data["val"], self.uploadedFiles[element.id]);
                    formData.append((parameter_data["key"] + "processtype"), document.getElementById(parameter_data["key"] + "-file_process_type").value);
                    
                } else if ("html_editor" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "html_editor";
                    parameter_data["key"] = element.id;
                    parameter_data["val"] = $(element).summernote('code');
                } else if ("iconpicker" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "iconpicker";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = element.value;
                } else if ("switch" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "switch";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = element.checked ? 'on' : 'off';
                } else if ("selection_group" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "selection_group";
                    parameter_data["key"] = element.getAttribute("data-key");
                    parameter_data["val"] = self.getSelectionGroupValue(parameter_data["key"])
                } else if ("checkbox" == element.getAttribute("data-type")) {
                    parameter_data["type"] = "other";
                    parameter_data["key"] = element.id;
                    parameter_data["val"] = element.checked ? 'on' : 'off';
                } else {
                    parameter_data["type"] = "other";
                    parameter_data["key"] = element.id;
                    parameter_data["val"] = element.value;
                }

                const key = parameter_data["key"];
                parameter_data["required"] = self.listByKey[key]["required"];
                parameter_data["title"] = self.listByKey[key]["title"];

                /* console.log(parameter_data) */

                config_data.push(parameter_data);
            });
            

            this.formConfig.config_data = config_data;
        },
        updateFile(e) {
            var self = this;
            let __key = e.id;
            let file = e.files[0];

            /* let extension = file.name.split('.').pop();

            let acceptableTypeCSV = e.getAttribute("accept");
            let acceptableTypes = acceptableTypeCSV.split(",");

            if (!acceptableTypes.includes(extension)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Acceptable file types: " + acceptableTypeCSV),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
            } */

            self.uploadedFiles[__key] = file;

            document.getElementById(__key + "-file_name").value = file.name;
            document.getElementById("spanFileName" + __key).innerHTML = file.name;

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
                document.getElementById(__key + "-file_value").value = reader.result;
                document.getElementById(__key + "-file_process_type").value = "uploaded";
                document.getElementById(__key + "-download_btn").style.display = "none";
                document.getElementById(__key + "-download_default_btn").style.display = "none";
                document.getElementById(__key + "-file_remove_btn").style.display = "none";
            }

            reader.readAsDataURL(file);
        },
        getSelectionGroupValue: function(selectionGroupKey) {
            var selectedValues = "";

            var parentkey = selectionGroupKey;
            var parentContainer = document.getElementById("groupContainer" + parentkey);
            var selectionItems = $(".selection-item", parentContainer);
            for (let index = 0; index < selectionItems.length; index++) {
                const selectionItem = selectionItems[index];

                if (selectionItem.checked) {
                    if ("" != selectedValues) {
                        selectedValues += ",";
                    }

                    selectedValues += selectionItem.id;
                }                
            }

            return selectedValues;
        },
        setSelectionGroupValue: function(elementKey, value) {
            var parentContainer = document.getElementById("groupContainer" + elementKey);
            var selectionItems = $(".selection-item", parentContainer);

            if ("" == value) {
                for (let index = 0; index < selectionItems.length; index++) {
                    const selectionItem = selectionItems[index];
                    document.getElementById(selectionItem.id).checked = false;               
                }

                return;
            }


            var values = value.split(",");

            for (let index = 0; index < selectionItems.length; index++) {
                const selectionItem = selectionItems[index];
                if (values.includes(selectionItem.id)) {
                    document.getElementById(selectionItem.id).checked = true;               
                } else {
                    document.getElementById(selectionItem.id).checked = false; 
                }
            }
        }
    },
    mounted() {
        var self = this;
        self.body_loader_active = true;
        self.main_folder = WisiloHelper.getMainFolder();
        self.page.is_ready = false;
        WisiloHelper.loadExternalFiles(self.page.external_files, self.processLoadQueue());
    }
}
</script>
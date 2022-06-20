<template>
    <div class="content-wrapper">
        <server-error v-if="page.has_server_error" ></server-error>
        <permission-error v-else-if="!page.authorization.status" :authorization="page.authorization"></permission-error>
        <div v-else>
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">{{ $t('Parameter Settings') }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><router-link :to="'/' + main_folder + '/home'">{{ $t('Home') }}</router-link></li>
                                <li class="breadcrumb-item"><a href="configuration">{{ $t('Configuration') }}</a></li>
                                <li class="breadcrumb-item active">{{ $t('Parameter Settings') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <section class="content" v-show="page.is_ready">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <form id="formConfiguration" @submit.prevent="submitForm" @keydown="form.onKeydown($event)">
                                    <div class="card-body">
                                        <div class="row mb-10 ">
                                            <div class="col-lg-12">
                                                <a id="buttonNewMenuItem" class="btn btn-primary btn-md btn-on-card float-right" href="javascript:void(0);">
                                                    <i class="fa fa-plus"></i> <span class="hidden-xxs">{{ $t('New') }}</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12" id="configEditorContainer">
                                                <ul id="ulConfigEditor" class="list-group">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer ">
                                        <button :disabled="form.busy"
                                            type="submit"
                                            class="btn btn-success btn-md btn-on-table float-right">
                                            {{ $t('Save') }}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="modal fade" id="modalMenuItem">
                <div class="modal-dialog modal-md">
                    <form id="formMenuItem" name="formMenuItem" method="post" class="form-horizontal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">{{ $t('Config Parameter') }}</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="system" value="">
                                <input type="hidden" id="editable" value="">
                                <input type="hidden" id="owner" value="">
                                <input type="hidden" id="is_owner" value="" v-model="is_owner">
                                <input type="hidden" id="currentKey" value="">
                                <input type="hidden" id="currentParent" value="">
                                <input type="hidden" id="item_data" v-model="item_data" @click="updateForm" value="">
                                <input type="hidden" id="exception_key" v-model="exception_key" @click="refreshListByKey" value="">
                                
                                <div class="row">
                                    <div class="form-group col-lg-12" v-show="(1 == is_owner)">
                                        <div class="">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="locked"
                                                    name="locked"
                                                    class="item-menu">
                                                <label for="locked" class="">
                                                    {{ $t('Locked') }}  
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <div class="">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="enabled"
                                                    name="enabled"
                                                    class="item-menu">
                                                <label for="enabled" class="">
                                                    {{ $t('Enabled') }}  
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <div class="">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="only_admins"
                                                    name="only_admins"
                                                    class="item-menu">
                                                <label for="only_admins" class="">
                                                    {{ $t('Show For Only Admins') }}  
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12" 
                                        v-show="(
                                            ('group' != parameter_type)
                                            && ('checkbox' != parameter_type)
                                            && ('switch' != parameter_type)
                                            && ('toggle' != parameter_type)
                                            && ('link_button' != parameter_type)
                                            && ('link_text' != parameter_type)
                                            && ('readonly_content' != parameter_type)
                                            && ('selection_item' != parameter_type)
                                        )">
                                        <div class="">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="required"
                                                    name="required"
                                                    class="item-menu">
                                                <label for="required" class="">
                                                    {{ $t('Required') }}  
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="__parent">{{ $t('Parent') }}</label>
                                        <select2-element class="select2-element item-menu"
                                            data-placeholder=""
                                            id="__parent"
                                            name="__parent"
                                            :options="parentlist"
                                            allowClear="true">
                                            <option></option>
                                        </select2-element>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="type">{{ $t('Type') }}</label>
                                        <select name="type" id="type" class="form-control item-menu" v-model="parameter_type">
                                            <option value="checkbox">{{ $t('Checkbox') }}</option>
                                            <option value="colorpicker">{{ $t('Color Picker') }}</option>
                                            <option value="datepicker">{{ $t('Date Picker') }}</option>
                                            <option value="datetimepicker">{{ $t('Date Time Picker') }}</option>
                                            <option value="dropdown">{{ $t('Dropdown') }}</option>
                                            <option value="file">{{ $t('File') }}</option>
                                            <option value="group">{{ $t('Group') }}</option>
                                            <option value="html_editor">{{ $t('HTML Editor') }}</option>
                                            <option value="iconpicker">{{ $t('Icon Picker') }}</option>
                                            <option value="integer">{{ $t('Integer') }}</option>
                                            <option value="link_button">{{ $t('Link (Button)') }}</option>
                                            <option value="link_text">{{ $t('Link (Text)') }}</option>
                                            <option value="number">{{ $t('Number') }}</option>
                                            <option value="password">{{ $t('Password') }}</option>
                                            <option value="radio">{{ $t('Radio') }}</option>
                                            <option value="readonly_content">{{ $t('Readonly Content') }}</option>
                                            <option value="selection_group">{{ $t('Selection Group') }}</option>
                                            <option value="selection_item">{{ $t('Selection Item') }}</option>
                                            <option value="shorttext">{{ $t('Shorttext') }}</option>
                                            <option value="switch">{{ $t('Switch') }}</option>
                                            <option value="textarea">{{ $t('Textarea') }}</option>
                                            <option value="timepicker">{{ $t('Time Picker') }}</option>
                                            <option value="toggle">{{ $t('Toggle') }}</option>
                                        </select>
                                        <span class="text-muted d-none" id="groupTypeWarning">
                                            {{ $t('This element type cannot be changed because it has children.') }}
                                        </span>
                                        <span class="text-muted d-none" id="parentSelectionGroupTypeWarning">
                                            {{ $t('This element can be "Selection Item" only, beacuse parent element type is "Selection Group"') }}
                                        </span>
                                    </div>

                                    <div class="form-group col-lg-12" v-show="('dropdown' == parameter_type)">
                                        <div class="">
                                            <div class="icheck-primary d-inline">
                                                <input type="checkbox"
                                                    id="multiple"
                                                    name="multiple"
                                                    class="item-menu"
                                                    v-model="parameter_multiple">
                                                <label for="multiple" class="">
                                                    {{ $t('Multiple') }}  
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="title">{{ $t('Title') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="title" id="title">
                                            <input type="hidden" class="form-control item-menu" name="__key" id="__key">
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12">
                                        <label for="basekey">{{ $t('Name') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="basekey" id="basekey">
                                        </div>
                                        <span class="text-muted">
                                            {{ $t('This value must contain only English letters or numbers.') }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="row mb-4" v-show="(('group' != parameter_type) && ('selection_group' != parameter_type))">
                                    <div class="col-md-12">
                                        <label style="margin:0;">{{ $t('Screen Sizes') }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="large_screen_size">{{ $t('Large') }}</label>
                                        <select id="large_screen_size" name="large_screen_size"  class="form-control">
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
                                        <label for="medium_screen_size">{{ $t('Medium') }}</label>
                                        <select id="medium_screen_size" name="medium_screen_size"  class="form-control">
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
                                        <label for="small_screen_size">{{ $t('Small') }}</label>
                                        <select id="small_screen_size" name="small_screen_size"  class="form-control">
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

                                <div class="row">
                                    <div class="form-group col-lg-12">
                                        <label for="description">{{ $t('Description') }}  </label>
                                        <textarea id="description"
                                            name="description"
                                            data-type="html_editor"
                                            class="textarea html-editor item-menu"
                                            rows="5"></textarea>
                                    </div>

                                    <div class="form-group col-lg-12" 
                                        v-show="(
                                            ('group' != parameter_type)
                                            && ('link_button' != parameter_type)
                                            && ('link_text' != parameter_type)
                                            && ('readonly_content' != parameter_type)
                                            && ('toggle' != parameter_type)
                                        )">
                                        <label for="hint">{{ $t('Hint') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="hint" id="hint">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" v-show="(('dropdown' == parameter_type) || ('radio' == parameter_type))">
                                    <div class="form-group col-lg-6">
                                        <label for="option_titles">{{ $t('Option Titles') }}  </label>
                                        <textarea rows="5"
                                            id="option_titles"
                                            name="option_titles"
                                            class="form-control item-menu"></textarea>
                                    </div>
                                    <div class="form-group col-lg-6">
                                        <label for="option_values">{{ $t('Option Values') }}  </label>
                                        <textarea rows="5"
                                            id="option_values"
                                            name="option_values"
                                            class="form-control item-menu"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-12" v-show="('toggle' == parameter_type)">
                                        <label for="toggle_elements">
                                            {{  $t('Toggle Elements') }}
                                        </label>
                                        <select2-element multiple="multiple"
                                            id="toggle_elements"
                                            name="toggle_elements"
                                            :options="toggle_elements_options"
                                            class="select2-element item-menu">
                                        </select2-element>
                                        <!-- <input type="hidden" id="toggle_elements_data" value="1" :selected-data="WisiloConfigForm.toggle_elements"> -->
                                    </div>

                                    <div class="form-group col-lg-12" v-show="(('link_button' == parameter_type) || ('link_text' == parameter_type))">
                                        <label for="url">{{ $t('URL') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="url" id="url">
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-12" v-show="('file' == parameter_type)">
                                        <label for="file_types">{{ $t('Acceptable File Types') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="file_types" id="file_types">
                                        </div>
                                        <span class="text-muted">
                                            {{ $t('This string is a comma-separated list of acceptable file extensions. Ex: .jpg, .pdf, .doc') }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row" v-show="('selection_group' == parameter_type)">
                                    <div class="form-group col-lg-6">
                                        <label for="min_selection">{{ $t('Min Selection Count') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control item-menu"
                                                name="min_selection" id="min_selection"
                                                min="0" step="1">
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-6">
                                        <label for="max_selection">{{ $t('Max Selection Count') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control item-menu"
                                                name="max_selection" id="max_selection"
                                                min="0" step="1">
                                        </div>
                                    </div>
                                </div>

                                <div class="row" v-show="(('integer' == parameter_type) || ('number' == parameter_type))">
                                    <div class="form-group col-lg-4">
                                        <label for="min">{{ $t('Min') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control item-menu" name="min" id="min">
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label for="max">{{ $t('Max') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control item-menu" name="max" id="max">
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label for="step">{{ $t('Step') }}</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control item-menu" name="step" id="step">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-12" 
                                        v-show="('readonly_content' == parameter_type)">
                                        <label for="content">{{ $t('Content') }}  </label>
                                        <textarea id="content"
                                            name="content"
                                            data-type="html_editor"
                                            class="textarea html-editor item-menu"
                                            rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-12" 
                                        v-show="(
                                            ('group' != parameter_type)
                                            && ('selection_item' != parameter_type)
                                            && ('link_button' != parameter_type)
                                            && ('link_text' != parameter_type)
                                            && ('readonly_content' != parameter_type)
                                        )">
                                        <label for="default_value" v-show="('' != parameter_type)">
                                            {{ $t('Default Value') }}
                                        </label>
                                        <div class="input-field" v-show="('file' == parameter_type)">
                                            <input type="file" id="default_value_file" name="default_value_file" 
                                                data-type="file" 
                                                class="form-input"
                                                style="display:none;">
                                            <button type="button" class="btn btn-primary" id="btn-file-trigger">
                                                {{ $t('Browse...') }}
                                            </button>
                                            
                                            <input type="hidden" id="default_value_file-file_name">
                                            <input type="hidden" id="default_value_file-file_value">
                                            <button type="button" class="text-btn" ref="file_download" @click="downloadFile"  data-current-key="">
                                                <span id="spanFileNamedefault_value_file"></span>
                                            </button>
                                        </div>
                                        <select id="default_value_checkbox" class="form-control"
                                            v-show="(
                                                ('toggle' == parameter_type)
                                                || ('checkbox' == parameter_type)
                                                || ('switch' == parameter_type)
                                            )">
                                            <option value="on">{{ $t('On') }}</option>
                                            <option value="off">{{ $t('Off') }}</option>
                                        </select>
                                        <div class="input-group"
                                            v-show="(
                                                ('dropdown' == parameter_type)
                                                || ('password' == parameter_type)
                                                || ('radio' == parameter_type)
                                                || ('shorttext' == parameter_type)
                                            )">
                                            <input type="text" class="form-control" id="default_value_text">
                                        </div>
                                        <div class="input-group" v-show="('selection_group' == parameter_type)">
                                            <select multiple
                                                id="default_value_multiple"
                                                class="form-control">
                                            </select>
                                        </div>
                                        <div class="input-group"
                                            v-show="('integer' == parameter_type) || ('number' == parameter_type)">
                                            <input type="number" class="form-control" id="default_value_number">
                                        </div>
                                        <span class="text-muted" v-show="('dropdown' == parameter_type) && (!parameter_multiple)">
                                            {{ $t('This string should be one of option values.') }}
                                        </span>
                                        <span class="text-muted" v-show="('dropdown' == parameter_type) && (parameter_multiple)">
                                            {{ $t('This string is a comma-separated list of option values.') }}
                                        </span>
                                        <span class="text-muted" v-show="('radio' == parameter_type)">
                                            {{ $t('This string should be one of option values.') }}
                                        </span>
                                        
                                        <div class="input-group" v-show="('datepicker' == parameter_type)">
                                            <input type="date" class="form-control" id="default_value_datepicker">
                                        </div>
                                        <div class="input-group" v-show="('datetimepicker' == parameter_type)">
                                            <input type="datetime-local" class="form-control" id="default_value_datetimepicker">
                                        </div>
                                        <div class="input-group" v-show="('timepicker' == parameter_type)">
                                            <input type="time" class="form-control" id="default_value_timepicker">
                                        </div>
                                        <div v-show="('textarea' == parameter_type)">
                                            <textarea
                                                id="default_value_textarea"
                                                class="form-control"
                                                rows="5"></textarea>
                                        </div>
                                        <div v-show="('html_editor' == parameter_type)">
                                            <textarea id="default_value_html_editor"
                                                name="default_value_html_editor"
                                                data-type="html_editor"
                                                class="textarea html-editor"
                                                rows="5"></textarea>
                                        </div>
                                        <div class="input-group" v-show="('colorpicker' == parameter_type)">
                                            <input type="text"
                                                class="form-control color-picker"
                                                id="default_value_colorpicker">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="default_value_colorpicker_append" style="padding-left:100px;"></span>
                                            </div>
                                        </div>
                                        <div class="input-group" v-show="('iconpicker' == parameter_type)">
                                            <button type="button" id="default_value_iconpicker_picker" class="btn btn-outline-secondary icon-picker">
                                            </button>
                                        </div>
                                        <input type="hidden" id="default_value_iconpicker" class="item-widget">

                                        <input type="hidden" class="form-control item-menu" 
                                            name="default_value" id="default_value">
                                    </div>

                                    <div class="form-group col-lg-12" v-show="'selection_item' == parameter_type">
                                        <label for="value">{{ $t('Value') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="value" id="value">
                                        </div>
                                    </div>
                                
                                </div>

                                <div class="row"
                                    v-show="(
                                        ('html_editor' == parameter_type)
                                        || ('integer' == parameter_type)
                                        || ('number' == parameter_type)
                                        || ('password' == parameter_type)
                                        || ('readonly_content' == parameter_type)
                                        || ('shorttext' == parameter_type)
                                        || ('textarea' == parameter_type)
                                    )">
                                    <div class="form-group col-lg-12">
                                        <label for="expression">{{ $t('Expression') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="expression" id="expression">
                                        </div>
                                        <div class="item-error-container" id="expression-error">
                                            <span class="error invalid-feedback"></span>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-12">
                                        <label for="message">{{ $t('Message') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control item-menu" name="message" id="message">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modalfooter justify-content-between ">
                                <div class="row">
                                    <div class="col col-lg-12">
                                        <button type="button"
                                            id="buttonAddMenuItem"
                                            name="buttonAddMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button"
                                            id="buttonUpdateMenuItem"
                                            name="buttonUpdateMenuItem"
                                            class="btn btn-success float-right"
                                            style="display:none;">
                                            {{ $t('Save') }}
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary float-right" data-dismiss="modal" style="margin-right:10px;">{{ $t('Cancel') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="modalMenuItemDelete">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">{{ $t('Menu Item Delete') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <p id="deleteWarning">{{ $t('The selected item will be deleted. Do you confirm?') }}</p>
                                <p id="groupDeleteWarning" class="d-none">{{ $t('The selected item will be deleted along with its children. Do you confirm?') }}</p>
                            </div>
                        </div>
                        <div class="modalfooter justify-content-between ">
                            <div class="row">
                                <div class="col col-lg-12">
                                    <button type="button" class="btn btn-outline-secondary float-left" data-dismiss="modal">{{ $t('Cancel') }}</button>
                                    <button type="button"
                                        id="buttonDeleteMenuItem"
                                        name="buttonDeleteMenuItem"
                                        class="btn btn-danger float-right">
                                        {{ $t('Continue') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <span class="d-none" id="copyBtnTitle">{{ $t('Copy Key') }}</span>
        <span class="d-none" id="copyBtnDefaultText">{{ $t('Copy') }}</span>
        <span class="d-none" id="copyBtnCopiedText">{{ $t('Copied!') }}</span>
        <body-loader :body_loader_active="body_loader_active" class="content-wrapper bodyLoader"></body-loader>
    </div>
</template>

<script>
export default {
    data() {
        return {
            main_folder: '',
            pagename: '',
            form: new Form({
                'config_json': '',
                'files': [],
            }),
            listByKey: [],
            item_data: [],
            exception_key: '',
            parameter_type: '',
            parameter_multiple: '',
            parentlist: [],
            toggle_elements_options: [],
            is_owner: 0,
            uploadedFiles: {},  
            validationForm: new Form({
                'expression': '',
            }),
            page: {
                is_ready: false,
                has_server_error: false,
                variables: [],
                authorization: {
                    status: true,
                    type: "",
                    msg: ""
                },
                is_variables_loading: false,
                is_variables_loaded: false,
                is_listbykey_loading: false,
                is_listbykey_loaded: false,
                is_parentlist_loading: false,
                is_parentlist_loaded: false,
                is_toggle_elements_options_loading: false,
                is_toggle_elements_options_loaded: false,
                has_post_error: false,
                post_error_msg: '',
                is_data_loading: false,
                is_data_loaded: false,
                is_post_success: false,
                external_files: [
                    ("/js/wisilo/config_editor.js"),
                    ("/js/wisilo/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"),
                    ("/js/wisilo/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"),
                    ("/js/wisilo/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"),
                    ("/js/wisilo/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css"),
                    ("/js/wisilo/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"),
                    ("/js/wisilo/select2/dist/js/select2.min.js"),
                ],
                editor: null
            },
            body_loader_active: false,
        };
    },
    methods: {
        updateForm: function() {
            document.getElementById("type").disabled = false;
            $("#groupTypeWarning").addClass("d-none");

            var current_data = $("#item_data").data("current_data");
           
            document.getElementById("system").value = 0;
            document.getElementById("editable").value = current_data["editable"];
            document.getElementById("locked").checked = current_data["locked"];
            document.getElementById("owner").value = current_data["owner"];
            this.is_owner = current_data["is_owner"];
            document.getElementById("enabled").checked = current_data["enabled"];
            document.getElementById("only_admins").checked = current_data["only_admins"];
            document.getElementById("required").checked = current_data["required"];
            this.multiple = current_data["multiple"];
            $("#__parent").val(current_data["__parent"]).trigger('change');
            document.getElementById("basekey").value = current_data["basekey"];
            document.getElementById("__key").value = current_data["__key"];
            this.parameter_type = current_data["type"];
            document.getElementById("title").value = current_data["title"];
            $("#large_screen_size").val(current_data["large_screen_size"]).trigger('change');
            $("#medium_screen_size").val(current_data["medium_screen_size"]).trigger('change');
            $("#small_screen_size").val(current_data["small_screen_size"]).trigger('change');
            document.getElementById("option_titles").value = current_data["option_titles"];
            document.getElementById("option_values").value = current_data["option_values"];
            $("#toggle_elements").val(current_data["toggle_elements"]).trigger('change');
            document.getElementById("url").value = current_data["url"];
            document.getElementById("min").value = current_data["min"];
            document.getElementById("max").value = current_data["max"];
            document.getElementById("step").value = current_data["step"];
            document.getElementById("file_types").value = current_data["file_types"];
            $("#content").summernote("code", current_data["content"]);
            document.getElementById("default_value").value = current_data["default_value"];
            document.getElementById("value").value = current_data["value"];
            document.getElementById("hint").value = current_data["hint"];
            $("#description").summernote("code", current_data["description"]);
            document.getElementById("min_selection").value = current_data["min_selection"];
            document.getElementById("max_selection").value = current_data["max_selection"];
            document.getElementById("expression").value = current_data["expression"];
            document.getElementById("message").value = current_data["message"];

            /* if ("toggle" == current_data.type) {
                this.WisiloConfigForm.toggle_elements = current_data.toggle_elements.split(",");
            } */

            var $li = $(document.getElementById(current_data["__key"]));
            var $ul = $li.children('ul');
            if ($ul.length > 0) {
                $("#groupTypeWarning").removeClass("d-none");
                document.getElementById("type").disabled = true;
            }

            var __key = current_data["__key"];
            var type = current_data["type"];
            var val = current_data["default_value"];

            if (
                ("checkbox" == type)
                || ("switch" == type)
                || ("toggle" == type)
                ) {
                document.getElementById("default_value_checkbox").value = val;
            } else if ("file" == type){
                document.getElementById("spanFileNamedefault_value_file").innerHTML = val;
                document.getElementById("spanFileNamedefault_value_file").parentNode.setAttribute("data-current-key", current_data["__key"])
            } else if (
                ("dropdown" == type)
                || ("password" == type)
                || ("radio" == type)
                || ("shorttext" == type)
                ) {
                document.getElementById("default_value_text").value = val;
            } else if (("integer" == type) || ("number" == type)){
                document.getElementById("default_value_number").value = val;
            } else if ("datepicker" == type){
                document.getElementById("default_value_datepicker").value = val;
            } else if ("datetimepicker" == type){
                document.getElementById("default_value_datetimepicker").value = val;
            } else if ("timepicker" == type){
                document.getElementById("default_value_timepicker").value = val;
            } else if ("html_editor" == type) {
                $("#default_value_html_editor").summernote("code", val);
            } else if ("textarea" == type) {
                $(document.getElementById("default_value_textarea")).val(val);
            } else if ("colorpicker" == type) {
                var colorPicker = document.getElementById("default_value_colorpicker");
                $(colorPicker).val(val);
                $(colorPicker).trigger('change');
            } else if ("iconpicker" == type) {
                if ("" == val || undefined === val) {
                    $(document.getElementById("default_value_iconpicker_picker")).iconpicker('setIcon', 'empty');
                } else{
                    $(document.getElementById("default_value_iconpicker_picker")).iconpicker('setIcon', val);
                }
            } else if ("selection_group" == type) {
                this.setDefaultValueSelection(__key, val);
            }
        },
        setDefaultValueSelection: function(__key, value) {
            $("#default_value_multiple").html("");

            var optionTemplateHTML = '<option value="__val__">__title__</option>';
            var optionHTML = "";
            var optionsHTML = "";

            var arrLi = $('ul:first>li', document.getElementById(__key));
            for (let index = 0; index < arrLi.length; index++) {
                const li = arrLi[index];

                optionHTML = optionTemplateHTML;
                optionHTML = optionHTML
                        .replace(/__val__/g, $(li).data("__key"))
                        .replace(/__title__/g, $(li).data("title"));

                optionsHTML = optionsHTML + optionHTML;
            }

            if ("" != optionsHTML) {
                $("#default_value_multiple").html(optionsHTML);
                $("#default_value_multiple").select2();
                $("#default_value_multiple").val(value.split(",")).trigger('change');
            }
        },
        processLoadQueue: function () {
            if (this.page.has_server_error) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.authorization.status) {
                this.$Progress.finish();
                this.page.is_ready = true;
                return;
            }

            if (!this.page.is_variables_loaded 
                && !this.page.is_data_loaded 
                && !this.page.is_listbykey_loaded) {
                this.$Progress.start();
            }
            
            if (!this.page.is_variables_loaded) {
                this.loadPageVariables();
            } else {
                if (!this.page.is_listbykey_loaded) {
                    this.load_listbykey();
                }

                if (this.page.is_data_loaded) {
                    this.$Progress.finish();
                    this.page.is_ready = true;

                    this.$nextTick(function () {
                        this.renderSelectElements();
                        this.updateMenuEditor();
                    });
                } else {
                    this.loadData();
                }
            }
        },
        loadPageVariables: function () {
            var self = this;

            if (self.page.is_variables_loading) {
                return;
            }

            self.page.is_variables_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisilo/get_page_variables/" + self.pagename))
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
                    WisiloHelper.initializePermissions(self.page.variables, false);
                    self.page.authorization = WisiloHelper.isUserAuthorized(self.page.variables, self.pagename);
                    self.processLoadQueue();
                });
        },
        loadData: function () {
            if (this.page.is_data_loading) {
                return;
            }

            this.page.is_data_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisiloconfig/get_json"))
                .then(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.form.config_json = data;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_data_loaded = true;
                    this.page.is_data_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        load_listbykey: function () {
            if (this.page.is_listbykey_loading) {
                return;
            }

            this.page.is_listbykey_loading = true;

            axios.get(WisiloHelper.getAPIURL("wisiloconfig/get_recordlist"))
                .then(({ data }) => {
                    this.page.is_listbykey_loaded = true;
                    this.page.is_listbykey_loading = false;
                    this.listByKey = data.list;
                    this.processLoadQueue();
                }).catch(({ data }) => {
                    this.page.is_listbykey_loaded = true;
                    this.page.is_listbykey_loading = false;
                    this.$Progress.fail();
                    this.page.has_server_error = true;
                    this.processLoadQueue();
                });
        },
        submitForm: function () {
            var self = this;
            /* self.$Progress.start();
            var str = self.page.editor.getString();
            self.form.config_json = str;
            self.form.files = self.uploadedFiles;

            self.form.post(WisiloHelper.getAPIURL("wisiloconfig/post_json")) */



            let formData = new FormData();
            
            formData.append('config_json', self.page.editor.getString());
            
            for (const [__key, file] of Object.entries(self.uploadedFiles)) {
                __key = __key.replace(".","_");
                formData.append(__key, file);
            }


            self.$Progress.start();

            axios.post( 
                    WisiloHelper.getAPIURL("wisiloconfig/post_json"),
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(({ data }) => {
                    self.$Progress.finish();
                    self.page.has_post_error = data.has_error;
                    self.page.post_error_msg = data.error_msg;
                    self.page.has_server_error = false;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    let errors = (self.form.errors.errors);
                    if (undefined !== errors.error) {
                        self.page.has_server_error = true;
                    } else {
                        self.page.has_post_error = true;
                        self.page.post_error_msg = self.$t("Your changes could not be saved. Please check your details and try again.");
                    }
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
                                /* onClose: () => {
                                    window.location.reload()
                                } */
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
        resetMenuItemForm: function () {
            document.getElementById("system").value = 0;
            document.getElementById("owner").value = 0;
            this.is_owner = 1;
            document.getElementById("locked").checked = false;
            document.getElementById("editable").value = 1;

            document.getElementById("enabled").checked = true;
            document.getElementById("only_admins").checked = false;
            document.getElementById("required").checked = false;
            document.getElementById("multiple").checked = false;
            $("#__parent").val("").trigger('change');
            document.getElementById("basekey").value = "";
            document.getElementById("__key").value = "";
            this.parameter_type = "group";
            document.getElementById("type").disabled = true;
            document.getElementById("title").value = "";
            $("#large_screen_size").val("12").trigger('change');
            $("#medium_screen_size").val("12").trigger('change');
            $("#small_screen_size").val("12").trigger('change');
            document.getElementById("option_titles").value = "";
            document.getElementById("option_values").value = "";
            $("#toggle_elements").val("").trigger('change');
            document.getElementById("url").value = "";
            document.getElementById("min").value = 0;
            document.getElementById("max").value = 0;
            document.getElementById("step").value = 0;
            document.getElementById("file_types").value = "";
            $("#content").summernote("code", "");
            document.getElementById("default_value").value = "";
            document.getElementById("value").value = "";
            document.getElementById("hint").value = "";
            $("#description").summernote("code", "");
            $("#default_value_html_editor").summernote("code", "");
            document.getElementById("min_selection").value = 0;
            document.getElementById("max_selection").value = 0;
            document.getElementById("expression").value = "";
            document.getElementById("message").value = "";

            $("#groupTypeWarning").addClass("d-none");
            document.getElementById("type").disabled = false;

            $("#item_data").removeData("current_data");
        },
        updateMenuEditor: function () {
            var self = this;
            if (!self.page.is_ready) {
                return;
            }

            $("#buttonNewMenuItem").off("click").on("click", function () {
                self.resetMenuItemForm();
                
                $("#buttonUpdateMenuItem").hide();
                $("#buttonAddMenuItem").show();
                $("#modalMenuItem").modal();
            });

            /* var rawMenuJSON = decodeURIComponent(this.form.config_json);
            var menuJSON = JSON.parse(rawMenuJSON); */
            
            var sortableListOptions = {
                placeholderCss: {"background-color": "#cccccc"}
            };

            self.page.editor = new MenuEditor(
                    "ulConfigEditor",
                    {
                        listOptions: sortableListOptions
                    }
            );
            self.page.editor.setForm($("#formMenuItem"));
            self.page.editor.setUpdateButton($("#buttonUpdateMenuItem"));
            self.page.editor.setData(self.form.config_json);

            $("#buttonUpdateMenuItem").off("click").on("click", function(){
                if (self.isValid()) {
                    self.parameterFormIsValid(function(){
                        self.page.editor.update();
                        $("#modalMenuItem").modal('hide');
                    });
                }
            });

            $("#buttonAddMenuItem").off("click").on("click", function(){
                document.getElementById("currentKey").value = "";
                document.getElementById("currentParent").value = "";

                if (self.isValid()) {
                    self.parameterFormIsValid(function(){
                        self.page.editor.add();
                        $("#modalMenuItem").modal('hide');
                    });
                }
            });

            $( "#ulConfigEditor" ).sortable();
            $( "#ulConfigEditor" ).disableSelection();

            var summernoteOptions = {
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
            };

            $("#description").summernote(summernoteOptions);
            $("#content").summernote(summernoteOptions);
            $("#default_value_html_editor").summernote(summernoteOptions);

            $(".color-picker").colorpicker();

            $(".color-picker").on("colorpickerChange", function(event) {
                var colorHex = event.color.toString();
                var elAppend = document.getElementById("default_value_colorpicker_append");
                elAppend.style.background = colorHex;
                elAppend.style.borderColor = colorHex;
            });

            var iconPickerOptions = {
                searchText: "...",
                labelHeader: "{0} / {1}",
                cols: 4, rows: 4, 
                footer: false, 
                iconset: "fontawesome5"
            };

            var iconPicker = $(".icon-picker").iconpicker(iconPickerOptions);
            iconPicker.on("change", function (e) {
                document.getElementById("default_value_iconpicker").value = e.icon;
            });

            $("#btn-file-trigger").on('click', function(e){
                document.getElementById("default_value_file").click();
            });

            $("#default_value_file").on('change', function(e){
                self.updateFile(this); 
            });

            $(".copyKey").off('click').on('click', function(e){
                self.copyKeyToClipboard(this);
            });

            setTimeout(function() {
                self.body_loader_active = false;
            }, 500);
        },
        parameterFormIsValid: function(callback) {
            $(".item-error-container > span").hide();

            var expression = document.getElementById("expression").value;
            if ("" == expression) {
                callback();
                return true;
            }

            var self = this;
            var has_error = false;
            var errors = [];
            
            self.validationForm.expression = expression;
            
            self.$Progress.start();
            
            self.validationForm.post(WisiloHelper.getAPIURL("wisiloconfig/validate_item"))
                .then(({ data }) => {
                    self.$Progress.finish();
                    has_error = data.has_error;
                    errors = data.errors;
                }).catch(({ data }) => {
                    self.$Progress.fail();
                    return false;
                }).finally(function() {
                    if (!self.page.has_server_error) {
                        if (!has_error) {
                            callback();
                        } else {
                            self.showValidationFormErrors(errors);
                        }
                    }
                });
        },
        showValidationFormErrors: function(errors) {
            var firstErrorContainer = null;
            for (const [field, msg] of Object.entries(errors)) {
                let errorContainer = null;
                if (errorContainer = document.getElementById(field + "-error")) {
                    $("span", errorContainer).html(msg).show();
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
        copyKeyToClipboard: function(btn) {
            btn.innerHTML = btn.getAttribute("copied-text");
            var __key = btn.getAttribute("data-key");
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(__key).select();
            document.execCommand("copy");
            $temp.remove();

            setTimeout(function () {
                btn.innerHTML = btn.getAttribute("default-text");
            }, 1000);
        },
        updateFile(e) {
            var self = this;
            var basekey = document.getElementById("basekey").value;
            var __key = basekey;
            var __parent = document.getElementById("__parent").value;

            if ("" != __parent) {
                __key = __parent + "." + __key;
            }

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

            document.getElementById("default_value_file-file_name").value = file.name;
            document.getElementById("spanFileNamedefault_value_file").innerHTML = file.name;

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
                document.getElementById("default_value_file-file_value").value = reader.result;
            }

            reader.readAsDataURL(file);
        },
        downloadFile: function () {
            const btn = this.$refs.file_download;
            var __key = btn.getAttribute("data-current-key");

            axios.get(WisiloHelper.getAPIURL("wisiloconfig/download_file/default/" + __key))
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
        isKeyValid: function(__key) {
            var valid = true;

            const alphabet = [
                "a","b","c","d","e","f",
                "g","h","i","j","k","l",
                "m","n","o","p","q","r",
                "s","t","u","v","w","x","y","z",
                "0","1","2","3","4","5","6","7","8","9"];

            for (let i = 0; i < __key.length; i++) {
                let char = __key.charAt(i);
                
                if (!alphabet.includes(char)) {
                    valid = false;
                    break;
                }                
            }
            
            return valid;
        },
        isValid: function() {
            var self = this;
            var basekey = document.getElementById("basekey").value.toLowerCase();

            if ("" == basekey) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Parameter name is required."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            if (!self.isKeyValid(basekey)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Parameter name must contain only English letters. You cannot be use special characters and numbers."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            document.getElementById("basekey").value = basekey;

            var key = basekey;
            var currentKey = document.getElementById("currentKey").value;
            var __parent = document.getElementById("__parent").value;
            var type = document.getElementById("type").value;

            if ("" != __parent) {
                key = __parent + "." + key;
            }

            if ((key != currentKey) && self.listByKey.hasOwnProperty(key)) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("This element is in use. Please try different name."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            if ("" == type) {
                Vue.swal.fire({
                    position: 'top-end',
                    title: self.$t("Please select a type."),
                    icon: 'error',
                    showConfirmButton: false,
                    timer: 10000,
                    timerProgressBar: true
                });
                return false;
            }

            document.getElementById("__key").value = key;
            return true;
        },
        refreshListByKey: function() {
            var exceptionKey = this.exception_key;

            this.listByKey = {};
            var listByKey = {};
            var elements = $(".list-group-item");

            for (let index = 0; index < elements.length; index++) {
                const data = $(elements[index]).data();

                if (exceptionKey != data.__key) {
                    const element = {};
                    element.enabled = data.enabled;
                    element.type = data.type;
                    element.title = data.title;
                    listByKey[data.__key] = element;
                }
            }

            this.listByKey = listByKey;
            this.renderSelectElements();
        },
        renderSelectElements: function() {
            if (0 == this.listByKey.length) {
                return;
            }

            // parent selection
            var parentList = [];
            var option = {};
            var type = "";

            for (var __key in this.listByKey) {
                type = this.listByKey[__key].type;
                if (("group" == type) ||("selection_group" == type)) {
                    option = {};
                    option["id"] = __key;
                    option["text"] = this.getOptionTitle(__key);
                    parentList.push(option);
                }
            }

            this.parentlist = parentList;

            var self = this;
            
            $("#__parent").off("change").on("change", function (e) {
                self.parentChanged(this.value);
            });

            // toggle selection
            var toggleList = [];

            for (var __key in this.listByKey) {
                const option = {};
                option["id"] = __key;
                option["text"] = this.getOptionTitle(__key);
                toggleList.push(option);
            }

            this.toggle_elements_options = toggleList;

            if ($("#toggle_elements").find('option').get(0)) {
                $("#toggle_elements").find('option').get(0).remove();
            }
        },
        parentChanged: function(selectedParentKey) {
            $("#parentSelectionGroupTypeWarning").addClass("d-none");
            document.getElementById("type").disabled = false;

            if ("" == selectedParentKey) {
                this.parameter_type = "group";
                document.getElementById("type").disabled = true;
                return;
            }
            
            if ("selection_group" != this.listByKey[selectedParentKey].type) {
                return;
            }

            $("#parentSelectionGroupTypeWarning").removeClass("d-none");
            this.parameter_type = "selection_item";
            document.getElementById("type").disabled = true;
        },
        getOptionTitle: function(key) {
            var self = this;
            var option_title = '';
            var parts = key.split('.');
            var parent_key = '';

            parts.forEach(part => {
                if ('' != parent_key) {
                    parent_key += '.';
                }

                parent_key += part;

                let title = self.getElementTitle(parent_key);

                if ('' != option_title) {
                    option_title += ' / ';
                }

                option_title += title;
            });

            return option_title;
        },
        getElementTitle: function(parent_key) {
            var parent = this.listByKey[parent_key];
            return parent["title"];
        },
        convertTitleToConfigName: function(strName) {
            var strReturnValue = strName;

            strReturnValue = strReturnValue.replace(/Ç/g, 'c');
            strReturnValue = strReturnValue.replace(/ç/g, 'c');
            strReturnValue = strReturnValue.replace(/Ý/g, 'i');
            strReturnValue = strReturnValue.replace(/ý/g, 'i');
            strReturnValue = strReturnValue.replace(/I/g, 'i');
            strReturnValue = strReturnValue.replace(/İ/g, 'i');
            strReturnValue = strReturnValue.replace(/ı/g, 'i');
            strReturnValue = strReturnValue.replace(/Ð/g, 'g');
            strReturnValue = strReturnValue.replace(/ð/g, 'g');
            strReturnValue = strReturnValue.replace(/Ğ/g, 'g');
            strReturnValue = strReturnValue.replace(/ğ/g, 'g');
            strReturnValue = strReturnValue.replace(/Ö/g, 'o');
            strReturnValue = strReturnValue.replace(/ö/g, 'o');
            strReturnValue = strReturnValue.replace(/Þ/g, 's');
            strReturnValue = strReturnValue.replace(/þ/g, 's');
            strReturnValue = strReturnValue.replace(/ş/g, 's');
            strReturnValue = strReturnValue.replace(/Ş/g, 's');
            strReturnValue = strReturnValue.replace(/Ü/g, 'u');
            strReturnValue = strReturnValue.replace(/ü/g, 'u');
            strReturnValue = strReturnValue.replace(/"/g, '');
            strReturnValue = strReturnValue.replace(/\'/g, '');
            strReturnValue = strReturnValue.replace(/\?/g, '');
            strReturnValue = strReturnValue.replace(/:/g, '');
            strReturnValue = strReturnValue.replace(/\//g, '');
            strReturnValue = strReturnValue.replace(/!/g, '');
            strReturnValue = strReturnValue.replace(/,/g, '');
            strReturnValue = strReturnValue.replace(/\(/g, '');
            strReturnValue = strReturnValue.replace(/\)/g, '');
            strReturnValue = strReturnValue.replace(/-/g, '');
            strReturnValue = strReturnValue.replace(/\./g, '');
            strReturnValue = strReturnValue.replace(/\+/g, '');
            strReturnValue = strReturnValue.replace(/\*/g, '');
            strReturnValue = strReturnValue.replace(/#/g, '');
            strReturnValue = strReturnValue.replace(/ /g, '');
            strReturnValue = strReturnValue.replace(/__/g, '');
            
            return strReturnValue.toLowerCase();
        }
    },
    mounted() {
        this.body_loader_active = true;
        this.main_folder = WisiloHelper.getMainFolder();
        this.pagename = WisiloHelper.getPagename();
        this.page.is_ready = false;
        WisiloHelper.loadExternalFiles(
                this.page.external_files,
                this.processLoadQueue());
    }
}
</script>
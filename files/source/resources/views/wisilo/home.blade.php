@include('wisilo.head')
<body class="hold-transition sidebar-mini layout-fixed control-sidebar-slide-open {{ $customization['body'] }}" data-main-folder="{{ $main_folder }}" template-created="0">
    @include('wisilo.header')
    <div id="mainVueApplication">
        <router-view></router-view>
        <vue-progress-bar></vue-progress-bar>
        <impersonation-dialog></impersonation-dialog>
        <custom-variable-list></custom-variable-list>
        <data-source-fields></data-source-fields>
        <data-source-query-fields></data-source-query-fields>
        <data-source-order-dialog></data-source-order-dialog>
        <data-source-condition></data-source-condition>
        <variable-mapping-edit></variable-mapping-edit>
        <widget-condition-dialog></widget-condition-dialog>
        <insert-custom-variable-dialog></insert-custom-variable-dialog>
    </div>
    
    <div>
        <input type="hidden" id="is_current_user_admin" value="{{ $user['admin'] }}">
        <div id="widgetModalTitleDefault" class="d-none">{{ __('Widget Item') }}</div>
        <div id="widgetModalTitleinfobox" class="d-none">{{ __('Infobox Widget') }}</div>
        <div id="widgetModalTitlerecordlist" class="d-none">{{ __('Record List Widget') }}</div>
        <div id="widgetModalTitlerecordgraph" class="d-none">{{ __('Record Graph') }}</div>

        <div id="widgetLabelDefault" class="d-none">{{ __('empty') }}</div>
        <div id="widgetLabelinfobox" class="d-none">{{ __('infobox') }}</div>
        <div id="widgetLabelrecordlist" class="d-none">{{ __('record list') }}</div>
        <div id="widgetLabelrecordgraph" class="d-none">{{ __('record graph') }}</div>

        <div id="recordgraphLabel" class="d-none">{{ __('Record Count') }}</div>
        <script type="text/html" id="modelRecordListSearchTemplate">
            <div id="tbody__MODEL__RecordList-searchForm"
                class="input-group input-group-sm divSearchBar htmldb-section float-right"
                data-htmldb-table="Session__MODEL__HTMLDB"
                style="margin-bottom:1rem;">
                <input type="search"
                    id="searchText" name="searchText"
                    class="form-control float-right inputSearchBar htmldb-input-save"
                    placeholder="{{ __('Search') }}"
                    data-htmldb-table="Session__MODEL__HTMLDB"
                    data-htmldb-input-field="searchText"
                    data-htmldb-refresh-table="__MODEL__HTMLDB"
                    data-htmldb-table-defaults='{"page":0}'
                    data-htmldb-value="@{{searchText}}"
                    data-htmldb-save-delay="1000">

                <div class="input-group-append labelSearchBar">
                    <button type="button" class="btn btn-default ">
                        <img class="imgLoader" src="/img/wisilo/loader.svg" width="14" height="14"/>
                        <i class="fas fa-search text-primary"></i>
                    </button>
                </div>
            </div>
        </script>
        <script type="text/html" id="tdTemplate">
            <td>__VALUE__</td>
        </script>
        <script type="text/html" id="tdLastRecordTemplate">
            <td>__VALUE__</td>
        </script>
        <script type="text/html" id="ulFileListTemplate">
            <span class="grippy"></span>
            <a href="" target="_blank" class="aFileListItemFileURL mediaImageContainer aMediaType__MEDIA_TYPE__">
                <img width="64" src="" alt="" class="imgFileListItemFileURL">
            </a>
            <a href="" target="_blank" class="aFileListItemFileURL mediaFilenameContainer text-primary aMediaType__MEDIA_TYPE__">
                <span class="title" class="spanFileListItemFileName">__FILE_NAME__</span>
            </a>
            <a href="JavaScript:void(0);" class="aDeleteFileListItem secondary-content text-primary float-right">
                <i class="fa fa-times"></i>
            </a>
        </script>
        <script type="text/html" id="recordlistColumnTemplate">
            <tr id="__column____VALUE__">
                <td>__COLUMN__</td>
                <td>__VALUE__</td>
                <td class="text-center">
                    <a data-column="__COLUMN__" data-value="__VALUE__" data-index="__INDEX__" class="table-icon-primary buttonEditColumn" href="javascript:void(0);">
                        <i class="fas fa-pen"></i>
                    </a>
                </td>
                <td class="text-center">
                    <a data-column="__COLUMN__" data-value="__VALUE__" data-index="__INDEX__" class="table-icon-danger buttonRemoveColumn" href="javascript:void(0);">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
            </tr>
        </script>
    </div>

    @if (config('app.env') == 'local')
    <script src="{{asset('js/wisilo/app.js')}}"></script>
    @else
    <script src="{{asset(mix('js/wisilo/app.js'), true)}}"></script>
    @endif
    <script src="/js/wisilo/custom.js"></script>

    @if('' != $google_maps_api_key)
    <script type="text/javascript" src="//maps.google.com/maps/api/js?key={{ $google_maps_api_key }}&libraries=places"></script>
    @endif
</body>
</html>
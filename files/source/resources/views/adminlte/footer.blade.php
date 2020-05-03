<div class="modal fade" id="galleryModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body text-center">
                <img id="popup-photo" src="">
            </div>
        </div>
    </div>
</div>

<div class="modal fade htmldb-dialog-edit" id="modal-Error">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">{{ __('Error') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="pFormErrorText"></p>
            </div>
        </div>
    </div>
</div>

<div class="divDialogContent divLoader" id="divLoader" >
    <img class="center-block" src="/assets/adminlte/img/loader.svg" width="70" height="70" />
    <div id="divLoaderText" class="" data-default-text="{{ __('Loading...') }}"></div>
</div>
    
<div id="__pagepermissionHTMLDB"
    class="htmldb-table"
    data-htmldb-priority="9999"
    data-htmldb-read-url="/{{ config('adminlte.main_folder') }}/htmldb/__pagepermission/get/{{ $controllerName }}?_token={{ csrf_token() }}"
    data-htmldb-read-only="1"
    data-htmldb-loader="divLoader">
</div>
@verbatim
<script type="text/html" 
    id="editPermissionTemplate"
    class="htmldb-template"
    data-htmldb-table="__pagepermissionHTMLDB"
    data-htmldb-template-target="editPermission">
    <input type="hidden" id="{{input_id}}" value="{{edit_permission}}">
</script>
@endverbatim
<div id="editPermission" class="d-none"></div>
<footer class="main-footer">
    <strong>Copyright &copy; 2014-2019 <a href="http://adminlte.io">AdminLTE.io</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 3.0.0
    </div>
</footer>

<!-- jQuery -->
<script src="/assets/adminlte/plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="/assets/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- Select2 -->
<script src="/assets/adminlte/plugins/select2/js/select2.full.min.js"></script>
<!-- Summernote -->
<script src="/assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
<!-- InputMask -->
<script src="/assets/adminlte/plugins/moment/moment.min.js"></script>
<script src="/assets/adminlte/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<!-- date-range-picker -->
<script src="/assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Bootstrap Icon Picker -->
<script src="/assets/adminlte/plugins/bootstrap-iconpicker/js/iconset/fontawesome5-3-1.min.js"></script>
<script src="/assets/adminlte/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="/assets/adminlte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Bootstrap Switch -->
<script src="/assets/adminlte/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>

<!-- Toastr -->
<script src="/assets/adminlte/plugins/toastr/toastr.min.js"></script>

<script type="text/javascript" src="//maps.google.com/maps/api/js?key={{ config('adminlte.google_maps_api_key') }}&libraries=places"></script>
<script src="/assets/adminlte/js/locationpicker.min.js"></script>
<script src="/assets/adminlte/js/dropzone.js"></script>
<script src="/assets/adminlte/js/media.js"></script>

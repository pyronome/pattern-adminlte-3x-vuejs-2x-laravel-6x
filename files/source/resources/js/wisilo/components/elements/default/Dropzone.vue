<template>
    <div id="dropzone-data" data-target-file-list="" data-media-type="">
        <form method="post" id="formDropzoneUpload" name="formDropzoneUpload" onsubmit="return false;" enctype="multipart/form-data">
            <div id="divDropzone" class="divDropzone row dz-clickable" style="min-height: 400px;">
                <div class="col s12">
                    <ul id="ulDropzoneUploadedFiles" class="ulDropzoneUploadedFiles"></ul>
                    <div id="divDropzoneUploaderInputContainer">
                        <input accept=".3gp,.7z,.ae,.ai,.avi,.bmp,.cdr,.csv,.divx,.doc,.docx,.dwg,.eps,.flv,.gif,.gz,.ico,.iso,.jpg,.jpeg,.mov,.mp3,.mp4,.mpeg,.pdf,.png,.ppt,.ps,.psd,.rar,.svg,.swf,.tar,.tiff,.txt,.wav,.zip" id="inputDropzoneUploadMedia" multiple="multiple" name="inputDropzoneUploadMedia" class="inputUploader" style="display:none;" type="file">
                        <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="5120000">
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    mounted() {
        WisiloHelper.initializeMediaJS();       
    }
}
</script>
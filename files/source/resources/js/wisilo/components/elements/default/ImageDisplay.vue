<template>
    <div class="modal fade" id="galleryModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <img id="popup-photo" src="">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['init_image_display'],
    mounted() {
        $("#galleryModal").on('hidden.bs.modal', function (e) {
            document.getElementById("popup-photo").src = "";
        });
    },
    watch: {
        init_image_display: function (init_image_display) {
            WisiloHelper.initializeShowPhoto();
        }
    }
}
</script>
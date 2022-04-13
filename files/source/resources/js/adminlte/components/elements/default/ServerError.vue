<template>
    <section class="content">
        <div class="container-fluid inner-swal-container" id="swalContainer" style="height:250px"></div>
    </section>
</template>

<script>

export default {
    methods: {
        swalFire: function () {
            var self = this;

            Vue.swal.fire({
                target: document.getElementById('swalContainer'),
                position: 'center',
                title: self.$t("An error occurred while processing your request."),
                icon: 'error',
                showConfirmButton: false,
                backdrop: false
            });
        }
    },
    mounted() {
        this.swalFire();
    }
}
</script>
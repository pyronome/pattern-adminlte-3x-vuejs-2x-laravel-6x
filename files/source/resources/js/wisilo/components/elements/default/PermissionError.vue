<template>
    <section class="content">
        <div class="container-fluid inner-swal-container" id="swalContainer" style="height:250px"></div>
    </section>
</template>

<script>

export default {
    props: ['authorization'],
    methods: {
        swalFire: function () {
            var authorization = this.authorization;
            var msg = "";

            if ("menu_permission" == authorization.type) {
                msg = self.$t("You have not permission to access this page.");
            } else if ("read_permission" == authorization.type) {
                msg = self.$t("You have not permission to view this object.");
            } else if ("create_permission" == authorization.type) {
                msg = self.$t("You have not permission to create an object.");
            } else if ("update_permission" == authorization.type) {
                msg = self.$t("You have not permission to update this object.");
            } else if ("custom" == authorization.type) {
                msg = authorization.msg;
            } else {
                msg = self.$t("You have not permission to change anything at this page.");
            }

            Vue.swal.fire({
                target: document.getElementById('swalContainer'),
                position: 'center',
                title: msg,
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
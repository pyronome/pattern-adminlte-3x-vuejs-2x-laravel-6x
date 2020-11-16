<template>
    <section class="content">
        <div class="container-fluid inner-swal-container" id="swalContainer"></div>
    </section>
</template>

<script>

export default {
    props: ['type'],
    methods: {
        swalFire: function () {
            var self = this;
            var msg = "";

            if ("menu_permission" == self.type) {
                msg = self.$t("You have not permission to access this page.");
            } else if ("read_permission" == self.type) {
                msg = self.$t("You have not permission to view this object.");
            } else if ("create_permission" == self.type) {
                msg = self.$t("You have not permission to create an object.");
            } else if ("update_permission" == self.type) {
                msg = self.$t("You have not permission to update this object.");
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
<template>
    <div class="content-wrapper">
        <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ $t("Profile") }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item active">{{ $t("Profile") }}</li>
                </ol>
            </div>
            </div>
        </div>
        </section>
        <section class="content">

        </section>
    </div>
</template>

<script>
export default {
    mounted() {
        AdminLTEHelper.initializeSideMenu();
    }
}
</script>
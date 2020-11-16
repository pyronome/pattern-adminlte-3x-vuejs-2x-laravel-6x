<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-xs-12">
                    <div class="page-permission-error">
                        <div v-if="'menu_permission' == type" class="alert alert-danger">
                            <span>{{ $t('You have not permission to access this page.') }}</span>
                        </div>
                        <div v-else-if="'read_permission' == type" class="alert alert-danger">
                            <span>{{ $t('You have not permission to view this object.') }}</span>
                        </div>
                        <div v-else-if="'create_permission' == type" class="alert alert-danger">
                            <span>{{ $t('You have not permission to create an object.') }}</span>
                        </div>
                        <div v-else-if="'update_permission' == type" class="alert alert-danger">
                            <span>{{ $t('You have not permission to update this object.') }}</span>
                        </div>
                        <div v-else class="alert alert-danger">
                            <span>{{ $t('You have not permission to change anything at this page.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    props: ['type']
}
</script>
<template>
    <div class="page-permission-error">
        <div v-if="'menu_permission' == type">
            <span>You have not permission to access this page.</span>
        </div>
        <div v-else-if="'read_permission' == type">
            <span>You have not permission to view this object.</span>
        </div>
        <div v-else-if="'create_permission' == type">
            <span>You have not permission to create an object.</span>
        </div>
        <div v-else-if="'update_permission' == type">
            <span>You have not permission to update this object.</span>
        </div>
        <div v-else>
            <span>You have not permission to change anything at this page.</span>
        </div>
    </div>
</template>

<script>
export default {
    props: ['type']
}
</script>
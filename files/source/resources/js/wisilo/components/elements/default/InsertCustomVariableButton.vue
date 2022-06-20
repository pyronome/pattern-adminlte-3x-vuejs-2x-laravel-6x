<template>
    <button type="button"
        class="btn-icon btn-icon-primary"
        @click="showInsertVariableDialog"
        style="float:right;"
        title="Insert Variable">
        <span class="btn-label btn-label-right">
            <i class="fas fa-database"></i>
        </span>                    
    </button>
</template>

<script>
export default {
    props: ["target"],
    methods: {
        showInsertVariableDialog: function () {
            window.__insert_custom_variable_dialog.showDialog(this.target);
        }
    },
    mounted() {
    }
}
</script>
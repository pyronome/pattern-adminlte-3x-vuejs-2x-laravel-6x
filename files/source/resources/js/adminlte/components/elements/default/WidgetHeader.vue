<template>
    <div style="display:inline-block;">
        <button type="button" class="btn-icon btn-icon-primary btn-move-widget">
            <span class="btn-label btn-label-right">
                <i class="fas fa-arrows-alt"></i>
            </span>                    
        </button>
        <button type="button" class="btn-icon btn-icon-primary btn-toggle-widget">
            <span class="btn-label btn-label-right">
                <i class="fas fa-eye"></i>
            </span>                    
        </button>
        <button type="button" class="btn-icon btn-icon-primary btn-edit-widget">
            <span class="btn-label btn-label-right">
                <i class="fas fa-cog"></i>
            </span>                    
        </button>
        <button type="button" class="btn-icon btn-icon-primary btn-remove-widget">
            <span class="btn-label btn-label-right">
                <i class="fas fa-times"></i>
            </span>                    
        </button>
    </div>
</template>

<script>
export default {
    props: ['instance_id']
}
</script>
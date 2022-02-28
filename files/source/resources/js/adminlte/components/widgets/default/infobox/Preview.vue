<template>
    <div>
        <widget-preview-dialog :widget_name="widget_name">
            <router-link class="info-box clickable-infobox" tag="div" to="https://www.google.com">
                <span class="info-box-icon elevation-1" style="backgroundColor:#17a2b8"><i class="fas fa-user"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ $t('Total User') }}</span>
                    <span class="info-box-number">1903</span>
                </div>
            </router-link>
        </widget-preview-dialog>
    </div>
</template>

<script>
    export default {
        props: ["widget_name"],
        mounted() {
        },
        methods: {
        }
    }
</script>
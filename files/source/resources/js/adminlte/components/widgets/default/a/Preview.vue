<template>
    <div>
        <widget-preview-dialog :widget_name="widget_name">
            <div class="row">
                <div class="col-lg-12">
                    <div style="padding:10px;background:gray;">
                        {{ $t('Changeable CSS & Content') }}
                    </div>
                </div>
            </div>
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
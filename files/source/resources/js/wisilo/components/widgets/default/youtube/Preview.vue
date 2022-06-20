<template>
    <div>
        <widget-preview-dialog :widget_name="widget_name">
            <iframe width="100%" height="315"
                 src="https://www.youtube.com/embed/WafvIXtktMs" 
                 title="YouTube video player" 
                 frameborder="0" 
                 allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
            </iframe>
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
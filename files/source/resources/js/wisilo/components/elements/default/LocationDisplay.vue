<template>
    <div class="googlemap">
    </div>
</template>

<script>
export default {
    props: ['coordinates'],
    watch: {
        coordinates: function (coordinates) {
            var lat = parseFloat(coordinates.lat);
            var lng = parseFloat(coordinates.lng);

            if(!isNaN(lat) && !isNaN(lng)) {
                WisiloHelper.showGoogleMap(this.$el, lat, lng);
            }
        }
    }
}
</script>
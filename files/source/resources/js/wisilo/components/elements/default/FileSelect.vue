<template>
    <div>
        <label :for="id" class="detail-label" style="width:100%;">
            <span v-html="label"></span>
        </label>
        <select2-element
            class="select2-element"
            :id="id"
            :name="id"
            :options="file_options">
        </select2-element>
    </div>
</template>

<script>
export default {
    props: ['label', 'id', 'multiple'],
    data() {
        return {
            file_options: [],
            is_file_options_loading: false,
            is_file_options_loaded: false
        }
    },
    mounted() {
        var self = this;
        var selectElement = document.getElementById(self.id);

        self.load_file_options(function() {
            if (self.multiple) {
                //console.log("select2 multiple")
                $(selectElement).select2({multiple: true});
            } else {
                //console.log("select2 ")
                $(selectElement).select2({multiple: false});
            }
        });
    },
    methods: {
        load_file_options: function(callback) {
            var self = this;
            if (self.is_file_options_loading) {
                return;
            }

            self.is_file_options_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilomedia/get_file_select_options"))
                .then(({ data }) => {
                    self.is_file_options_loaded = true;
                    self.is_file_options_loading = false;
                    self.file_options = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.is_file_options_loaded = true;
                    self.is_file_options_loading = false;
                    self.$Progress.fail();
                }).finally(function() {
                    callback();
                });
        }
    }
}
</script>
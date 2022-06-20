<template>
    <div>
        <div :id="contentContainerId"></div>
        <script id="image-template"  type="text/html">__html__</script>
        <script id="file-template"  type="text/html">__html__</script>
    </div>
</template>

<script>
export default {
    props: ["fileIds", "image_style", "file_button_style"],
    data() {
        return {
            contentContainerId: "",
            files: [],
            is_files_loading: false,
            is_files_loaded: false
        }
    },
    mounted() {
        var self = this;

        self.contentContainerId = WisiloHelper.generateGUID("fileContentContainer");
        
        self.load_files(function() {
            self.renderFileContents();
        });
    },
    methods: {
        load_files: function(callback) {
            var self = this;

            if ((undefined === self.fileIds) || ("" == self.fileIds)) {
                return;
            }

            if (self.is_files_loading) {
                return;
            }

            self.is_files_loading = true;
            
            axios.get(WisiloHelper.getAPIURL("wisilomedia/get_file_contents/" + self.fileIds))
                .then(({ data }) => {
                    self.is_files_loaded = true;
                    self.is_files_loading = false;
                    self.files = data.list;
                    self.processLoadQueue();
                }).catch(({ data }) => {
                    self.is_files_loaded = true;
                    self.is_files_loading = false;
                    self.$Progress.fail();
                }).finally(function() {
                    callback();
                });
        },
        renderFileContents: function() {
            var self = this;
            var imageTemplate = document.getElementById("image-template").innerHTML;
            var fileTemplate = document.getElementById("file-template").innerHTML;
            var contentHTML = "";

            var files = self.files;

            for (let index = 0; index < files.length; index++) {
                const element = files[index];

                if (element.is_image) {
                    contentHTML += imageTemplate.replace(/__html__/g, element.html);
                } else {
                    contentHTML += fileTemplate.replace(/__html__/g, element.html);
                }
            }

            var container = document.getElementById(self.contentContainerId);
            container.innerHTML = contentHTML;

            $("img", container).attr("style", self.image_style);
            $("button", container).attr("style", self.file_button_style);

            $(".file_download").on('click', function(e) {
                self.downloadFile(this.getAttribute("data-file-id"));
            });
        },
        downloadFile: function (file_id) {
            axios.get(WisiloHelper.getAPIURL("wisilomedia/download_file/" + file_id))
                .then(({ data }) => {
                    var a = document.createElement("a"); //Create <a>
                    a.href = data.url; //Image Base64 Goes here
                    a.download = data.filename; //File name Here
                    a.click(); //Downloaded file
                    URL.revokeObjectURL(a.href)
                }).catch(({ data }) => {
                    console.log("error!")
                });
        },
    }
}
</script>
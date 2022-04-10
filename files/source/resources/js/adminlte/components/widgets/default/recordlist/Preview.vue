<template>
    <div>
        <widget-preview-dialog :widget_name="widget_name">
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th><span>Id</span>&nbsp;</th>
                            <th><span>Created At</span>&nbsp;</th>
                            <th><span>Updated At</span>&nbsp;</th>
                            <th><span>Name</span>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2001-01-01</td>
                            <td>2001-01-01</td>
                            <td>Italy</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2002-02-02</td>
                            <td>2002-02-02</td>
                            <td>Spain</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>2003-03-03</td>
                            <td>2003-03-03</td>
                            <td>England</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>2004-04-04</td>
                            <td>2004-04-04</td>
                            <td>France</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>2005-05-05</td>
                            <td>2005-05-05</td>
                            <td>Turkey</td>
                        </tr>
                    </tbody>
                </table>
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
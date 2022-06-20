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
                            <th class="text-center th-btn-1">
                                <a class="btn btn-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-plus"></i> <span class="hidden-xxs">Add</span>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>2001-01-01</td>
                            <td>2001-01-01</td>
                            <td>Italy</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>2002-02-02</td>
                            <td>2002-02-02</td>
                            <td>Spain</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>2003-03-03</td>
                            <td>2003-03-03</td>
                            <td>England</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>2004-04-04</td>
                            <td>2004-04-04</td>
                            <td>France</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>2005-05-05</td>
                            <td>2005-05-05</td>
                            <td>Turkey</td>
                            <td class="text-center">
                                <a class="btn btn-outline-primary btn-xs btn-on-table" href="javascript:void(0);">
                                    <i class="fa fa-info-circle"></i> <span class="hidden-xxs">Detail</span>
                                </a>
                            </td>
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
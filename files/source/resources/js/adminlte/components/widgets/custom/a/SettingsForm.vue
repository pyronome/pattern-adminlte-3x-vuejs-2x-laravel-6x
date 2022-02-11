<template>
    <div>
        <div class="modal fade" id="modal-EditWidget" tabindex="1" role="dialog">
            <div class="modal-dialog modal-md">
                <form id="formWidgetItem" name="formWidgetItem" method="post" class="form-horizontal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 id="widgetModalTitle" class="modal-title">{{ $t('Widget Item') }}</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="__widgetconfig-type" name="__widgetconfig-type" class="item-widget">

                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-enabled">{{ $t('Enabled') }}</label>
                                    <select name="__widgetconfig-enabled" id="__widgetconfig-enabled" class="form-control item-widget">
                                        <option value="0">{{ $t('No') }}</option>
                                        <option value="1">{{ $t('Yes') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-text">{{ $t('Text') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-widget" name="__widgetconfig-text" id="__widgetconfig-text">
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <label for="__widgetconfig-css">{{ $t('CSS') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control item-widget" name="__widgetconfig-css" id="__widgetconfig-css">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- <div class="row mb-10">
                                <input type="hidden" id="__widgetconfig-size" name="__widgetconfig-size" class="item-widget">
                                <div class="col-md-4">
                                    <label for="large_screen_size">{{ $t('Large Screen Size') }}</label>
                                    <select id="large_screen_size" name="large_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="medium_screen_size">{{ $t('Medium Screen Size') }}</label>
                                    <select id="medium_screen_size" name="medium_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="small_screen_size">{{ $t('Small Screen Size') }}</label>
                                    <select id="small_screen_size" name="small_screen_size"  class="form-control">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12" selected>12</option>
                                    </select>
                                </div>
                            </div> -->
                            
                        </div>

                        <div class="modalfooter justify-content-between">
                            <div class="row mb-10">
                                <div class="col-md-12">
                                    <button type="button"
                                        id="buttonSaveWidget"
                                        name="buttonSaveWidget"
                                        class="btn btn-success float-right">
                                        {{ $t('Save') }}
                                    </button>
                                    <button type="button" 
                                        class="btn btn-outline-secondary float-right" 
                                        data-dismiss="modal" 
                                        style="margin-right:10px;">
                                        {{ $t('Cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            loaded: function () {
                console.log("Settings Custom A loaded")
            }
        },
        mounted() {
            console.log("Settings Custom A mounted")

            this.loaded();
        },
        updated() {
            
        }
    }
</script>
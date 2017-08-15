<template>
    <div style="display: inline-block;" class="text-left">
        <button class="btn btn-default btn-sm" type="button" @click="showExportModal=true">
            Export <span class="fa fa-download"></span>
        </button>

        <modal title="Export List" :value="showExportModal" @closed="showExportModal=false" effect="zoom" width="400" ok-text="Export" :callback="exportList">
            <div slot="modal-body" class="modal-body">

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Filename</label>
                            <input type="text"
                                   class="form-control"
                                   v-model="exportSettings.filename"
                                   placeholder="Enter an optional file name">
                        </div>
                    </div>
                    <hr class="divider inv">
                    <div class="row">
                        <div v-validate-class class="col-xs-8 form-group">
                            <label>Choose Fields to Include:</label>
                        </div>
                        <div class="col-xs-4 text-right">
                            <button class="btn btn-link btn-xs" @click.prevent="selectAllFields" v-text="exportSelectButton"></button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-6" v-for="(key, value) in options">
                            <div>
                                <label>
                                    <input type="checkbox"
                                           v-model="exportSettings.fields"
                                           :value="key"
                                           initial="off"
                                           name="fields" v-validate="{required: { rule: true, message: 'At least one field is required.' }}">
                                    {{ value }}
                                </label>
                            </div>
                        </div>
                    </div>

            </div>
        </modal>
    </div>
</template>
<script>
    export default {
        name: 'export-utility',
        props: {
            'url': {
                type: String,
                required: true
            },
            'options': {
                type: Object,
                required: true
            },
            'filters': {
                type: Object,
                required: true
            }
        },
        data(){
            return{
                showExportModal: false,
                exportSettings: {
                    fields: [],
                    filename: ''
                },
                exportSelectButton: 'Select all'
            }
        },
        methods: {
            selectAllFields() {
                if (this.exportSettings.fields.length == _.keys(this.options).length) {
                    this.exportSettings.fields = [];
                    this.exportSelectButton = 'Select all';
                } else {
                    this.exportSettings.fields = _.keys(this.options);
                    this.exportSelectButton = 'Deselect all';
                }
            },
            exportList(){
                var self = this;
                this.$validate(true, function() {
                    if (self.$validation.invalid) {
                        self.$root.$emit('showError', _.first(self.$validation.errors).message)
                        throw new Error("Validation errors");
                    }
                });

                var params = this.filters;
                $.extend(params, this.exportSettings);
                $.extend(params, {author_id: this.$root.user.id});

                this.$http.post(this.url, params).then((response) => {
                    this.$root.$emit('showSuccess', response.data.message);
                    this.showExportModal = false;
                    this.exportSettings.fields = [];
                    this.exportSettings.filename = '';
                }, (error) =>  {
                    this.$root.$emit('showError', 'The server is unable to create the export.');
                })
            }
        }
    }
</script>
<style scoped>
    input.form-control {
        display: block;
        width: 100%;
    }
</style>
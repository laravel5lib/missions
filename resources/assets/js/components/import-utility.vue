<template>
    <div style="display: inline-block;" class="text-left">
        <button class="btn btn-default btn-sm" type="button" @click="showImportModal=true">
            Import <span class="fa fa-upload"></span>
        </button>

        <modal :title="title" :value="showImportModal" @closed="showImportModal=false" effect="zoom" width="400" ok-text="Import" :callback="importList">
            <div slot="modal-body" class="modal-body">
                <spinner ref="spinner" size="sm" text="importing"></spinner>
                
                <div class="alert alert-info" v-if="totalRows > 0">
                    <p>{{ totalRows }} records are being processed. An email will be sent when importing is completed.</p>
                </div>


                    <div class="row">
                        <div class="col-sm-12 form-group" v-validate-class>
                            <label for="file" class="control-label">File</label>
                            <input type="file" id="file" accept=".csv" :value="importFile" @change="handleFile" class="form-control" name="file" v-validate="'mimes:text/csv'">
                            <span class="help-block">.csv files only</span>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Required Fields</label>
                        </div>
                        <div class="col-sm-4" v-for="required in requiredFields">
                            <code v-text="required"></code>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Optional Fields</label>
                        </div>
                        <div class="col-sm-4" v-for="optional in optionalFields">
                            <code v-text="optional"></code>
                        </div>
                    </div>

            </div>
        </modal>
    </div>
</template>
<script>
    export default {
        name: 'import-utility',
        props: {
            'url': {
                type: String,
                required: true
            },
            'title': {
                type: String,
                default: 'Import List'
            },
            'requiredFields': {
                type: Array,
                required: true
            },
            'optionalFields': {
                type: Array,
                default: []
            },
            'parentId': {
                type: String,
                default: null
            }
        },
        data(){
            return{
                showImportModal: false,
                file: null,
                importFile: null,
                totalRows: 0,
                totalImported: 0
            }
        },
        computed: {
            rejected() {
                return this.totalRows - this.totalImported;
            }
        },
        watch: {
            showImportModal(val)
            {
                if(val == false) {
                    this.totalRows = 0;
                    this.totalImported = 0;
                }
            }
        },
        methods: {
            handleFile(e) {
                var self = this;
                var reader = new FileReader();
                reader.onload = function(event){
                    // var img = new Image();
                    // self.file = img.src = event.target.result;
                    self.file = event.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            },
            importList(){
                var self = this;
                this.$validate(true, function() {
                    if (self.$validation.invalid) {
                        self.$root.$emit('showError', _.first(self.$validation.errors).message)
                        throw new Error("Validation errors");
                    }
                });

                this.$http.post(this.url, {file: this.file||undefined, required: this.requiredFields, email: this.$root.user.email, parent_id: this.parentId}).then((response) => {
                    this.totalRows = response.data.total_rows;
                    this.totalImported = response.data.total_imported;
                    this.$root.$emit('showSuccess', response.data.message);
                    this.$emit('importComplete', true);
                    // this.showImportModal = false;
                    this.file = null;
                    this.importFile = null;
                }, (error) =>  {
                    if (error.data.errors) {
                        this.$root.$emit('showError', _.first(_.toArray(error.data.errors)));
                    } else {
                        this.$root.$emit('showError', error.data.message);
                    }
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
<template>
    <div>
        <div class="row">
            <div class="form-group col-sm-offset-2 col-sm-8">
                <div class="input-group">
                    <input class="form-control input-md" 
                           placeholder="Search reports by name..." 
                           v-model="search" 
                           debounce="250">
                    <span class="input-group-addon input-md"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-center" v-if="!reports.length">
                <spinner ref="spinner" size="sm" text="Loading"></spinner>
                <p class="lead">
                    No reports found. <br />
                    <small class="text-muted" v-if="search">
                        Please try modifying your search.
                    </small>
                    <small class="text-muted" v-else>
                        Create reports using the "Export" button where available.
                    </small>
                </p>
            </div>
            <div class="col-xs-12" v-else>
                <spinner ref="spinner" size="sm" text="Loading"></spinner>
                <div class="list-group">
                    <div class="list-group-item hidden-xs">
                        <div class="row">
                            <div class="col-sm-5"><h5>Name</h5></div>
                            <div class="col-sm-3"><h5>Created</h5></div>
                            <div class="col-sm-2"><h5>Type</h5></div>
                            <div class="col-sm-2"><h5>Download</h5></div>
                        </div>
                    </div>
                    <div class="list-group-item" v-for="report in reports">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="row">
                                    <div class="col-xs-1 hidden-xs">
                                        <tooltip effect="scale" placement="top" content="Trash">
                                            <a class="text-muted" @click="destroy(report.id)">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </tooltip>
                                    </div>
                                    <div class="col-xs-12 visible-xs text-right">
                                        <a class="text-muted" @click="destroy(report.id)">
                                            <i class="fa fa-trash-o"></i> Delete
                                        </a>
                                    </div>
                                    <div class="col-xs-11 hidden-xs">
                                        <p>
                                            {{ report.name }}
                                        </p>
                                    </div>
                                    <div class="col-xs-12 visible-xs">
                                        <h5 class="text-center">
                                            {{ report.name }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3 hidden-xs">
                                <p>{{ report.created_at | moment('lll') }}</p>
                            </div>
                            <div class="col-sm-3 visible-xs">
                                <p class="text-center">{{ report.created_at | moment('lll') }}</p>
                            </div>
                            <div class="col-sm-2 hidden-xs">
                                <p><i class="fa fa-file-excel-o"></i> {{ report.type.toUpperCase() }}</p>
                            </div>
                            <div class="col-sm-2 visible-xs">
                                <p class="text-center"><i class="fa fa-file-excel-o"></i> {{ report.type.toUpperCase() }}</p>
                            </div>
                            <div class="col-sm-2">
                                <button @click="prepareToDownload(report.source)" class="btn btn-primary btn-sm btn-block">
                                    <i class="fa fa-download"></i> <span class="hidden-sm">Download</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 text-center">
                <pagination :pagination="pagination" :callback="fetch"></pagination>
            </div>
        </div>

        <admin-delete-modal :id="selectedReportId" 
                            resource="report" 
                            label="Delete Report Forever"
                            redirect="/admin/reports">
        </admin-delete-modal>

        <modal :show="showDisclaimer" ok-text="Download" cancel-text="Cancel">
          <div slot="modal-header" class="modal-header">
            <h4 class="modal-title">Privacy Notice</h4>
          </div>
          <div slot="modal-body" class="modal-body">
              <p>Some reports may contain sensitive information. <strong>Please DO NOT share reports with unauthorized personnel</strong>. If you are unsure, please ask a Missions.Me representative. Thank you for protecting our users' privacy!</p>
          </div>
          <div slot="modal-footer" class="modal-footer">
              <button type="button" class="btn btn-default" @click="showDisclaimer = false">Cancel</button>
              <button @click="download(source)" class="btn btn-primary">Download</button>
            </div>
        </modal>

    </div>
</template>
<script type="text/javascript">
    import adminDeleteModal from '../admin-delete-modal.vue';
    export default{
        name: 'reports-list',
        components: {adminDeleteModal},
        props: {
            userId: {
                type: String,
                default: null
            }
        },
        data(){
            return {
                reports: [],
                pagination: {
                    per_page: 10
                },
                search: '',
                selectedReportId: '',
                showDisclaimer: false,
                source: ''
            }
        },
        watch: {
            search(val, oldval) {
                this.pagination.current_page = 1;
                this.fetch();
            }
        },
        computed: {
            user() {
                return this.userId ? this.userId : this.$root.user.id;
            }
        },
        methods: {
            fetch() {
                this.$http.get('users/'+this.user+'/reports?search='+this.search).then((response) => {
                    this.reports = response.data.data;
                    this.pagination = response.data.meta.pagination;
                }, (error) =>  {
                    this.$root.$emit('showError', 'Unable to get reports from server.');
                });
            },
            prepareToDownload(source)
            {
                this.showDisclaimer = true;
                this.source = source;
            },
            download(source)
            {   
                if (source) {
                    this.showDisclaimer = false;
                    this.source = '';
                    window.open(source);
                } else {
                    this.$root.$emit('showError', 'The file could not be found.');
                }
            },
            destroy(id) {
                this.selectedReportId = id;
                $('#deleteConfirmationModal').modal()
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>

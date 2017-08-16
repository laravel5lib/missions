<template>
    <div class="row">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's referrals
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <template v-if="canExport">
                    <export-utility url="referrals/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                </template>
            </form>
            <hr class="divider sm inv">
        </div>
        <div class="col-sm-12" v-if="loaded && !referrals.length">
            <p class="text-center text-muted" role="alert"><em>Add and manage your referrals here!</em></p>
        </div>

        <div class="col-xs-12 col-md-4 col-sm-6" v-for="referral in referrals">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/'+ firstUrlSegment +'/records/referrals/' + referral.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{referral.applicant_name}}
                        </h5>
                    </a>
                    <div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:20px;top:5px;">
                        <!--<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/referrals/' + referral.id + '/edit'"><i class="fa fa-pencil"></i></a>-->
                        <a @click="selectedReferral = referral, deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>TYPE</label>
                            <p class="small">{{referral.type ? referral.type[0].toUpperCase() + referral.type.slice(1) : ''}} Reference</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>ATTENTION:</label>
                            <p class="small">{{referral.attention_to}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>STATUS:</label>
                            <p class="small">{{referral.status ? referral.status[0].toUpperCase() + referral.status.slice(1) : ''}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>CREATED ON</label>
                            <p class="small">{{referral.created_at|moment('lll')}}</p>
                        </div><!-- end col -->
                         <div class="col-sm-6">
                            <label>UPDATED ON</label>
                            <p class="small">{{referral.putd_at|moment('lll')}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div>
                <div class="panel-footer" style="padding: 0;" v-if="selector">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-danger" @click="setReferral(referral)">
                            Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 text-center">
            <pagination :pagination="pagination" :callback="searchReferrals"></pagination>
        </div>
        <modal :value="deleteModal" title="Remove Referral" :small="true">
            <div slot="modal-body" class="modal-body text-center">Delete this Referral?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeReferral(selectedReferral)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default{
        name: 'referrals-list',
        components: {exportUtility, importUtility},
        props: {
            'userId': {
                type: String,
                required: false
            },
            'selector': {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                referrals: [],
                selectedReferral: '',
                trips: [],
                //logic vars
                includeManaging: false,
                search: '',
                per_page: 15,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    id: 'ID',
                    applicant_name: 'Applicant Name',
                    recipient_email: 'Recipient Email',
                    attention_to: 'Attention to',
                    type: 'Type',
                    referrer: 'Referrer Details',
                    status: 'Status',
                    sent_at: 'Sent On',
                    responded_at: 'Received On',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                    user_name: 'User Name',
                    user_email: 'User Email',
                    user_phone: 'User Primary Phone',
                },
                exportFilters: {},
                importRequiredFields: [
                    'applicant_name', 'user_email', 'attention_to', 'recipient_email'
                ],
                importOptionalFields: [
                    'sent_at', 'responded_at', 'created_at', 'updated_at'
                ],
            }
        },
        computed: {
            isFacilitator() {
                return this.trips.length > 0 ? true : false;
            },
            canExport() {
                return this.firstUrlSegment == 'admin';
            }
        },
        watch:{
            'search': (val, oldVal) =>  {
                this.pagination.current_page = 1;
                this.searchReferrals();
            },
            'includeManaging': (val, oldVal) =>  {
                this.pagination.current_page = 1;
                this.searchReferrals();
            }
        },
        methods:{
            setReferral(referral) {
                this.$dispatch('set-document', referral);
            },
            removeReferral(referral){
                if(referral) {
                    this.$http.delete('referrals/' + referral.id).then((response) => {
                        this.referrals = _.reject(this.referrals, (item) => {
                            return item.id === referral.id;
                        });
                        this.selectedReferral = '';
                    });
                }
            },
            searchReferrals(){
                let params = {user: this.userId, sort: '', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
                if (this.includeManaging)
                    params.manager = this.userId;
                this.exportFilters = params;
                this.$http.get('referrals', { params: params }).then((response) => {
                    this.referrals = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            }
        },
        mounted(){
            this.$http.get('users/' + this.userId + '?include=facilitating,managing.trips').then((response) => {
                let user = response.data.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, (group) => {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }
            });
            this.searchReferrals();
        }
    }
</script>

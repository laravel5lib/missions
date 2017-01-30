<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="userId">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's referrals
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <export-utility v-if="canExport" url="referrals/export"
                                :options="exportOptions"
                                :filters="exportFilters">
                </export-utility>
                <import-utility title="Import Referrals List" 
                      url="referrals/import" 
                      :required-fields="importRequiredFields" 
                      :optional-fields="importOptionalFields">
                </import-utility>
            </form>
            <hr class="divider sm inv">
        </div>
        <div class="col-sm-12" v-if="loaded && !referrals.length">
            <p class="text-center text-muted" role="alert"><em>No records found</em></p>
        </div>

        <div class="col-md-4 col-sm-6" v-for="referral in referrals">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/'+ firstUrlSegment +'/records/referrals/' + referral.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{referral.name}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:20px;top:5px;">
                        <a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/referrals/' + referral.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedReferral = referral, deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-xs-12">
                            <label>TYPE</label>
                            <p class="small">{{referral.type|capitalize}} Reference</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>REFERRAL:</label>
                            <p class="small">{{referral.referral_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>STATUS:</label>
                            <p class="small">{{referral.status | capitalize}}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>UPDATED:</label>
                            <p class="small">{{referral.updated_at | moment 'll'}}</p>
                        </div>
                    </div>
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
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchReferrals"></pagination>
        </div>
        <modal :show.sync="deleteModal" title="Remove Referral" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Referral?</div>
            <div slot="modal-footer" claass="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeReferral(selectedReferral)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    export default{
        name: 'referrals-list',
        components: {exportUtility},
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
                    name: 'name',
                    type: 'Type',
                    referral_name: 'Referral Name',
                    referral_phone: 'Referral Phone',
                    referral_email: 'Referral Email',
                    status: 'Status',
                    sent_at: 'Sent On',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                    user_name: 'User Name',
                    user_email: 'User Email',
                    user_phone: 'User Primary Phone',
                },
                exportFilters: {},
                importRequiredFields: [
                    'name', 'user_email', 'referral_name', 'referral_email', 'referral_phone'
                ],
                importOptionalFields: [
                    'sent_at', 'created_at', 'updated_at'
                ],
            }
        },
        watch:{
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchReferrals();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchReferrals();
            }
        },
        methods:{
            canExport() {
                let roles = _.pluck(this.$root.user.roles.data, 'name');
                return !!this.$root.user ? _.contains(roles, 'admin') : false;
                // TODO - use abilities instead of roles
                // return this.$root.hasAbility('') ||  this.$root.hasAbility('') ||  this.$root.hasAbility('');
            },
            setReferral(referral) {
                this.$dispatch('set-document', referral);
            },
            removeReferral(referral){
                if(referral) {
                    this.$http.delete('referrals/' + referral.id).then(function (response) {
                        this.referrals = _.reject(this.referrals, function (item) {
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
                this.$http.get('referrals', params).then(function (response) {
                    this.referrals = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            }
        },
        ready(){
            this.searchReferrals();
        }
    }
</script>

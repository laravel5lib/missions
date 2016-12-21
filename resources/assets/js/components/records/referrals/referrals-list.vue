<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-sm-12" v-if="loaded && !referrals.length">
            <p class="text-center text-muted" role="alert"><em>No records found</em></p>
        </div>

        <div class="col-md-4 col-sm-6" v-for="referral in referrals">
            <div class="panel panel-default">
                <div class="panel-body">
                    <label>{{referral.type|capitalize}} Type</label>
                    <a role="button" :href="'/dashboard/records/referrals/' + referral.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{referral.referral_name}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:20px;top:5px;">
                        <a style="margin-right:3px;" :href="'/dashboard/records/referrals/' + referral.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedReferral = referral, deleteModal = true"><i class="fa fa-times"></i></a>
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
    export default{
        name: 'referrals-list',
        props: {
            'userId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                referrals: [],
                selectedReferral: '',
                //logic vars
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
            }
        },
        methods:{
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
                // this.$refs.spinner.show();
                this.$http('referrals?user=' + this.userId).then(function (response) {
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

<template>
    <div v-if="donors.length > 0">
        <div class="btn-group btn-group-sm" role="group" aria-label="...">
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donors'}" @click="toggleView('donors')">Donors</button>
            </div>
            <div class="btn-group btn-group-sm" role="group">
                <button type="button" class="btn btn-default" :class="{'btn-primary': activeView === 'donations'}" @click="toggleView('donations')">Donations</button>
            </div>
        </div>

        <hr class="divider inv sm">

        <div class="panel panel-default" v-for="donor in donors" v-if="activeView==='donors'">
            <div class="panel-heading" role="tab" id="heading-{{ donor.id }}">
                <h5>
                    <a role="button">
                        {{ donor.name }} <span class="small">{{donor.total_donated|currency}}</span>
                    </a>
                </h5>
            </div>
        </div>
        <div class="panel panel-default" v-for="donation in donations" v-if="activeView==='donations'">
            <div class="panel-heading" role="tab" id="heading-{{ donation.id }}">
                <h5>
                    <span class="text-success">{{ donation.amount|currency }}</span> was donated<br>
                    <small class="small">by <a :href="'@' + donation.donor.data.account_url">{{ donation.name }}</a> on {{ donation.created_at|moment 'll'}}</small>
                    <br /><small>{{ donation.comment }}</small>
                </h5>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-center">
                <nav>
                    <ul class="pagination pagination-sm">
                        <li>
                            <a>{{ pagination.total }} {{ activeView === 'donors' ? 'Donors' : 'Donations' }}</a>
                        </li>
                        <li :class="{ 'disabled': pagination.current_page == 1 }">
                            <a aria-label="Previous" @click="page=pagination.current_page-1">
                                <span aria-hidden="true">&laquo; Back</span>
                            </a>
                        </li>
                        <!--<li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>-->
                        <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                            <a aria-label="Next" @click="page=pagination.current_page+1">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name:'user-profile-fundraisers-donors',
        props: ['id'],
        data(){
            return{
                donors: [],
                donations: [],
                activeView: 'donors',

                // pagination vars
                page: 1,
                per_page: 10,
                pagination: { current_page: 1 },
            }
        },
        filters: {
            marked: marked,
        },
        watch: {
            'page': function (val, oldVal) {
                this.searchDonors();
            },
        },
        methods:{
            toggleView(view){
                switch (view) {
                    case 'donors':
                        this.searchDonors();
                        break;
                    case 'donations':
                        this.searchDonations();
                        break;
                }
                this.activeView = view;
//                this.activeView = this.activeView === 'donors' ? 'donations' : 'donors';
            },
            searchDonors(){
                this.$http.get('fundraisers{/id}/donors', {id: this.id}).then(function (response) {
                    this.donors = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            searchDonations(){
                this.$http.get('fundraisers{/id}/donations', {id: this.id, include: 'donor'}).then(function (response) {
                    this.donations = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready(){
            this.searchDonors();
        }
    }
</script>

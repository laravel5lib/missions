<template>
    <div style="position:relative">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div v-if="donations.length">

            <hr class="divider inv sm">

            <table class="table table-bordered">
                <tbody>
                <tr v-for="donation in donations">
                    <td class="col-xs-4" style="vertical-align: middle; background-color: #F4F3F4">
                        <h4 class="text-success text-center">{{ currency(donation.amount) }}</h4>
                    </td>
                    <td class="col-xs-8">
                        <h5>
                            <a class="text-capitalize"
                               :href="`/${donation.donor.data.account_url}`"
                               v-if="!donation.anonymous && donation.donor.data.account_url">
                                {{ donation.name }}
                            </a>
                            <span v-if="!donation.anonymous && !donation.donor.data.account_url">
                                {{ donation.name }}
                            </span>
                            <span v-if="donation.anonymous">Anonymous</span>
                            <small>{{ donation.created_at|moment('ll') }}</small>
                        </h5>
                        <span class="small text-muted">{{ donation.details.comment }}</span>
                    </td>
                </tr>
                </tbody>
            </table>

                    <!-- <div :id="'heading-' + donation.id">
                        <h5>
                            <span class="text-success">{{ currency(donation.amount) }}</span> was donated<br>
                            <small class="small">by
                                <a class="text-capitalize"
                                   :href="`/${donation.donor.data.account_url}`"
                                   v-if="!donation.anonymous && donation.donor.data.account_url">
                                    {{ donation.name }}
                                </a>
                                <span v-if="!donation.anonymous && !donation.donor.data.account_url">
                                    {{ donation.name }}
                                </span>
                                <span v-if="donation.anonymous">an anonymous donor</span>
                                on {{ donation.created_at|moment('ll')}}
                            </small>
                            <br /><small>{{ donation.details.comment }}</small>
                        </h5>
                    </div>
                </div> -->

            <div class="row">
                <div class="col-sm-12 text-center">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                                <a aria-label="Next" @click="loadMore">
                                    <span aria-hidden="true">Load More</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name: 'user-profile-fundraisers-donors',
        props: ['id'],
        data(){
            return {
                display: true,
                donations: [],

                // pagination vars
                page: 1,
                per_page: 10,
                pagination: {current_page: 1},
            }
        },
        filters: {
            marked: marked,
        },
        methods: {
            nextPage()
            {
                if(this.pagination.current_page < this.pagination.total_pages) {
                    this.page = this.pagination.current_page + 1;
                }
            },
            prevPage()
            {
                if(this.pagination.current_page != 1) {
                    this.page = this.pagination.current_page - 1;
                }
            },
            getDonations(){
                return this.$http.get(`fundraisers/${this.id}/donations`, { params: {
                    id: this.id,
                    include: 'donor',
                    per_page: this.per_page,
                    page: this.page
                }});
            },
            searchDonations(){
                this.getDonations().then((response) => {
                    this.donations = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            },
            loadMore(){
              if(this.pagination.current_page < this.pagination.total_pages) {
                this.page = this.pagination.current_page + 1;
                this.getDonations().then((response) => {
                  this.donations = _.union(this.donations, response.data.data);
                  this.pagination = response.data.meta.pagination;
                });
              }
            },
        },
        mounted(){
            this.searchDonations();

            this.$root.$on('DonateForm:complete', (val) => {
                this.searchDonations();
            });
        }
    }
</script>

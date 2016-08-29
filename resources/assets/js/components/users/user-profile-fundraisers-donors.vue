<template>
    <div class="">
        <div class="panel panel-default" v-for="donor in donors">
            <div class="panel-heading" role="tab" id="heading-{{ donor.id }}">
                <h5>
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ donor.id }}" aria-expanded="true" aria-controls="collapseOne">
                        {{ donor.name }} <span class="small text-success">{{donor.total_donated|currency}}</span>
                    </a>
                </h5>
            </div>
            <div id="collapse-{{ donor.id }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-{{ donor.id }}">
                <ul class="list-group">
                    <li class="list-group-item" v-for="donation in donor.donations.data">
                        <h5 class="list-group-item-heading"><a :href="'@' + donor.account_holder_id">{{ donor.name }}</a> <small>donated {{ donation.amount|currency }} on {{ donation.created_at|moment 'll'}}</small></h5>
                        <p class="list-group-item-text" v-html="donation.message | marked"></p>
                    </li>
                </ul>
                <!--<div class="panel-body"></div>-->
            </div>
            <!--<div class="panel-footer"></div>-->
        </div>

                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-12 text-center">
                    <nav>
                        <ul class="pagination pagination-sm">
                            <li>
                                <a>{{ pagination.total }} Donors</a>
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
    </div>
</template>
<script>
    var marked = require('marked');
    export default{
        name:'user-profile-fundraisers-donors',
        props: ['id'],
        data(){
            return{
                donors: [],
                // pagination vars
                page: 1,
                per_page: 10,
                pagination: {},
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
            searchDonors(){
                this.$http.get('fundraisers{/id}/donors', {id: this.id, include: 'donations'}).then(function (response) {
                    this.donors = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready(){
            this.searchDonors();
        }
    }
</script>

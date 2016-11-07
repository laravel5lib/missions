<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <div class="form-inline" style="display: inline-block;">
                        <div class="form-group">
                            <label>Show</label>
                            <select class="form-control input-sm" v-model="per_page">
                                <option v-for="option in perPageOptions" :value="option">{{option}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()">Reset Filters</button>
                    <!--<a class="btn btn-primary btn-sm" href="reservations/create">New <i class="fa fa-plus"></i></a>-->
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover">
            <thead>
            <tr>
                <th :class="{'text-primary': orderByField === 'user.data.name'}">
                    User
                    <i @click="setOrderByField('user.data.name')" v-if="orderByField !== 'user.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'user.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'type'}">
                    Email
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'campaign.data.name'}">
                    Phone
                    <i @click="setOrderByField('campaign.data.name')" v-if="orderByField !== 'campaign.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'status'}">
                    Status
                    <i @click="setOrderByField('status')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'status'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th>
                    Start &amp; End
                </th>
                <th><i class="fa fa-plane"></i></th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="reservation in reservations|filterBy search|orderBy orderByField direction">
                <td>{{reservation.given_names|capitalize}} {{reservation.surname|capitalize}}</td>
                <td>{{reservation.user.data.email}}</td>
                <td>{{reservation.user.data.phone_one}}</td>
                <td>{{reservation.status}}</td>
                <td>{{reservation.created_at|moment 'll'}}</td>
                <td>View</td>
                <td>
                    <a href="/admin{{reservation.links[0].uri}}"><i class="fa fa-eye"></i></a>
                    <!--<a href="/admin{{campaignId + reservation.links[0].uri}}/edit"><i class="fa fa-pencil"></i></a>-->
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <nav>
                            <ul class="pagination pagination-sm">
                                <li :class="{ 'disabled': pagination.current_page == 1 }">
                                    <a aria-label="Previous" @click="page=pagination.current_page-1">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                                <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                                    <a aria-label="Next" @click="page=pagination.current_page+1">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'admin-trip-reservations',
        props: ['tripId'],
        data(){
            return{
                reservations: [],
                orderByField: 'surname',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {},
                search: ''
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchReservations();
            },
            'page': function (val, oldVal) {
                this.searchReservations();
            },
            'per_page': function (val, oldVal) {
                this.searchReservations();
            }
        },

        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'surname';
                this.direction = 1;
                this.search = null;
            },
            searchReservations(){
                this.$http.get('reservations', {
                    trip_id: new Array(this.tripId),
                    include:'user',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.page
                }).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.reservations = response.data.data;
                })
            }
        },
        ready(){
            this.searchReservations();
        }
    }
</script>

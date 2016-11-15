<template>
    <div>
        <h4 class="text-center text-muted">Trips</h4>
        <hr class="divider">
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
                    <a class="btn btn-primary btn-sm" href="{{campaignId}}/trips/create">New <i class="fa fa-plus"></i></a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-striped">
            <thead>
            <tr>
                <th :class="{'text-primary': orderByField === 'group.data.name'}">
                    Group
                    <i @click="setOrderByField('group.data.name')" v-if="orderByField !== 'group.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
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
                <th></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="trip in trips|filterBy search|orderBy orderByField direction">
                <td>{{trip.group.data.name}}</td>
                <td>{{trip.type|capitalize}}</td>
                <td>{{trip.status|capitalize}}</td>
                <td>{{trip.started_at|moment 'll'}} - <br>{{trip.ended_at|moment 'll'}}</td>
                <td>{{trip.reservations}}</td>
                <td class="text-center">
                    <a href="/admin{{trip.links[0].uri}}"><i class="fa fa-gear"></i></a>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
						<pagination :pagination.sync="pagination" :callback="searchTrips"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<script>
    export default{
        name: 'admin-trips',
        data(){
            return{
                campaignId: this.$parent.campaignId,
                trips: [],
                orderByField: 'group.data.name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: ''
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchTrips();
            },
            'per_page': function (val, oldVal) {
                this.searchTrips();
            }
        },
        computed:{
        },
        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'group.data.name';
                this.direction = 1;
                this.search = null;
            },
            searchTrips(){
                this.$http.get('trips', {
                    campaign_id: this.campaignId,
                    include:'campaign,group',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                }).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.trips = response.data.data;
                })
            }

        },
        activate(done){
            this.searchTrips();
            done();
        }
    }
</script>

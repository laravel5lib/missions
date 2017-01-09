<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <select  class="form-control input-sm" v-model="filters.type">
                        <option value="">Any Type</option>
                        <option value="ministry">Ministry</option>
                        <option value="family">Family</option>
                        <option value="international">International</option>
                        <option value="media">Media</option>
                        <option value="medical">Medical</option>
                        <option value="leader">Leader</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control input-sm" v-model="filters.status" style="width:100%;">
                        <option value="">Any Status</option>
                        <option value="active">Active</option>
                        <option value="closed">Closed</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-8">
                        <h5>Trips</h5>
                    </div>
                    <div class="col-sm-4 text-right">
                        <a class="btn btn-primary btn-sm" href="{{campaignId}}/trips/create"><i class="fa fa-plus icon-left"></i> New</a>
                    </div>
                </div>
            </div><!-- end panel-heading -->
            <div class="panel-body">
                <spinner v-ref:spinner size="sm" text="Loading"></spinner>
                <div class="row">
                    <div class="col-sm-12">
                        <form class="form-inline" novalidate>
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
                            <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                                Filters
                                <i class="fa fa-filter"></i>
                            </button>
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
            </div><!-- end panel-body -->
        </div><!-- end panel -->
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'admin-trips',
        data(){
            return{
                showFilters: false,
                campaignId: this.$parent.campaignId,
                filters: {
                    type: '',
                    status: ''
                },
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
                  // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.searchTrips();
                },
                deep: true
            },
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
                this.filters = {
                    type: '',
                    status: ''
                };
            },
            searchTrips(){
                let params = {
                    campaign_id: this.campaignId,
                    include:'campaign,group',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                };
                $.extend(params, this.filters);
                // this.$refs.spinner.show();
                this.$http.get('trips', params).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.trips = response.data.data;
                    // this.$refs.spinner.hide();
                }, function (error) {
                    // this.$refs.spinner.hide();
                })
            }

        },
        activate(done){
            this.searchTrips();
            done();
        }
    }
</script>

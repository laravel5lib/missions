<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <v-select class="form-control" id="groupFilter" :debounce="250" :on-search="getGroups"
                              :value.sync="groupObj" :options="groupsOptions" label="name"
                              placeholder="Filter Group"></v-select>
                </div>
                <div class="form-group">
                    <select id="type" class="form-control input-sm" v-model="filters.trip_type" >
                        <option value="">Any Trip Type</option>
                        <option value="full">Full</option>
                        <option value="media">Media</option>
                        <option value="medical">Medical</option>
                        <option value="short">Short</option>
                    </select>
                </div>
                <div class="form-group" v-if="!tripId">
                    <v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
                              :value.sync="campaignObj" :options="campaignOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>

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
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()">Reset Filters <i class="fa fa-times"></i></button>
                    <button class="btn btn-default btn-sm " type="button" @click="showFilters=!showFilters">
                        Filters
                        <span class="caret"></span>
                    </button>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-striped">
            <thead>
            <tr>
                <th :class="{'text-primary': orderByField === 'name'}">
                    Name
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'email'}">
                    Email
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'phone'}">
                    Phone
                    <i @click="setOrderByField('phone')" v-if="orderByField !== 'phone'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'phone'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'trip.data.campaign.data.name'}">
                    Campaign
                    <i @click="setOrderByField('trip.data.campaign.data.name')" v-if="orderByField !== 'trip.data.campaign.data.name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'trip.data.type'}">
                    Trip Type
                    <i @click="setOrderByField('trip.data.type')" v-if="orderByField !== 'trip.data.type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'trip.data.group.data.name'}">
                    Group
                    <i @click="setOrderByField('trip.data.group.data.name')" v-if="orderByField !== 'status'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'trip.data.group.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="interest in interests|filterBy search|orderBy orderByField direction">
                <td>{{interest.name|capitalize}}</td>
                <td>{{interest.email}}</td>
                <td>{{interest.phone}}</td>
                <td>{{interest.trip.data.campaign.data.name}}</td>
                <td>{{interest.trip.data.type}}</td>
                <td>{{interest.trip.data.group.data.name}}</td>
                <td>
                    <a href="/admin{{interest.links[0].uri}}"><i class="fa fa-eye"></i></a>
                    <a href="/admin{{campaignId + interest.links[0].uri}}/edit"><i class="fa fa-pencil"></i></a>
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
<script>
    import vSelect from "vue-select";
    export default{
        name: 'admin-interests-list',
        components: {vSelect},
        data(){
            return{
                interests: [],
                orderByField: 'name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {},
                search: '',
                groupObj: [],
                groupsOptions: [],
                campaignObj: null,
                campaignOptions: [],
                tripObj: null,
                tripOptions: [],
                // filter vars
                filters: {
                    group: '',
                    campaign: '',
                    trip: '',
                    trip_type: '',
                },
                showFilters: false
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.searchInterests();
                },
                deep: true
            },
            'groupObj': function (val) {
                this.filters.group = val ? val.id : '';
            },
            'tripObj': function (val) {
                this.filters.trip = val ? val.id : '';
            },
            'campaignObj': function (val) {
                this.filters.campaign = val ? val.id : '';
            },
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchInterests();
            },
            'direction': function (val) {
                this.searchInterests();
            },
            'page': function (val, oldVal) {
                this.searchInterests();
            },
            'per_page': function (val, oldVal) {
                this.searchInterests();
            }
        },

        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'name';
                this.direction = 1;
                this.search = null;
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { search: search}).then(function (response) {
                    this.groupsOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCampaigns(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('campaigns', { search: search}).then(function (response) {
                    this.campaignOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getTrips(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('trips', { search: search}).then(function (response) {
                    this.tripOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            searchInterests(){
                var params = {
                    include:'trip.group,trip.campaign',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.page
                };
                $.extend(params, this.filters);

                this.$http.get('interests', params).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.interests = response.data.data;
                })
            }
        },
        ready(){
            // populate
            this.getGroups();
            this.getCampaigns();
            this.getTrips();
            this.searchInterests();
        }
    }
</script>

<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">

                <div class="form-group">
                    <v-select class="form-control" id="groupFilter" multiple :debounce="250" :on-search="getGroups"
                              :value.sync="groupsArr" :options="groupOptions" label="name"
                              placeholder="Filter Groups"></v-select>
                </div>

                <div class="form-group" v-if="!tripId">
                    <v-select class="form-control" id="campaignFilter" :debounce="250" :on-search="getCampaigns"
                              :value.sync="campaignObj" :options="campaignOptions" label="name"
                              placeholder="Filter by Campaign"></v-select>
                </div>

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

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>

        <div class="row">

            <div class="col-xs-12 text-right">
                <form class="form-inline">
                    <div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
                        <label>
                            <input type="checkbox" v-model="includeManaging"> Include my group's reservations
                        </label>
                    </div>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="showFilters=!showFilters">
                        Filters
                        <i class="fa fa-filter"></i>
                    </button>
                </form>
                <hr class="divider sm inv">
            </div>
            <template v-if="reservations.length > 0">
                <div class="col-xs-12 col-sm-6 col-md-4" v-for="reservation in reservations">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center" :class="'panel-' + reservation.trip.data.type">
                            <h5 class="text-uppercase">{{ reservation.trip.data.type }}</h5>
                        </div>
                        <div class="panel-body text-center">
                            <img :src="reservation.avatar" class="img-circle img-md">
                            <hr class="divider inv sm">
                            <h4>{{ reservation.surname }}, {{ reservation.given_names }}</h4>
                            <p class="text-capitalize small">{{ reservation.trip.data.group.data.name }}</p>
                            <label style="margin-bottom:2px;">Campaign</label>
                            <p class="text-capitalize small" style="margin-top:2px;">{{ reservation.trip.data.campaign.data.name }}</p>
                            <label style="margin-bottom:2px;font-size:10px;">Country</label>
                            <p class="text-capitalize small" style="margin-top:2px;">{{ reservation.country_name }}</p>
                            <hr class="divider inv sm">
                            <a class="btn btn-sm btn-primary" href="/dashboard/reservations/{{ reservation.id }}">View Reservation</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <pagination :pagination.sync="pagination" :callback="getReservations"></pagination>
                </div>
            </template>

    <div class="col-xs-12" v-if="reservations.length < 1">
        <p class="text-muted text-center"><em>No reservations found</em></p>
    </div>
</div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    export default{
        name: 'reservations-list',
        components: {vSelect},
        props: ['userId', 'type'],
        data(){
            return {
                reservations: [],
                pagination: { current_page: 1},
                trips: [],
                includeManaging: false,
                search: '',
                showFilters: false,
                isFacilitator: false,
                groupsArr: [],
                groupOptions: [],
                campaignObj: null,
                campaignOptions: [],
                filters: {
                    groups: '',
                    campaign: '',
                    type: ''
                }
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.getReservations();
                },
                deep: true
            },
            'groupsArr': function (val) {
                this.filters.groups = _.pluck(val, 'id')||'';
            },
            'campaignObj': function (val) {
                this.filters.campaign = val ? val.id : '';
            },
            'search': function (val, oldVal) {
                this.getReservations();
            },
            'includeManaging': function (val, oldVal) {
                this.getReservations();
            }
        },
        methods: {
            country(code){
                return code;
            },
            getReservations(){
                let params = {
                    include: 'trip.campaign,trip.group',
                    search: this.search,
                    page: this.pagination.current_page
                };

                if (this.includeManaging) {
                    params.trip = this.trips;
                } else {
                    params.user = new Array(this.userId);
                }

                switch (this.type) {
                    case 'active':
                        params.current = true;
                        break;
                    case 'archive':
                        params.archived = true;
                        break;
                }
                $.extend(params, this.filters);


                this.$http.get('reservations', params).then(function (response) {
                    this.reservations = response.data.data
                    this.pagination = response.data.meta.pagination;
                });
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { search: search}).then(function (response) {
                    this.groupOptions = response.data.data;
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

        },
        ready(){
            this.$http.get('users/' + this.userId + '?include=facilitating,managing.trips').then(function (response) {
                let user = response.data.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, function (group) {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }
            });

            this.getReservations();
        }
    }
</script>

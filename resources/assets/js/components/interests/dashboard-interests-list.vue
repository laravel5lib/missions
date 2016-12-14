<template>
    <spinner v-ref:spinner size="sm" text="Loading"></spinner>
    <div class="row">
        <div class="col-sm-12">
            <form class="form-inline text-right" novalidate>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <!--<button class="btn btn-default btn-sm " type="button" @click="showFilters=!showFilters">
                    Filters
                    <span class="caret"></span>
                </button>-->
                <!--<a class="btn btn-primary btn-sm" href="reservations/create">New <i class="fa fa-plus"></i> </a>-->
            </form>
        </div>
    </div>

    <hr class="divider sm">

    <div class="col-sm-6 col-md-4" v-for="interest in interests">
        <div class="panel panel-default" :id="interest.id">
            <div class="panel-heading"><h6>{{ interest.name }}</h6></div>
            <div class="panel-body">
                <dl class="dl-hoizontal">
                    <dt>Email</dt>
                    <dd><a :href="interest.email ? ('mailto:' + interest.email) : '#'">{{ interest.email || 'None Provided'}}</a></dd>
                    <dt>Phone</dt>
                    <dd><a :href="interest.phone ? ('tel:' + interest.phone) : '#'">{{ interest.phone || 'None Provided'}}</a></dd>
                    <dt>Contact Pref.</dt>
                    <dd>{{ interest.communication_preferences}}</dd>
                    <dt>Interested since</dt>
                    <dd>{{ interest.created | moment 'll'}}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-xs-12 text-center">
        <pagination :pagination.sync="pagination" :callback="searchInterests"></pagination>
    </div>

</template>
<script type="text/javascript">
    export default{
        name: 'dashboard-interests-list',
        props: ['groupId', 'tripId'],
        data(){
            return{
                interests:[],
                // pagination vars
                search: '',
                page: 1,
                per_page: 6,
                pagination: { current_page: 1 },

            }
        },
        watch: {
            'search': function (val) {
                this.searchInterests();
            }
        },
        methods: {
            searchInterests() {
                // this.$refs.spinner.show();
                this.$http.get('interests', {
                    group: this.groupId,
                    trip: this.tripId,
                    include: '',
                    search: this.search,
                    page: this.pagination.current_page,
                    per_page: this.per_page
                }).then(function (response) {
                    this.interests = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                });
            }
        },
        ready() {
            this.searchInterests();
        }
    }
</script>

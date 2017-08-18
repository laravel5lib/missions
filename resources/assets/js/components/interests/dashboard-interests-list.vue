<template>
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for anything">
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
        <div class="row">
            <div class="col-sm-6 col-md-4" v-for="interest in interests">
                <div class="panel panel-default" :id="interest.id">
                    <div class="panel-heading"><h5>{{ interest.name }}</h5></div>
                    <div class="panel-body">
                        <label>Email</label>
                        <p class="small" style="margin-bottom:5px;"><a :href="interest.email ? ('mailto:' + interest.email) : '#'">{{ interest.email || 'None Provided'}}</a></p>
                        <label>Phone</label>
                        <p class="small" style="margin-bottom:5px;"><a :href="interest.phone ? ('tel:' + interest.phone) : '#'">{{ interest.phone || 'None Provided'}}</a></p>
                        <label>Contact Pref.</label>
                        <p class="small" style="margin-bottom:5px;">{{ interest.communication_preferences}}</p>
                        <label>Interested since</label>
                        <p class="small" style="margin-bottom:5px;">{{ interest.created | moment('ll')}}</p>
                    </div>
                </div>
            </div>
        </div><!-- end row -->
        <div class="row">
            <div class="col-xs-12 text-center">
                <pagination :pagination="pagination" :callback="searchInterests"></pagination>
            </div>
        </div><!-- end row -->
        <hr class="divider inv xlg">
    </div>

</template>
<script type="text/javascript">
    import _ from 'underscore';
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
            'search'(val) {
                this.pagination.current_page = 1;

            }
        },
        methods: {
            debouncedSearch: _.debounce(function () {
                this.searchInterests();
            }, 250),
            searchInterests() {
                // this.$refs.spinner.show();
                this.$http.get('interests', { params: {
                    group: this.groupId,
                    trip: this.tripId,
                    include: '',
                    search: this.search,
                    page: this.pagination.current_page,
                    per_page: this.per_page
                }}).then((response) => {
                    this.interests = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                });
            }
        },
        mounted() {
            this.searchInterests();
        }
    }
</script>

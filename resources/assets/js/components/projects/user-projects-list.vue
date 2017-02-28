<template>
    <div>
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">

                <div class="form-group">
                    <v-select class="form-control" id="causeFilter" :debounce="250" :on-search="getCauses"
                              :value.sync="causeObj" :options="causeOptions" label="name"
                              placeholder="Filter by Cause"></v-select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>

        <div class="row">

            <div class="col-xs-12 text-right tour-step-find">
                <form class="form-inline">
                    <div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
                        <label>
                            <input type="checkbox" v-model="includeManaging"> Include my group's projects
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
            <div class="col-xs-12" style="position:relative">
                <!--<spinner v-ref:spinner size="sm" text="Loading"></spinner>-->
                <template v-if="projects.length > 0">
                <div class="col-xs-12 col-sm-6 col-md-4" v-for="project in projects">
                    <div class="panel panel-default">
                        <div class="panel-heading text-center">
                            <h5 class="text-capitalize" style="margin-bottom:0;">{{ project.initiative.data.type }}</h5>
                            <label>{{ project.initiative.data.cause.data.name }}</label>
                        </div>
                        <div class="panel-body text-center" :class="'panel-' + project.initiative.data.type">
                            <h5>{{ project.name }}</h5>
                            <hr class="divider inv sm">
                            <!--<img :src="project.sponsor.data.avatar" class="img-circle img-md">-->
                            <!--<hr class="divider inv sm">-->
                            <div class="col-sm-6">
                                <label style="margin-bottom:2px;">Sponsor</label>
                                <p class="text-capitalize small">{{ project.sponsor.data.name }}</p>
                            </div><!-- end col -->
                            <div class="col-sm-6">
                                <label style="margin-bottom:2px;font-size:10px;">Country</label>
                                <p class="text-capitalize small" style="margin-top:2px;">{{ project.initiative.data.country.name }}</p>
                            </div><!-- end col -->
                            <label style="margin-bottom:2px;">Raised</label>
                            <p class="text-capitalize text-success" style="margin-top:2px;">{{ project.amount_raised|currency}}</p>
                            <hr class="divider inv sm">
                            <a class="btn btn-sm btn-primary" href="/dashboard/projects/{{ project.id }}">View Project</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 text-center">
                    <pagination :pagination.sync="pagination" :callback="getProjects"></pagination>
                </div>
            </template>
            </div>
            <div class="col-xs-12" v-if="projects.length < 1">
                <p class="text-muted text-center"><em>Sponsor a project and view details here!</em></p>
                <p class="text-center"><a class="btn btn-link btn-sm" href="/sponsor-a-project">Sponsor A Project</a></p>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    export default{
        name: 'user-projects-list',
        components: {vSelect},
        props: ['userId', 'type'],
        data(){
            return {
                projects: [],
                pagination: { current_page: 1},
                trips: [],
                includeManaging: false,
                search: '',
                showFilters: false,
                isFacilitator: false,
                groupsArr: [],
                groupOptions: [],
                causeObj: null,
                causeOptions: [],
                filters: {
                    groups: '',
                    cause: '',
                    type: ''
                }
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.getProjects();
                },
                deep: true
            },
            'groupsArr': function (val) {
                this.filters.groups = _.pluck(val, 'id')||'';
            },
            'causeObj': function (val) {
                this.filters.cause = val ? val.id : '';
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.getProjects();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.getProjects();
            }
        },
        methods: {
            country(code){
                return code;
            },
            getProjects(){
                let params = {
                    include:'sponsor,initiative.cause',
                    search: this.search,
                    page: this.pagination.current_page
                };

                if (this.includeManaging) {
                    params.manager = this.userId;
                } else {
                    params.user = this.userId;
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

                // this.$refs.spinner.show();
                this.$http.get('projects', params).then(function (response) {
                    this.projects = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    // this.$refs.spinner.hide();
                });
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('groups', { search: search}).then(function (response) {
                    this.groupOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            getCauses(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('causes', { search: search}).then(function (response) {
                    this.causeOptions = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },

        },
        ready(){
            this.$http.get('users/' + this.userId + '?include=facilitating,managing.projects').then(function (response) {
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

            this.getCauses();
            this.getProjects();
        }
    }
</script>
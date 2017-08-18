<template>
    <div>
        <div class="row">
            <div class="col-sm-12">
                <form class="form-inline text-right" novalidate>
                    <!--<div class="form-inline" style="display: inline-block;">
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
                    <div id="toggleFields" class="form-toggle-menu dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Fields
                            <span class="caret"></span>
                        </button>
                        <ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="name" :disabled="maxCheck('name')">Project Name
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="sponsor" :disabled="maxCheck('sponsor')"> Sponsor
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')"> Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="country" :disabled="maxCheck('country')"> Country
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="goal" :disabled="maxCheck('goal')"> Goal
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="amount_raised" :disabled="maxCheck('amount_raised')"> Funds Raised
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="percent_raised" :disabled="maxCheck('percent_failed')"> Percent Raised
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="funded_at" :disabled="maxCheck('funded_at')"> Funded
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="created_at" :disabled="maxCheck('created_at')"> Created on
                                </label>
                            </li>
                        </ul>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()">Reset Filters <i class="fa fa-times"></i></button>
                    <hr>-->
                    <export-utility url="causes/export"
                         :options="exportOptions"
                         :filters="exportFilters">
                    </export-utility>
                    <!--<a class="btn btn-primary btn-sm" :href="'/admin/causes/' +  causeId  + '/projects/create'">New <i class="fa fa-plus"></i></a>-->
                </form>
            </div>
        </div>
        <hr>
        <div class="row" style="display:flex; flex-wrap: wrap; flex-direction: row;">
            <div v-for="(cause, index) in causes" class="col-sm-6 col-md-4" style="display:flex">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5 class="text-center">{{ cause.name }}</h5>
                    </div>
                    <div class="panel-body text-center">
                        <!--<p class="badge">{{ cause.status|capitalize }}</p><br>-->
                        <p class="small">{{ cause.short_desc }}</p>
                        <label>Countries</label>
                        <p class="small"><span v-for="country in cause.countries">
								{{ country.name }}<span v-show="index + 1 != cause.countries.length">, </span>
						</span></p>
                        <label>Projects Funded</label>
                        <p>{{ cause.projects_funded }}</p>
                        <!--<h3 class="text-success">{{ trip.starting_cost | currency }}</h3>-->
                        <a :href="'/admin/causes/' +  cause.id  + '/current-projects'" class="btn btn-primary-hollow btn-sm"><i class="fa fa-cog"></i> Manage</a>
                    </div>
                </div>
            </div>
        </div><!-- end row -->

    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'project-causes',
        components: {exportUtility},
        data() {
            return{
                causes: {},
                pagination: {},
                exportOptions: {
                    id: 'ID',
                    name: 'Name',
                    short_desc: 'Short Description',
                    countries: 'Countries',
                    country_codes: 'Country Codes',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
            }
        },
        methods: {
            fetch() {
                this.$http.get('causes').then((response) => {
                    this.causes = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>
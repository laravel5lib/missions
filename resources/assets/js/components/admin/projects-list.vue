<template>
    <div style="position: relative;">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
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
                    <export-utility url="projects/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                    <a class="btn btn-primary btn-sm" :href="'/admin/causes/' +  causeId  + '/projects/create'">New <i class="fa fa-plus"></i></a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-striped">
            <thead>
            <tr>
                <th v-if="isActive('name')" :class="{'text-primary': orderByField === 'name'}">
                    Name
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('type')">
                    Type
                </th>
                <th v-if="isActive('country')">
                    Country
                </th>
                <th v-if="isActive('sponsor')">
                    Sponsor
                </th>
                <th v-if="isActive('goal')">
                    Goal
                </th>
                <th v-if="isActive('funds_raised')">
                    Funds Raised
                </th>
                <th v-if="isActive('percent_raised')">
                    Percent Raised
                </th>
                <th v-if="isActive('funded_at')">
                    Funded
                </th>
                <th v-if="isActive('created_at')">
                    Created on
                    <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody v-if="projects.length > 0">
            <tr v-for="project in orderByProp(projects, orderByField, direction)">
                <td v-if="isActive('name')">{{ project.name|capitalize }}</td>
                <td v-if="isActive('type')">{{ project.initiative.data.type|capitalize }}</td>
                <td v-if="isActive('country')">{{ project.initiative.data.country.name|capitalize }}</td>
                <td v-if="isActive('sponsor')">{{ project.sponsor.data.name|capitalize }}</td>
                <td v-if="isActive('goal')">$ {{project.goal}}</td>
                <td v-if="isActive('funds_raised')">{{currency(project.amount_raised)}}</td>
                <td v-if="isActive('percent_raised')">{{project.percent_raised}}%</td>
                <td v-if="isActive('funded_at')">
                    <span v-if="project.funded">{{project.funded_at|moment('ll')}}</span>
                    <span v-else>In progress</span>
                </td>
                <td v-if="isActive('created_at')">{{project.created_at|moment('ll')}}</td>
                <td>
                    <a :href="`/admin/projects/${project.id}`"><i class="fa fa-cog"></i></a>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="10" class="text-center text-muted">No projects found.</td>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="10">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination="pagination" pagination-key="pagination"
                                    :callback="searchProjects"
                                    size="small">
                        </pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../export-utility.vue';
    export default{
        name: 'projects-list',
        components: {exportUtility},
        props: {
          'causeId': {
              type: String,
              required: true
          },
          'list': {
              type: String,
              default: 'current'
          }
        },
        data(){
            return{
                projects: [],
                orderByField: 'name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                activeFields: ['name', 'sponsor', 'type', 'percent_raised'],
                maxActiveFields: 6,
                exportOptions: {
                    id: 'ID',
                    name: 'Name',
                    initiative_type: 'Initiative Type',
                    rep_name: 'Rep Name',
                    sponsor_name: 'Sponsor Name',
                    plaque: 'Plaque (Prefix + Message)',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
            }
        },
        watch: {
            'search'(val, oldVal) {
                this.page = 1;
                this.searchProjects();
            },
            'page'(val, oldVal) {
                this.searchProjects();
            },
            'per_page'(val, oldVal) {
                this.searchProjects();
            }
        },

        methods:{
            isActive(field){
                return _.contains(this.activeFields, field);
            },
            maxCheck(field){
                return !_.contains(this.activeFields, field) && this.activeFields.length >= this.maxActiveFields
            },
            setOrderByField(field){
                this.orderByField = field;
                this.direction = 1;
                this.searchProjects();
            },
            resetFilter(){
                this.orderByField = 'name';
                this.direction = 1;
                this.search = null;
            },
            getParameters()
            {
                var params = {
                    include:'sponsor,initiative,rep',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc'),
                    cause: this.causeId
                };

                switch (this.list){
                    case 'current':
                        params.current = true;
                        break;
                    case 'archived':
                        params.archived = true;
                        break;
                }

                return this.exportFilters = params;
            },
            searchProjects(){
                // this.$refs.spinner.show();
                var params = this.getParameters();
                this.$http.get('projects', { params: params }).then((response) => {
                    this.pagination = response.data.meta.pagination;
                    this.projects = response.data.data;
                    // this.$refs.spinner.hide();
                })
            }
        },
        mounted(){
            this.searchProjects();
        }
    }
</script>

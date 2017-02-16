<template>
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
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
                                    <input type="checkbox" v-model="activeFields" value="type" :disabled="maxCheck('type')">Type
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="started_at" :disabled="maxCheck('started_at')"> Started on
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="ended_at" :disabled="maxCheck('ended_at')"> Ended on
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="country" :disabled="maxCheck('country')"> Country
                                </label>
                            </li>
                            <li>
                                <label class="small" style="margin-bottom: 0px;">
                                    <input type="checkbox" v-model="activeFields" value="projects" :disabled="maxCheck('projects')"> Projects
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
                    <export-utility url="initiatives/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                    <a class="btn btn-primary btn-sm" href="/admin/causes/{{ causeId }}/initiatives/create">New <i class="fa fa-plus"></i></a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-striped">
            <thead>
            <tr>
                <th v-if="isActive('type')" :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('country')">
                    Country
                </th>
                <th v-if="isActive('started_at')">
                    Started On
                    <i @click="setOrderByField('started_at')" v-if="orderByField !== 'started_at'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'started_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('ended_at')">
                    Ended On
                    <i @click="setOrderByField('ended_at')" v-if="orderByField !== 'ended_at'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'ended_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th v-if="isActive('projects')">
                    Projects
                </th>
                <th v-if="isActive('created_at')">
                    Created on
                    <i @click="setOrderByField('created_at')" v-if="orderByField !== 'created_at'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'created_at'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody v-if="initiatives.length > 0">
            <tr v-for="initiative in initiatives|filterBy search|orderBy orderByField direction">
                <td v-if="isActive('type')">{{initiative.type|capitalize}}</td>
                <td v-if="isActive('country')">{{initiative.country.name|capitalize}}</td>
                <td v-if="isActive('started_at')">{{initiative.started_at|moment 'll'}}</td>
                <td v-if="isActive('ended_at')">{{initiative.ended_at|moment 'll'}}</td>
                <td v-if="isActive('projects')">{{initiative.projects_count}}</td>
                <td v-if="isActive('created_at')">{{initiative.created_at|moment 'll'}}</td>
                <td>
                    <a href="/admin/initiatives/{{initiative.id}}"><i class="fa fa-cog"></i></a>
                </td>
            </tr>
            </tbody>
            <tbody v-else>
            <tr>
                <td colspan="7" class="text-center text-muted">No initiatives found.</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination.sync="pagination"
                                    :callback="searchInitiatives"
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
        name: 'initiatives-list',
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
                initiatives: [],
                orderByField: 'started_at',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                activeFields: ['type', 'country', 'started_at', 'ended_at'],
                maxActiveFields: 6,
                exportOptions: {
                    id: 'ID',
                    type: 'Type',
                    country: 'Country',
                    country_code: 'Country Code',
                    short_desc: 'Short Description',
                    started_at: 'Statred On',
                    ended_at: 'Ended On',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchInitiatives();
            },
            'page': function (val, oldVal) {
                this.searchInitiatives();
            },
            'per_page': function (val, oldVal) {
                this.searchInitiatives();
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
                this.searchInitiatives();
            },
            resetFilter(){
                this.orderByField = 'started_at';
                this.direction = 1;
                this.search = null;
            },
            getParameters()
            {
                var params = {
                    // include:'',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    sort: this.orderByField + '|' + (this.direction === 1 ? 'asc' : 'desc')
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
            searchInitiatives(){
                // this.$refs.spinner.show();
                var params = this.getParameters();
                this.$http.get('causes/' + this.causeId + '/initiatives', params).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.initiatives = response.data.data;
                    // this.$refs.spinner.hide();
                })
            }
        },
        ready(){
            this.searchInitiatives();
        }
    }
</script>

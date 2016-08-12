<template>
    <div>
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
                    <div class="dropdown" style="display: inline-block;">
                        <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            Sort By
                            <span class="caret"></span>
                        </button>
                        <ul style="padding: 10px 20px;" class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="status" value=""> Any Status
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="status" value="true"> Public Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="status" value="false"> Private only
                                </label>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value=""> Any Type
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value="church"> Church Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value="business"> Business only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value="nonprofit"> Non-Profit Only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value="youth"> Private only
                                </label>
                            </li>
                            <li>
                                <label style="margin-bottom: 0" class="small">
                                    <input type="radio" v-model="type" value="other"> Other only
                                </label>
                            </li>
                        </ul>
                    </div>
                    <button class="btn btn-default btn-sm" type="button" @click="resetFilter()">Reset Filters</button>
                    <a class="btn btn-primary btn-sm" href="groups/create">New <i class="fa fa-plus icon-left"></i></a>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover">
            <thead>
            <tr>
                <th :class="{'text-primary': orderByField === 'name'}">
                    Group
                    <i @click="setOrderByField('name')" v-if="orderByField !== 'name'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'type'}">
                    Type
                    <i @click="setOrderByField('type')" v-if="orderByField !== 'type'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'type'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th :class="{'text-primary': orderByField === 'country_name'}">
                    Location
                    <!--<i @click="setOrderByField('campaign.data.name')" v-if="orderByField !== 'campaign.data.name'" class="fa fa-sort pull-right"></i>-->
                    <!--<i @click="direction=direction*-1" v-if="orderByField === 'campaign.data.name'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>-->
                </th>
                <th :class="{'text-primary': orderByField === 'public'}">
                    Status
                    <i @click="setOrderByField('public')" v-if="orderByField !== 'public'" class="fa fa-sort pull-right"></i>
                    <i @click="direction=direction*-1" v-if="orderByField === 'public'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                </th>
                <th>Active Trips</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="group in groups|filterBy search|orderBy orderByField direction|filterBy status in 'public'|filterBy type in 'type'">
                <td>{{group.name}}</td>
                <td>{{group.type|capitalize}}</td>
                <td>{{group.state|capitalize}}, {{group.country_name|capitalize}}</td>
                <td>{{group.public ? 'Public' : 'Private'}}</td>
                <td>{{group.trips.data.length}}</td>
                <td>
                    <a data-toggle="tooltip" data-placement="top" title="Manage" href="/admin{{group.links[0].uri}}"><i class="fa fa-gear"></i></a>
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
    export default{
        name: 'admin-groups',
        data(){
            return{
                groups: [],
                orderByField: 'name',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {},
                search: '',
                status: '',
                type: ''
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.searchGroups();
            },
            'page': function (val, oldVal) {
                this.searchGroups();
            },
            'per_page': function (val, oldVal) {
                this.searchGroups();
            }
        },

        methods:{
            setOrderByField(field){
                return this.orderByField = field, this.direction = 1;
            },
            resetFilter(){
                this.orderByField = 'name';
                this.direction = 1;
                this.search = '';
                this.status = '';
                this.type = '';
            },
            searchGroups(){
                this.$http.get('groups', {
                    include:'trips:onlyPublished',
                    search: this.searchText,
                    per_page: this.per_page,
                    page: this.page
                }).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.groups = response.data.data;
                })
            }
        },
        ready(){
            this.searchGroups();
        }
    }
</script>

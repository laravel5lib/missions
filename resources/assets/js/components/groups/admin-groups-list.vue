<template>
    <div>
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
                    <export-utility url="groups/export"
                                    :options="exportOptions"
                                    :filters="exportFilters">
                    </export-utility>
                    <import-utility title="Import Groups List" 
                              url="groups/import" 
                              :required-fields="importRequiredFields" 
                              :optional-fields="importOptionalFields">
                    </import-utility>
                </form>
            </div>
        </div>
        <hr>
        <table class="table table-hover table-striped">
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
                <template v-if="pending">
                    <th :class="{'text-primary': orderByField === 'country_name'}">
                        Location
                        <!--<i @click="setOrderByField('notes.data[0].content')" v-if="orderByField !== 'notes.data[0].content'" class="fa fa-sort pull-right"></i>-->
                        <!--<i @click="direction=direction*-1" v-if="orderByField === 'notes.data[0].content'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>-->
                    </th>
                    <th :class="{'text-primary': orderByField === 'phone_one'}">
                        Phone One
                        <i @click="setOrderByField('public')" v-if="orderByField !== 'public'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'public'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                    <th :class="{'text-primary': orderByField === 'email'}">
                        Email
                        <i @click="setOrderByField('email')" v-if="orderByField !== 'email'" class="fa fa-sort pull-right"></i>
                        <i @click="direction=direction*-1" v-if="orderByField === 'email'" class="fa pull-right" :class="{'fa-sort-desc': direction==1, 'fa-sort-asc': direction==-1}"></i>
                    </th>
                </template>
                <template v-else>
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
                </template>

                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="group in groups|filterBy search|orderBy orderByField direction|filterBy status in 'public'|filterBy type in 'type'">
                <td>{{group.name}}</td>
                <td>{{group.type|capitalize}}</td>
                <template v-if="pending">
                    <td>{{group.state|capitalize}}, {{group.country_name|capitalize}}</td>
                    <td>{{group.phone_one}}</td>
                    <td>{{group.email}}</td>
                </template>
                <template v-else>
                    <td>{{group.state|capitalize}}, {{group.country_name|capitalize}}</td>
                    <td>{{group.public ? 'Public' : 'Private'}}</td>
                    <td>{{group.trips.data.length}}</td>
                </template>
                <td>
                    <a data-toggle="tooltip" data-placement="top" title="Manage" href="/admin{{group.links[0].uri}}"><i class="fa fa-gear"></i></a>
                </td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="7">
                    <div class="col-sm-12 text-center">
                        <pagination :pagination.sync="pagination" :callback="searchGroups"></pagination>
                    </div>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../export-utility.vue';
    import importUtility from '../import-utility.vue';
    export default{
        name: 'admin-groups',
        components: {exportUtility, importUtility},
        props: {
            pending: {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                groups: [],
                orderByField: 'name',
                direction: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: { current_page: 1 },
                search: '',
                status: '',
                type: '',
                exportOptions: {
                  name: 'Name',
                  email: 'Email',
                  type: 'Type',
                  status: 'Status',
                  phone_one: 'Primary Phone',
                  phone_two: 'Secondary Phone',
                  address: 'Street Address',
                  city: 'City',
                  state: 'State/Providence',
                  country: 'Country',
                  country_code: 'Country Code',
                  timezone: 'Timezone',
                  description: 'Description',
                  visibility: 'Visibility',
                  url: 'Profile URL',
                  avatar_source: 'Logo Source',
                  banner_source: 'Banner Source',
                  created: 'Created On',
                  updated: 'Last Updated'
                },
                exportFilters: {},
                importRequiredFields: [
                  'name', 'type', 'country_code', 'timezone',
                ],
                importOptionalFields: [
                  'email', 'status', 'url', 'created_at', 'updated_at',
                  'phone_one', 'phone_two', 'address', 'city', 'state', 'zip', 
                  'url', 'description', 'visibility', 'logo_source', 'banner_source'
                ],
            }
        },
        watch: {
            'search': function (val, oldVal) {
                this.page = 1;
                this.pagination.current_page = 1;
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
                // this.$refs.spinner.show();
                this.$http.get('groups', {
                    include:'trips:status(active),notes',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    pending: this.pending ? true : null
                }).then(function (response) {
                    this.pagination = response.data.meta.pagination;
                    this.groups = response.data.data;
                    // this.$refs.spinner.hide();
                }, function (error) {
                    // this.$refs.spinner.hide();
                    //TODO add error alert
                })
            }
        },
        ready(){
            this.searchGroups();
            $('[data-toggle="tooltip"]').tooltip();
        }
    }
</script>

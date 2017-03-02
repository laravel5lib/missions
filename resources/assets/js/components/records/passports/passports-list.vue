<template>
    <div class="row">
        <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">

                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="filters.expired"> Expired
                    </label>
                </div>

                <div class="form-group">
                    <label>Sort By</label>
                    <select class="form-control input-sm" v-model="filters.sort">
                        <option value="given_names">Given Names</option>
                        <option value="surname">Surname</option>
                        <option value="number">Passport Number</option>
                    </select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
            </form>
        </aside>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="userId">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's passports
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
                <export-utility v-if="canExport" url="passports/export"
                      :options="exportOptions"
                      :filters="exportFilters">
                  </export-utility>
                  <!-- <import-utility title="Import Passports List" 
                      url="passports/import" 
                      :required-fields="importRequiredFields" 
                      :optional-fields="importOptionalFields">
                  </import-utility> -->
            </form>
            <hr class="divider sm inv">
        </div>
        <div class="col-sm-12 tour-step-view" v-if="loaded && !passports.length">
            <p class="text-center text-muted" role="alert"><em>Add and manage your passports here!</em></p>
        </div>

        <div class="col-sm-12 tour-step-view" style="display:flex; flex-wrap: wrap;">
        <div class="col-xs-12 col-sm-6 col-md-4" v-for="passport in passports" style="display:flex; flex-direction:column;">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/'+ firstUrlSegment +'/records/passports/' + passport.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{passport.given_names}} {{passport.surname}}
                        </h5>
                    </a>
                    <div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:25px;top:12px;">
                        <a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/passports/' + passport.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedPassport = passport,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>ID</label>
                            <p class="small">{{passport.number}}</p>
                        </div>
                        <div class="col-xs-6 text-right">
                            <span class="label label-primary" v-if="passport.expired">
                                <i class="fa fa-exclamation-triangle"></i> Expired
                            </span>
                        </div>
                    </div>
                    <label>CITIZENSHIP</label>
                    <p class="small">{{passport.citizenship_name}}</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>EXPIRES ON</label>
                            <p class="small">{{passport.expires_at|moment 'll'}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;" v-if="selector">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-danger" @click="setPassport(passport)">
                        Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchPassports"></pagination>

        </div>
        <modal :show.sync="deleteModal" title="Remove Passport" small="true">
            <div slot="modal-body" class="modal-body text-center">Delete this Passport?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removePassport(selectedPassport)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default{
        name: 'passports-list',
        components: {exportUtility, importUtility},
        props: {
            'userId': {
                type: String,
                required: false
            },
            'selector': {
                type: Boolean,
                default: false
            }
        },
        data(){
            return{
                passports: [],
                selectedPassport: null,
                //logic vars
                filters: {
                    sort: 'surname'
                },
                showFilters: false,
                includeManaging: false,
                search: '',
                per_page: 9,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    number: 'Passport Number',
                    given_names: 'Given Names',
                    surname: 'Surname',
                    birth_country: 'Nationality',
                    birth_country_code: 'Nationality Country Code',
                    citizenship: 'Citizenship',
                    citizenship_country_code: 'Citizenship Country Code',
                    expires_at: 'Expire Date',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                    user_name: 'User Name',
                    user_email: 'User Email',
                    user_phone_one: 'User Primary Phone',
                    user_phone_two: 'User Secondary Phone'
                },
                exportFilters: {},
                importRequiredFields: [
                    'number', 'user_email', 'given_names', 'surname',
                    'birth_country_code', 'citizenship_country_code',
                    'expires_at'
                ],
                importOptionalFields: [
                    'created_at', 'updated_at'
                ],
            }
        },
        watch:{
            'filters': {
                handler: function (val) {
                    this.pagination.current_page = 1;
                    this.searchPassports();
                },
                deep: true
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchPassports();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchPassports();
            }

        },
        methods:{
            canExport() {
                let roles = _.pluck(this.$root.user.roles.data, 'name');
                return !!this.$root.user ? _.contains(roles, 'admin') : false;
                // TODO - use abilities instead of roles
                // return this.$root.hasAbility('') ||  this.$root.hasAbility('') ||  this.$root.hasAbility('');
            },
            setPassport(passport) {
              this.$dispatch('set-document', passport);
            },
            // emulate pagination
            removePassport(passport){
                if(passport) {
                    this.$http.delete('passports/' + passport.id).then(function (response) {
                        this.passports = _.reject(this.passports, function (item) {
                            return item.id === passport.id;
                        });
                    });
                }
            },
            searchPassports(){
                let params = {user: this.userId, sort: 'surname', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
                if (this.includeManaging)
                    params.manager = this.userId;
                this.exportFilters = params;
                $.extend(params, this.filters);
                this.$http.get('passports', params).then(function (response) {
                    this.passports = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            }

        },
        ready(){
            this.searchPassports();
        }
    }
</script>

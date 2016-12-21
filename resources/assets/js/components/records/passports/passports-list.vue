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
                <div style="margin-right:5px;" class="checkbox">
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
            </form>
            <hr class="divider sm inv">
        </div>
        <div class="col-sm-12" v-if="loaded && !passports.length">
            <p class="text-center text-muted" role="alert"><em>No records found</em></p>
        </div>

        <div class="col-sm-6 col-md-4" v-for="passport in passports">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/dashboard' + passport.links[0].uri">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{passport.given_names}} {{passport.surname}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:25px;top:12px;">
                        <a style="margin-right:3px;" :href="'/dashboard' + passport.links[0].uri + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedPassport = passport,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <label>ID</label>
                    <p class="small">{{passport.number}}</p>
                    <label>BIRTH COUNTRY</label>
                    <p class="small">{{passport.citizenship_name}}</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>ISSUED ON</label>
                            <p class="small">{{passport.issued_at|moment 'll'}}</p>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                            <label>EXPIRES ON</label>
                            <p class="small">{{passport.expires_at|moment 'll'}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end panel-body -->
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchPassports"></pagination>

        </div>
        <modal :show.sync="deleteModal" title="Remove Passport" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Passport?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removePassport(selectedPassport)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'passports-list',
        props: {
            'userId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                passports: [],
                selectedPassport: null,
                //logic vars
                filters: {
                    expired: false,
                    sort: 'surname'
                },
                showFilters: false,
                includeManaging: false,
                search: '',
                per_page: 3,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
            }
        },
        watch:{
            'filters': {
                handler: function (val) {
                    this.searchPassports();
                },
                deep: true
            },
            'search': function (val, oldVal) {
                this.searchPassports();
            },
            'includeManaging': function (val, oldVal) {
                this.searchPassports();
            }

        },
        methods:{
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

<template>
    <div class="row">
        <spinner ref="spinner" global size="sm" text="Loading"></spinner>

        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="isFacilitator && ! firstUrlSegment == 'admin'">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's visas
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" placeholder="Search" @keyup="debouncedSearch">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <template v-if="app.user.can.create_reports">
                    <export-utility url="visas/export"
                          :options="exportOptions"
                          :filters="exportFilters">
                    </export-utility>
                </template>
            </form>
            <hr class="divider sm inv">
        </div>

        <div class="col-xs-12" v-if="loaded && !visas.length">
            <div role="alert"><p class="text-center text-muted"><em>Add and manage your visas here!</em></p></div>
        </div>

        <div class="col-xs-12">
            <div class="list-group">
                <div class="list-group-item" v-for="visa in visas">
                    <h5 class="list-group-item-heading">
                        <a role="button" :href="'/'+ firstUrlSegment +'/records/visas/' + visa.id">
                            {{visa.given_names}} {{visa.surname}}
                        </a>
                        <span v-if="app.user.can.delete_visas" style="position:absolute;right:25px;top:12px;">
                            <a @click="selectedVisa = visa,deleteModal = true"><i class="fa fa-times"></i></a>
                        </span>
                    </h5>

                    <div class="row">
                        <div class="col-sm-4">
                            <label>NUMBER</label>
                            <p class="small">{{visa.number}}</p>
                        </div>
                        <div class="col-sm-4">
                            <label>COUNTRY</label>
                            <p class="small">{{visa.country_name}}</p>
                        </div>
                        <div class="col-sm-4">
                            <label>EXPIRES ON</label>
                            <p class="small">
                                {{visa.expires_at|moment('ll')}}
                                <span class="label label-primary" v-if="visa.expired">
                                    <i class="fa fa-exclamation-triangle"></i> Expired
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row" v-if="selector">
                        <div class="col-xs-12 text-right">
                            <hr class="divider sm">
                            <a class="btn btn-sm btn-primary" @click="setVisa(visa)">
                                Use This Visa
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-xs-12 text-center">
            <pagination :pagination="pagination" pagination-key="pagination" :callback="searchVisas"></pagination>
        </div>

        <modal :value="deleteModal" title="Remove Visa" :small="true">
            <div slot="modal-body" class="modal-body">Delete this Visa?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeVisa(selectedVisa)'>Delete</button>
            </div>
        </modal>
    </div>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default{
        name: 'visas-list',
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
                app: MissionsMe,
                visas: [],
                selectedVisa: null,
                trips: [],
                //logic vars
                filters: {
                    sort: 'surname'
                },
                showFilters: false,
                includeManaging: false,
                search: '',
                per_page: 9,
                pagination: {
                    current_page:1
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    number: 'Visa Number',
                    given_names: 'Given Names',
                    surname: 'Surname',
                    country: 'Country',
                    country_code: 'Country Code',
                    issued_at: 'Issue Date',
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
                    'country_code', 'issued_at', 'expires_at'
                ],
                importOptionalFields: [
                    'created_at', 'updated_at'
                ]
            }
        },
        computed: {
            isFacilitator: {
                get() {
                    return this.trips.length > 0 ? true : false;
                },
                set() {}
            }
        },
        watch:{
            'filters': {
                handler(val, oldVal) {
                    this.pagination.current_page = 1;
                    this.searchVisas();
                },
                deep: true
            },
            'search' (val, oldVal)  {
                this.pagination.current_page = 1;
            },
            'includeManaging' (val, oldVal)  {
                this.pagination.current_page = 1;
                this.searchVisas();
            }
        },
        methods:{
            setVisa(visa) {
                this.$emit('set-document', visa);
            },
            // emulate pagination
	        debouncedSearch: _.debounce(function() {
	            this.searchVisas()
	        }, 250),
            searchVisas(){
                let params = {
                    user: this.userId,
	                sort: 'author_name',
	                search: this.search,
	                per_page: this.per_page,
	                page: this.pagination.current_page
                };

                if (this.includeManaging)
                    params.manager = this.userId;

                $.extend(params, this.filters);
                this.exportFilters = params;

                this.$http.get('visas', { params: params }).then((response) => {
                    this.visas = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                    // this.$refs.spinner.hide();
                });
            },
            removeVisa(visa){
                if(visa) {
                    // this.$refs.spinner.show();
                    this.$http.delete('visas/' + visa.id).then((response) => {
                        this.visas = _.reject(this.visas, (item) => {
                            return item.id === visa.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.visas.length / this.per_page);
                        // this.$refs.spinner.hide();
                    });
                }
            }
        },
        mounted(){
            let userId = this.userId || this.$root.user.id;
            this.$http.get('users/' + userId + '?include=facilitating,managing.trips').then((response) => {
                let user = response.data.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, (group) => {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }
            });

            this.searchVisas();
        }
    }
</script>

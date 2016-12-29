<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="userId">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's visas
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
            </form>
            <hr class="divider sm inv">
        </div>

        <div class="col-sm-12" v-if="loaded && !visas.length">
            <div role="alert"><p class="text-center text-muted"><em>No records found</em></p></div>
        </div>

        <div class="col-sm-12" style="display:flex; flex-wrap: wrap;">
        <div class="col-xs-12 col-sm-6 col-md-4" v-for="visa in visas" style="display:flex; flex-direction:column;">
            <div class="panel panel-default">
                <div style="min-height:220px;" class="panel-body">
                    <a role="button" :href="'/'+ url + '/records/visas/' + visa.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{visa.given_names}} {{visa.surname}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:25px;top:12px;">
                        <a style="margin-right:3px;" :href="'/'+ url +'/records/visas/' + visa.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedVisa = visa,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-xs-6">
                            <label>ID</label>
                            <p class="small">{{visa.number}}</p>
                        </div>
                        <div class="col-xs-6 text-right">
                            <span class="label label-primary" v-if="visa.expired">
                                <i class="fa fa-exclamation-triangle"></i> Expired
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            <label>COUNTRY</label>
                            <p class="small">{{visa.country_name}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>ISSUED ON</label>
                            <p class="small">{{visa.issued_at|moment 'll'}}</p>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                            <label>EXPIRES ON</label>
                            <p class="small">{{visa.expires_at|moment 'll'}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;" v-if="selector">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-danger" @click="setVisa(visa)">
                            Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchVisas"></pagination>

        </div>
        <modal :show.sync="deleteModal" title="Remove Visa" small="true">
            <div slot="modal-body" class="modal-body">Are you sure you want to delete this Visa?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeVisa(selectedVisa)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'visas-list',
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
                visas: [],
                selectedVisa: null,
                //logic vars
                filters: {
                    expired: false,
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
            }
        },
        watch:{
            'filters': {
                handler: function (val) {
                    this.pagination.current_page = 1;
                    this.searchVisas();
                },
                deep: true
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchVisas();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchVisas();
            }
        },
        computed: {
            url: function() {
                return document.location.pathname.split("/").slice(1,2).toString();
            }
        },
        methods:{
            setVisa(visa) {
                this.$dispatch('set-document', visa);
            },
            // emulate pagination
            searchVisas(){
                let params = {user: this.userId, sort: 'author_name', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
                if (this.includeManaging)
                    params.manager = this.userId;
                $.extend(params, this.filters);
                this.$http.get('visas', params).then(function (response) {
                    this.visas = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                    // this.$refs.spinner.hide();
                });
            },
            removeVisa(visa){
                if(visa) {
                    // this.$refs.spinner.show();
                    this.$http.delete('visas/' + visa.id).then(function (response) {
                        this.visas = _.reject(this.visas, function (item) {
                            return item.id === visa.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.visas.length / this.per_page);
                        // this.$refs.spinner.hide();
                    });
                }
            }
        },
        ready(){
            this.searchVisas();
        }
    }
</script>

<template>
    <div class="row" style="position:relative">
        <spinner ref="spinner" global size="sm" text="Loading"></spinner>
        <div class="col-xs-12 text-right">
            <form class="form-inline">
                <div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
                    <label>
                        <input type="checkbox" v-model="includeManaging"> Include my group's influencer questionnaires
                    </label>
                </div>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                </div>
                <!-- <export-utility v-if="canExport" url="influencers/export"
                                :options="exportOptions"
                                :filters="exportFilters">
                </export-utility> -->
                <!-- <import-utility title="Import Influencers List"
                      url="influencers/import"
                      :required-fields="importRequiredFields" 
                      :optional-fields="importOptionalFields">
                </import-utility> -->
            </form>
            <hr class="divider sm inv">
        </div>

        <div class="col-sm-12" v-if="loaded && !influencers.length">
            <p class="text-center text-muted" role="alert"><em>Add and manage your influencer questionnaires here!</em></p>
        </div>

        <div class="col-xs-12 col-md-4 col-sm-6" v-for="influencer in influencers">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/'+ firstUrlSegment +'/records/influencers/' + influencer.id">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{influencer.author_name}}
                        </h5>
                    </a>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>SUBJECT</label>
                            <p class="small">{{influencer.subject}}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>QUESTIONS:</label>
                            <p class="small">{{influencer.content.length}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>CREATED ON</label>
                            <p class="small">{{influencer.created_at|moment('lll')}}</p>
                        </div><!-- end col -->
                         <div class="col-sm-6">
                            <label>UPDATED ON</label>
                            <p class="small">{{influencer.updated_at|moment('lll')}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                    <div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:20px;top:5px;">
                        <!--<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/influencers/' + influencer.id + '/edit'"><i class="fa fa-pencil"></i></a>-->
                        <a @click="selectedInfluencer = influencer, deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
                <div class="panel-footer" style="padding: 0;" v-if="selector">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-danger" @click="setInfluencer(influencer)">
                            Select
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12 text-center">
            <pagination :pagination="pagination" pagination-key="pagination" :callback="searchInfluencers"></pagination>
        </div>
        <modal :value="deleteModal" title="Remove Influencer" :small="true">
            <div slot="modal-body" class="modal-body text-center">Delete this Influencer?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeInfluencer(selectedInfluencer)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    let API = {
        GET: {
            body: [
                {
                    id: '...',

                }
            ]
        },
        SHOW: function (id) {
            return _.findWhere(this.GET.body, {id: id});
        },
    };
    export default{
        name: 'influencer-questionnaires-list',
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
                influencers: [],
                selectedInfluencer: '',
                trips: [],
                //logic vars
                includeManaging: false,
                search: '',
                per_page: 15,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    id: 'ID',
                    author_name: 'Author Name',
                    subject: 'Subject',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                },
                exportFilters: {},
                importRequiredFields: [
                    'author_name', 'user_email', 'subject', 'content'
                ],
                importOptionalFields: [
                    'created_at', 'updated_at'
                ],

            }
        },
        computed: {
            isFacilitator: {
                get() {
                    return this.trips.length > 0 ? true : false;
                },
                set() {}
            },
        },
        watch:{
            'search'(val, oldVal) {
                this.pagination.current_page = 1;
//                this.searchInfluencers();
            },
            'includeManaging'(val, oldVal) {
                this.pagination.current_page = 1;
                this.searchInfluencers();
            }
        },
        methods:{
            canExport() {
                let roles = _.pluck(this.$root.user.roles.data, 'name');
                return !!this.$root.user ? _.contains(roles, 'admin') : false;
                // TODO - use abilities instead of roles
                // return this.$root.hasAbility('') ||  this.$root.hasAbility('') ||  this.$root.hasAbility('');
            },
            setInfluencer(influencer) {
                this.$emit('set-document', influencer);
            },
            removeInfluencer(influencer){
                if(influencer) {
                    this.$http.delete('influencers/' + influencer.id).then((response) => {
                        this.influencers = _.reject(this.influencers, (item) => {
                            return item.id === influencer.id;
                        });
                        this.selectedInfluencer = '';
                    });
                }
            },
            debouncedSearch: _.debounce(function() {
                this.searchInfluencers()
            }, 250),
            searchInfluencers(){
                let params = {user: this.userId, subject: 'Influencer', sort: 'author_name', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
                if (this.includeManaging)
                    params.manager = this.userId;
                this.exportFilters = params;
                this.$http.get('essays', { params: params }).then((response) => {
                    this.influencers = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                    // this.$refs.spinner.hide();
                });
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

            this.searchInfluencers();

        }
    }
</script>

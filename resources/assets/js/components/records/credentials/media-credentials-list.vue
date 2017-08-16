<template>
	<div class="row">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="col-xs-12 text-right">
			<form class="form-inline">
				<div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
					<label>
						<input type="checkbox" v-model="includeManaging"> Include my group's media credentials
					</label>
				</div>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</form>
			<hr class="divider sm inv">
		</div>
		<div class="col-xs-12" v-if="loaded && !media_credentials.length">
			<p class="text-center text-muted" role="alert"><em>Add and manage your media credentials here!</em></p>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4" v-for="media_credential in media_credentials">
			<div class="panel panel-default">
				<div class="panel-body">
					<a role="button" :href="'/'+ firstUrlSegment +'/records/media-credentials/' + media_credential.id">
						<h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
							{{media_credential.applicant_name}}
						</h5>
					</a>
					<div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:25px;top:12px;">
						<!--<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/media-credentials/' + media_credential.id + '/edit'"><i
								class="fa fa-pencil"></i></a>-->
						<a @click="selectedMediaCredential = media_credential,deleteModal = true"><i
								class="fa fa-times"></i></a>
					</div>
					<hr class="divider">
					<label>Role</label>
					<p class="small">{{getRole(media_credential)}}</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>CREATED ON</label>
                            <p class="small">{{media_credential.created_at|moment('lll')}}</p>
                        </div><!-- end col -->
                         <div class="col-sm-6">
                            <label>UPDATED ON</label>
                            <p class="small">{{media_credential.putd_at|moment('lll')}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
				</div><!-- end panel-body -->
				<div class="panel-footer" style="padding: 0;" v-if="selector">
					<div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
						<a class="btn btn-danger" @click="setMedia(media_credential)">
							Select
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<pagination :pagination="pagination" :callback="searchMedias"></pagination>

		</div>
		<modal :value="deleteModal" title="Remove Media Credential" :small="true">
			<div slot="modal-body" class="modal-body">Delete this Media Credential?</div>
			<div slot="modal-footer" class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
				<button type="button" class="btn btn-primary btn-sm"
				        @click='deleteModal = false,removeMediaCredential(selectedMediaCredential)'>Delete
				</button>
			</div>
		</modal>
	</div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default {
        name: 'media-credentials-list',
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
            return {
                media_credentials: [],
                selectedMediaCredential: null,
                trips: [],
                //logic vars
                filters: {
                    expired: false,
                    sort: 'name'
                },
                showFilters: false,
                includeManaging: false,
                search: '',
                per_page: 15,
                pagination: {
                    current_page: 1
                },
                loaded: false,
                deleteModal: false,
                exportOptions: {
                    applicant_name: 'Applicant Name',
                    holder_id: 'Holder ID',
                    holder_type: 'Holder Type',
                    content: 'Content',
                    expired_at: 'Expired At',
                },
                exportFilters: {},
                importRequiredFields: [
                    'applicant_name'
                ],
                importOptionalFields: [
                    'holder_id', 'holder_type', 'content',
                    'expired_at'
                ]
            }
        },
        computed: {
            isFacilitator() {
                return this.trips.length > 0 ? true : false;
            }
        },
        watch: {
            'filters': {
                handler: (val) =>  {
                    this.pagination.current_page = 1;
                    this.searchMedias();
                },
                deep: true
            },
            'search': (val, oldVal) =>  {
                this.pagination.current_page = 1;
                this.searchMedias();
            },
            'includeManaging': (val, oldVal) =>  {
                this.pagination.current_page = 1;
                this.searchMedias();
            }

        },
        methods: {
            setMedia(media) {
                this.$dispatch('set-document', media);
            },
            searchMedias(){
                let params = {
                    user: this.userId,
                    sort: 'applicant_name',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page
                };
//                if (this.includeManaging)
//                    params.manager = this.userId;
//                params.include = '';
                $.extend(params, this.filters);
                this.$http.get('credentials/media', {params: params}).then((response) => {
                    this.media_credentials = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            },
            removeMediaCredential(media_credential){
                if (media_credential) {
                    this.$http.delete('credentials/media/' + media_credential.id).then((response) => {
                        this.media_credentials.$remove(media_credential);
                        this.paginatedMedia_credentials.$remove(media_credential);
                        this.pagination.total_pages = Math.ceil(this.media_credentials.length / this.per_page);
                    });
                }
            },
	        getRole(media_credential){
                return _.findWhere(media_credential.content, { id: 'role'}).a.name;
	        }
        },
        mounted(){
            this.$http.get('users/' + this.userId).then((response) => {
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

            this.searchMedias();
        }
    }
</script>

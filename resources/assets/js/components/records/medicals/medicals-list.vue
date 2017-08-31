<template>
	<div class="row">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="col-xs-12 text-right">
			<form class="form-inline">
				<div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
					<label>
						<input type="checkbox" v-model="includeManaging"> Include my group's medical releases
					</label>
				</div>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
                <template v-if="canExport">
    				<export-utility url="medical/releases/export"
    				                :options="exportOptions"
    				                :filters="exportFilters">
    				</export-utility>
                </template>
			</form>
			<hr class="divider sm inv">
		</div>
		<div class="col-xs-12" v-if="loaded && !medical_releases.length">
			<p class="text-center text-muted" role="alert"><em>Add and manage your medical records here!</em></p>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4" v-for="medical_release in medical_releases">
			<div class="panel panel-default">
				<div class="panel-body">
					<a role="button" :href="'/'+ firstUrlSegment +'/records/medical-releases/' + medical_release.id">
						<h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
							{{medical_release.name}}
						</h5>
					</a>
					<div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:25px;top:12px;">
						<!--<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/medical-releases/' + medical_release.id + '/edit'"><i
								class="fa fa-pencil"></i></a>-->
						<a @click="selectedMedicalRelease = medical_release,deleteModal = true"><i
								class="fa fa-times"></i></a>
					</div>
					<hr class="divider">
					<div class="row">
						<div class="col-sm-6">
							<label>Condition(s)</label>
							<p class="small">{{medical_release.has_conditions ? 'Yes' : 'No'}}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Allergy(s)</label>
							<p class="small">{{medical_release.has_allergies ? 'Yes' : 'No'}}</p>
						</div><!-- end col -->
					</div><!-- end row -->
                    <div class="row">
                        <div class="col-sm-6">
                            <label>CREATED ON</label>
                            <p class="small">{{medical_release.created_at|moment('lll')}}</p>
                        </div><!-- end col -->
                         <div class="col-sm-6">
                            <label>UPDATED ON</label>
                            <p class="small">{{medical_release.updated_at|moment('lll')}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
				</div><!-- end panel-body -->
				<div class="panel-footer" style="padding: 0;" v-if="selector">
					<div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
						<a class="btn btn-danger" @click="setMedical(medical_release)">
							Select
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<pagination :pagination="pagination" pagination-key="pagination" :callback="searchMedicals"></pagination>

		</div>
		<modal :value="deleteModal" title="Remove Medical Release" :small="true">
			<div slot="modal-body" class="modal-body">Delete this Medical Release?</div>
			<div slot="modal-footer" class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
				<button type="button" class="btn btn-primary btn-sm"
				        @click='deleteModal = false,removeMedicalRelease(selectedMedicalRelease)'>Delete
				</button>
			</div>
		</modal>
	</div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default{
        name: 'medicals-list',
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
                medical_releases: [],
                selectedMedicalRelease: null,
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
                    name: 'Name',
                    ins_provider: 'Insurance Provider',
                    ins_policy_no: 'Insurance Policy No.',
                    emergency_name: 'Emergency Name',
                    emergency_phone: 'Emergency Phone',
                    emergency_email: 'Emergency Email',
                    created_at: 'Created On',
                    updated_at: 'Last Updated',
                    user_name: 'User Name',
                    user_email: 'User Email',
                    user_phone_one: 'User Primary Phone',
                    user_phone_two: 'User Secondary Phone',
                    conditions: 'Conditions',
                    allergies: 'Allergies'
                },
                exportFilters: {},
                importRequiredFields: [
                    'name', 'user_email',
                    'ins_provider', 'ins_policy_no'
                ],
                importOptionalFields: [
                    'emergency_name', 'emergency_phone', 'emergency_email',
                    'conditions', 'allergies', 'created_at', 'updated_at'
                ]
            }
        },
        computed: {
            isFacilitator() {
                return this.trips.length > 0 ? true : false;
            },
            canExport() {
                return this.firstUrlSegment == 'admin';
            }
        },
        watch: {
            'filters': {
                handler(val, oldVal) {
                    this.pagination.current_page = 1;
                    this.searchMedicals();
                },
                deep: true
            },
            'search'(val, oldVal) {
                this.pagination.current_page = 1;
//                this.searchMedicals();
            },
            'includeManaging'(val, oldVal) {
                this.pagination.current_page = 1;
                this.searchMedicals();
            }

        },
        methods: {
            setMedical(medical) {
                this.$emit('set-document', medical);
            },
            debouncedSearch: _.debounce(function() {
                this.searchMedicals()
            }, 250),
            searchMedicals(){
                let params = {
                    user: this.userId,
                    sort: 'author_name',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page
                };
                if (this.includeManaging)
                    params.manager = this.userId;
                params.include = 'conditions,allergies,uploads';
                $.extend(params, this.filters);
                this.$http.get('medical/releases', {params: params}).then((response) => {
                    this.medical_releases = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            },
            removeMedicalRelease(medical_release){
                if (medical_release) {
                    this.$http.delete('medical/releases/' + medical_release.id).then((response) => {
                        this.medical_releases.$remove(medical_release);
                        this.paginatedMedical_releases.$remove(medical_release);
                        this.pagination.total_pages = Math.ceil(this.medical_releases.length / this.per_page);
                    });
                }
            }
        },
        mounted(){
            this.$http.get('users/' + this.userId + '?include=facilitating,managing.trips').then((response) => {
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

            this.searchMedicals();
        }
    }
</script>
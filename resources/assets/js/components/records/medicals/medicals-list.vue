<template>
	<div class="row">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="col-xs-12 text-right">
			<form class="form-inline">
				<div style="margin-right:5px;" class="checkbox" v-if="isFacilitator && ! firstUrlSegment == 'admin'">
					<label>
						<input type="checkbox" v-model="includeManaging"> Include my group's medical releases
					</label>
				</div>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
                <template v-if="app.user.can.create_reports">
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

		<div class="col-xs-12">
            <div class="list-group">
                <div class="list-group-item" 
                    v-for="release in medical_releases" 
                    :key="release.id">
                    <h5 class="list-group-item-heading">
                        <a role="button" :href="`/${firstUrlSegment}/records/medical-releases/${release.id}`">
                            {{release.name}}
                        </a>
                        <span v-if="app.user.can.delete_medical_releases">
                            <a role="button" 
                            @click="selectedMedicalRelease = release,deleteModal = true"
                            style="position:absolute;right:25px;top:12px;">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    </h5>

                    <div class="row">
                        <div class="col-sm-3">
                            <label>MEDICATION</label>
                            <p class="small">{{release.takes_medication ? 'Yes' : 'No'}}</p>
                        </div>
                        <div class="col-sm-3">
                            <label>CONDITIONS</label>
                            <p class="small">{{release.has_conditions ? 'Yes' : 'No'}}</p>
                        </div>
                        <div class="col-sm-3">
                            <label>ALLERGIES</label>
                            <p class="small">{{release.has_allergies ? 'Yes' : 'No'}}</p>
                        </div>
                        <div class="col-sm-3">
                            <label>UPDATED</label>
                            <p class="small">{{release.updated_at | moment('ll')}}</p>
                        </div>
                    </div>

                    <div class="row" v-if="selector">
                        <div class="col-xs-12 text-right">
                            <hr class="divider sm">
                            <a class="btn btn-sm btn-primary" @click="setMedicalRelease(release)">
                                Use this Medical Release
                            </a>
                        </div>
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
                app: MissionsMe,
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
            isFacilitator: {
                get() {
                    return this.trips.length > 0 ? true : false;
                },
                set() {}
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

            this.searchMedicals();
        }
    }
</script>
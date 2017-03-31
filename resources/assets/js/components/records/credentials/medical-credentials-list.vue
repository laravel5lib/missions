<template>
	<div class="row">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-xs-12 text-right">
			<form class="form-inline">
				<div style="margin-right:5px;" class="checkbox" v-if="isFacilitator">
					<label>
						<input type="checkbox" v-model="includeManaging"> Include my group's medical credentials
					</label>
				</div>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
				<!-- <export-utility url="medical/credentials/export"
				                :options="exportOptions"
				                :filters="exportFilters">
				</export-utility> -->
				<!-- <import-utility title="Import Medical Credential List"
					url="medical/credentials/import"
					:required-fields="importRequiredFields"
					:optional-fields="importOptionalFields">
				</import-utility> -->
			</form>
			<hr class="divider sm inv">
		</div>
		<div class="col-xs-12" v-if="loaded && !medical_credentials.length">
			<p class="text-center text-muted" role="alert"><em>Add and manage your medical records here!</em></p>
		</div>

		<div class="col-xs-12 col-sm-6 col-md-4" v-for="medical_credential in medical_credentials">
			<div class="panel panel-default">
				<div class="panel-body">
					<a role="button" :href="'/'+ firstUrlSegment +'/records/medical-credentials/' + medical_credential.id">
						<h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
							{{medical_credential.applicant_name}}
						</h5>
					</a>
					<div v-if="firstUrlSegment !== 'admin'" style="position:absolute;right:25px;top:12px;">
						<!--<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/medical-credentials/' + medical_credential.id + '/edit'"><i
								class="fa fa-pencil"></i></a>-->
						<a @click="selectedMedicalCredential = medical_credential,deleteModal = true"><i
								class="fa fa-times"></i></a>
					</div>
					<hr class="divider">
					<label>Role</label>
					<p class="small">{{ getRoleName(getRole(medical_credential)) }}</p>
                    <label>Attachments</label>
                    <p class="small">{{ medical_credential.uploads.data.length }} documents</p>
				</div><!-- end panel-body -->
				<div class="panel-footer" style="padding: 0;" v-if="selector">
					<div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
						<a class="btn btn-danger" @click="setMedical(medical_credential)">
							Select
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-12 text-center">
			<pagination :pagination.sync="pagination" :callback="searchMedicals"></pagination>
		</div>
		<modal :show.sync="deleteModal" title="Remove Medical Credential" small="true">
			<div slot="modal-body" class="modal-body">Delete this Medical Credential?</div>
			<div slot="modal-footer" class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
				<button type="button" class="btn btn-primary btn-sm"
				        @click='deleteModal = false,removeMedicalCredential(selectedMedicalCredential)'>Delete
				</button>
			</div>
		</modal>
	</div>
</template>
<script type="text/javascript">
    import exportUtility from '../../export-utility.vue';
    import importUtility from '../../import-utility.vue';
    export default {
        name: 'medical-credentials-list',
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
                medical_credentials: [],
                selectedMedicalCredential: null,
                trips: [],
                roles: [
                    {value: 'MDPF', name: 'Medical Professional', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'MDSG', name: 'Medical Student', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSN', name: 'Medical Student: Nursing', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSP', name: 'Medical Student: Pre-Med', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSD', name: 'Medical Student: Dental', required: ['letter', ], optional: ['certification']},
                    {value: 'RESP', name: 'Respitory Therapist', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHYA', name: 'Physican\'s Assistant', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'PHYT', name: 'Physical Therapist', required: ['license', 'diploma','resume' ], optional: []},
                    {value: 'PHAT', name: 'Pharmacy Tech', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHAA', name: 'Pharmacy Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHAR', name: 'Pharmacist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'OTEC', name: 'Optometry Tech', required: ['license', 'diploma', ], optional: []},
                    {value: 'ODOC', name: 'Optometry Doctor', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'OAST', name: 'Optical Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'DIET', name: 'Dietitian', required: ['license', 'diploma', ], optional: []},
                    {value: 'NUTR', name: 'Nutrionist', required: ['license', 'resume'], optional: ['certification']},
                    {value: 'LACT', name: 'Lactation Consultant', required: ['license', 'resume'], optional: ['certification']},
                    {value: 'NAST', name: 'Nurse Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'NTEC', name: 'Nurse Tech', required: ['license', 'diploma', ], optional: []},
                    //{value: 'NPRC', name: 'Nurse Practitioner', required: [], optional: []},
                    {value: 'REGN', name: 'Nurse (RN)', required: ['license', 'diploma', ], optional: []},
                    {value: 'LPNN', name: 'Nurse (LPN)', required: ['license', 'diploma', ], optional: []},
                    {value: 'NCRT', name: 'Non-Certified', required: [], optional: []},
                    {value: 'MEDA', name: 'Medical Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'LVNN', name: 'LVN', required: ['license', 'diploma', ], optional: []},
                    {value: 'HEDU', name: 'Health Education', required: [], optional: ['license', 'diploma', 'resume']},
                    {value: 'ETEC', name: 'EMT', required: ['license', 'diploma', ], optional: []},
                    {value: 'MDFG', name: 'Doctor (OB/GYN)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'MDOC', name: 'Doctor (MD)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DDOC', name: 'Doctor (DO)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DENT', name: 'Dentist (DDS)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DENH', name: 'Dental Hygienist', required: ['license', 'diploma', ], optional: []},
                    {value: 'DENA', name: 'Dental Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'CHRA', name: 'Chiropractor Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'CHRO', name: 'Chiropractor', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'RDIO', name: 'Radiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'CRDO', name: 'Cardiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'ANES', name: 'Anesthesiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'PRAY', name: 'Prayer Team', required: [], optional: []}
                ],
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
                handler: function (val) {
                    this.pagination.current_page = 1;
                    this.searchMedicals();
                },
                deep: true
            },
            'search': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchMedicals();
            },
            'includeManaging': function (val, oldVal) {
                this.pagination.current_page = 1;
                this.searchMedicals();
            }

        },
        methods: {
            setMedical(medical) {
                this.$dispatch('set-document', medical);
            },
            searchMedicals(){
                let params = {
                    user: this.userId,
                    sort: 'applicant_name',
                    search: this.search,
                    per_page: this.per_page,
                    page: this.pagination.current_page,
                    include: 'uploads'
                };
//                if (this.includeManaging)
//                    params.manager = this.userId;
//                params.include = '';
                $.extend(params, this.filters);
                this.$http.get('credentials/medical', {params: params}).then(function (response) {
                    this.medical_credentials = response.body.data;
                    this.pagination = response.body.meta.pagination;
                    this.loaded = true;
                });
            },
            removeMedicalCredential(medical_credential){
                if (medical_credential) {
                    this.$http.delete('credentials/medical/' + medical_credential.id).then(function (response) {
                        this.medical_credentials.$remove(medical_credential);
                        this.paginatedMedical_credentials.$remove(medical_credential);
                        this.pagination.total_pages = Math.ceil(this.medical_credentials.length / this.per_page);
                    });
                }
            },
	        getRole(medical_credential){
                return _.findWhere(medical_credential.content, { id: 'role'}).a;
	        },
	        getRoleName(role){
	            return _.findWhere(this.roles, { value: role}).name;
	        }
        },
        ready(){
            this.$http.get('users/' + this.userId).then(function (response) {
                let user = response.body.data;
                let managing = [];

                if (user.facilitating.data.length) {
                    this.isFacilitator = true;
                    let facilitating = _.pluck(user.facilitating.data, 'id');
                    this.trips = _.union(this.trips, facilitating);
                }

                if (user.managing.data.length) {
                    _.each(user.managing.data, function (group) {
                        managing = _.union(managing, _.pluck(group.trips.data, 'id'));
                    });
                    this.trips = _.union(this.trips, managing);
                }
            });

            this.searchMedicals();
        }
    }
</script>

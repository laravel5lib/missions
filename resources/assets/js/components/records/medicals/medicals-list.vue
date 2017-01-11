<template>
	<div class="row">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="col-xs-12 text-right">
			<form class="form-inline">
				<div style="margin-right:5px;" class="checkbox" v-if="userId">
					<label>
						<input type="checkbox" v-model="includeManaging"> Include my group's medical releases
					</label>
				</div>
				<div class="input-group input-group-sm">
					<input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search">
					<span class="input-group-addon"><i class="fa fa-search"></i></span>
				</div>
			</form>
			<hr class="divider sm inv">
		</div>
		<div class="col-sm-12" v-if="loaded && !medical_releases.length">
			<p class="text-center text-muted" role="alert"><em>No records found</em></p>
		</div>

		<div class="col-sm-6 col-md-4" v-for="medical_release in medical_releases">
			<div class="panel panel-default">
				<div class="panel-body">
					<a role="button" :href="'/'+ firstUrlSegment +'/records/medical-releases/' + medical_release.id">
						<h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
							{{medical_release.name}}
						</h5>
					</a>
					<div style="position:absolute;right:25px;top:12px;">
						<a style="margin-right:3px;" :href="'/'+ firstUrlSegment +'/records/medical-releases/' + medical_release.id + '/edit'"><i
								class="fa fa-pencil"></i></a>
						<a @click="selectedMedicalRelease = medical_release,deleteModal = true"><i
								class="fa fa-times"></i></a>
					</div>
					<hr class="divider">
					<div class="row">
						<div class="col-sm-6">
							<label>Condition(s)</label><p class="small">{{medical_release.has_conditions ? 'Yes' : 'No'}}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Allergy(s)</label><p class="small">{{medical_release.has_allergies ? 'Yes' : 'No'}}</p>
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
		<div class="col-sm-12 text-center">
			<pagination :pagination.sync="pagination" :callback="searchMedicals"></pagination>

		</div>
		<modal :show.sync="deleteModal" title="Remove Medical Release" small="true">
			<div slot="modal-body" class="modal-body">Are you sure you want to delete this Medical Release?</div>
			<div slot="modal-footer" class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
				<button type="button" class="btn btn-primary btn-sm"
						@click='deleteModal = false,removeMedicalRelease(selectedMedicalRelease)'>Confirm
				</button>
			</div>
		</modal>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'medicals-list',
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
			// emulate pagination
			searchMedicals(){
				let params = {user: this.userId, sort: 'author_name', search: this.search, per_page: this.per_page, page: this.pagination.current_page};
				if (this.includeManaging)
					params.manager = this.userId;
					params.include = 'conditions,allergies,uploads';
				$.extend(params, this.filters);
				this.$http.get('medical/releases', params).then(function (response) {
					this.medical_releases = response.data.data;
					this.pagination = response.data.meta.pagination;
					this.loaded = true;
					// this.$refs.spinner.hide();
				});
			},
			removeMedicalRelease(medical_release){
				if (medical_release) {
					// this.$refs.spinner.show();
					this.$http.delete('medical/releases/' + medical_release.id).then(function (response) {
						this.medical_releases.$remove(medical_release);
						this.paginatedMedical_releases.$remove(medical_release);
						this.pagination.total_pages = Math.ceil(this.medical_releases.length / this.per_page);
						// this.$refs.spinner.hide();
					});
				}
			}
		},
		ready(){
			this.searchMedicals();
		}
	}
</script>

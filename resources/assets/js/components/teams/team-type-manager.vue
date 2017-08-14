<template>
	<div class="row" style="position: relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="col-md-5 col-md-push-7">
			<!-- Search and Filter -->
			<form class="form-inline row">
				<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
					<div class="input-group input-group-sm col-xs-12">
						<input type="text" class="form-control" v-model="teamTypesSearch" debounce="300" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div><!-- end col -->
				<div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
					<button class="btn btn-primary btn-sm btn-block" type="button" @click="createTypeMode">
						Create Squad Type
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider inv">
				</div>
			</form>
			<!-- Team Types Accordion -->
			<div class="panel-group" id="teamTypesAccordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default" v-for="(teamType, typeIndex) in teamTypesFiltered">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<div class="row">
								<div class="col-xs-9">
									<a role="button" data-toggle="collapse" data-parent="#teamTypesAccordion" :href="'#teamTypeItem' + typeIndex" aria-expanded="true" aria-controls="collapseOne">
										<h4>{{ teamType.name ? teamType.name[0].toUpperCase() + teamType.name.slice(1) : '' }}</h4>
									</a>
								</div>
								<div class="col-xs-3 text-right action-buttons">
									<dropdown type="default">
										<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
											<span class="fa fa-ellipsis-h"></span>
										</button>
										<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
											<li><a @click="editType(teamType)">Edit</a></li>
											<li><a @click="confirmDelete(teamType)">Delete</a></li>
										</ul>
									</dropdown>
									<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#teamTypesAccordion" :href="'#teamTypeItem' + typeIndex" aria-expanded="true" aria-controls="collapseOne">
										<i class="fa fa-angle-down"></i>
									</a>
								</div>
							</div>

						</h4>
					</div>
					<div :id="'teamTypeItem' + typeIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6" v-for="(key, value) in teamType.rules">
									<label v-text="getRuleLabel(key)"></label>
									<p class="small" v-text="value"></p>
								</div>
							</div><!-- end row -->
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="col-md-7 col-md-pull-5">
			<template v-if="currentType">

					<form class="form-inlvine" @submit.prevent="editTypeMode ? updateType() : createType()" id="TypeForm">
						<div class="form-group" v-error-handler="{ value: currentType.name, client: 'name', messages: { req: 'Please name this type'} }">
							<label class="control-label col-sm-4">Name</label>
							<input type="text" class="form-control" name="name=" v-model="currentType.name" v-validate="'required'">
						</div>
						<div class="row">
							<template v-for="(key, value) in currentType.rules">
								<div class="col-sm-6"  v-error-handler="{ value: value, client: key }">
									<div class="form-group">
										<label v-text="getRuleLabel(key)"></label>
										<input type="number" class="form-control" v-model="currentType.rules[key]" :field="key" v-validate="'required'" :value="value" min="0">
									</div>
								</div>
							</template>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-sm" type="submit" v-text="editTypeMode ? 'Update Squad Type' : 'Create Squad Type'"></button>
						</div>
					</form>

			</template>

		</div>

		<modal title="Delete Team Type" small ok-text="Delete" :callback="deleteType" :value="showTypeDeleteModal" @closed="showTypeDeleteModal=false">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedType">
					Are you sure you want to delete type: <b>{{selectedType.name}}</b> ?
				</p>
			</div>
		</modal>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'team-type-manager',
        mixins: [errorHandler],
        props: ['campaignId'],
        data(){
            return {
                // mixin settings
//                validatorHandle: 'TypeForm',

                currentType: null,
                selectedType: null,
                editTypeMode: false,
                showTypeDeleteModal: false,
                teamTypes: [],
                teamTypeResource: this.$resource('teams/types{/id}'),
                teamTypesSearch: '',
            }
        },
	    computed: {
            teamTypesFiltered() {
                return this.teamTypes.filter((team) => {
                    return team.name.indexOf(this.teamTypesSearch) !== -1
                });
            }
	    },
        methods: {
            getRuleLabel(key){
                switch(key) {
                    case 'min_members':
                        return 'Minimum Squad Members Required';
                    case 'max_members':
                        return 'Maximum Squad Members Allowed';
                    case 'min_leaders':
                        return 'Minimum Squad Leaders Required';
                    case 'max_leaders':
                        return 'Maximum Squad Leaders Allowed';
                    case 'min_groups':
                        return 'Minimum Groups Required';
                    case 'max_groups':
                        return 'Maximum Groups Allowed';
                    case 'min_group_members':
                        return 'Minimum Members per Group';
                    case 'max_group_members':
                        return 'Maximum Members per Group';
                    case 'min_group_leaders':
                        return 'Minimum Leaders per Group';
                    case 'max_group_leaders':
                        return 'Maximum Leaders per Group';
                }
            },
            newTypeModel() {
                return {
                    name: '',
                    campaign_id: this.campaignId,
	                rules: {
                        min_members: 25,
                        max_members: 25,
                        min_leaders: 2,
                        max_leaders: 2,
                        min_groups: 2,
                        max_groups: 10,
                        min_group_members: 2,
                        max_group_members: 5,
                        min_group_leaders: 1,
                        max_group_leaders: 1
                    }
                }
            },
            editType(type) {
                this.editTypeMode = true;
                this.currentType = type;
            },
            createTypeMode() {
                this.editTypeMode = false;
                this.currentType = this.newTypeModel();
            },
	        updateType() {

                this.resetErrors();
                if (this.$TypeForm.valid) {
                    let updatingObject = _.extend({}, this.currentType);
	                let originalType = _.findWhere(this.teamTypes, { id: this.currentType.id});

                    // check if name changed
                    if (originalType.name === this.currentType.name)
	                    delete updatingObject.name;

                    return this.teamTypeResource.update({ id: updatingObject.id }, updatingObject).then((response) => {
                        _.extend(_.findWhere(this.teamTypes, { id: updatingObject.id}), response.data.data);
                        this.$root.$emit('showSuccess', 'Team Type: ' + response.data.data.name + ', successfully updated');
                    }, function (response) {
                        this.$root.$emit('showError', response.data.message);
                    });
                }

	        },
	        createType() {

                this.resetErrors();
                if (this.$TypeForm.valid) {
                    return this.teamTypeResource.post(this.currentType).then((response) => {
                        this.teamTypes.push(response.data.data);
                        this.$root.$emit('showSuccess', 'Team Type: ' + response.data.data.name + ', successfully created');
                    }, function (response) {
                        this.$root.$emit('showError', response.data.message);

                    });
                }
	        },
            confirmDelete(type) {
                this.selectedType = type;
                this.showTypeDeleteModal = true;
            },
            deleteType() {
                this.teamTypeResource
	                .delete({ id: this.selectedType.id})
	                .then((response) => {
	                    this.teamTypes = _.reject(this.teamTypes, function (type) {
		                    return type.id === this.selectedType.id
                        }.bind(this));
                        this.showTypeDeleteModal = true;
                        this.$root.$emit('showInfo', 'Team Type: ' + this.selectedType.name + ', successfully deleted');
                        this.$nextTick(function () {
                            this.selectedType = null;
                        });
                    }, function (response) {
                        this.$root.$emit('showError', response.data.message);
                    });
            },
            getTeamTypes() {
                return this.teamTypeResource.get({campaign: this.campaignId}).then((response) => {
                    return this.teamTypes = response.data.data;
                }, function (error) {
                    console.log(error);
                    return error;
                });
            },
        },
        mounted(){
			this.getTeamTypes();
        }
    }
</script>
<template>
	<div class="row" style="position: relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
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
						Create Team Type
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider inv">
				</div>
			</form>
			<!-- Team Types Accordion -->
			<div class="panel-group" id="teamTypesAccordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default" v-for="teamType in teamTypes | filterBy teamTypesSearch">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<div class="row">
								<div class="col-xs-9">
									<a role="button" data-toggle="collapse" data-parent="#teamTypesAccordion" :href="'#teamTypeItem' + $index" aria-expanded="true" aria-controls="collapseOne">
										<h4>{{ teamType.name | capitalize }}</h4>
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
									<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#teamTypesAccordion" :href="'#teamTypeItem' + $index" aria-expanded="true" aria-controls="collapseOne">
										<i class="fa fa-angle-down"></i>
									</a>
								</div>
							</div>

						</h4>
					</div>
					<div :id="'teamTypeItem' + $index" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6" v-for="(key, value) in teamType.rules">
									<label v-text="key | underscoreToSpace | capitalize"></label>
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
				<validator name="TypeForm">
					<form class="form-inlvine" @submit.prevent="editTypeMode ? updateType() : createType()" id="TypeForm">
						<div class="form-group" v-error-handler="{ value: currentType.name, client: 'name', messages: { req: 'Please name this type'} }">
							<label class="control-label col-sm-4">Name</label>
							<input type="text" class="form-control" v-validate:name="['required']" v-model="currentType.name">
						</div>
						<div class="row">
							<template v-for="(key, value) in currentType.rules">
								<div class="col-sm-6"  v-error-handler="{ value: value, client: key }">
									<div class="form-group">
										<label v-text="key | underscoreToSpace | capitalize"></label>
										<input type="number" class="form-control" v-model="currentType.rules[key]" :field="key" v-validate="['required']" :value="value" min="0">
									</div>
								</div>
							</template>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-sm" type="submit" v-text="editTypeMode ? 'Update Type' : 'Create Type'"></button>
						</div>
					</form>
				</validator>
			</template>

		</div>

		<modal title="Delete Team Type" small ok-text="Delete" :callback="deleteType" :show.sync="showTypeDeleteModal">
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
        data(){
            return {
                // mixin settings
                validatorHandle: 'TypeForm',

                currentType: null,
                selectedType: null,
                editTypeMode: false,
                showTypeDeleteModal: false,
                teamTypes: [],
                teamTypeResource: this.$resource('teams/types{/id}')
            }
        },
        methods: {
            newTypeModel() {
                return {
                    name: '',
	                rules: {
                        min_members: 25,
                        max_members: 25,
                        min_leaders: 2,
                        max_leaders: 2,
                        min_squads: 2,
                        max_squads: 10,
                        min_squad_members: 2,
                        max_squad_members: 5,
                        min_squad_leaders: 1,
                        max_squad_leaders: 1
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

                    return this.teamTypeResource.update({ id: updatingObject.id }, updatingObject).then(function (response) {
                        _.extend(_.findWhere(this.teamTypes, { id: updatingObject.id}), response.body.data);
                        this.$root.$emit('showSuccess', 'Team Type: ' + response.body.data.name + ', successfully updated');
                    }, function (response) {
                        this.$root.$emit('showError', response.body.message);
                    });
                }

	        },
	        createType() {

                this.resetErrors();
                if (this.$TypeForm.valid) {
                    return this.teamTypeResource.save(this.currentType).then(function (response) {
                        this.teamTypes.push(response.body.data);
                        this.$root.$emit('showSuccess', 'Team Type: ' + response.body.data.name + ', successfully created');
                    }, function (response) {
                        this.$root.$emit('showError', response.body.message);

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
	                .then(function (response) {
	                    this.teamTypes = _.reject(this.teamTypes, function (type) {
		                    return type.id === this.selectedType.id
                        }.bind(this));
                        this.showTypeDeleteModal = true;
                        this.$root.$emit('showInfo', 'Team Type: ' + this.selectedType.name + ', successfully deleted');
                        this.$nextTick(function () {
                            this.selectedType = null;
                        });
                    }, function (response) {
                        this.$root.$emit('showError', response.body.message);
                    });
            },
            getTeamTypes() {
                return this.teamTypeResource.get().then(function (response) {
                    return this.teamTypes = response.body.data;
                }, function (error) {
                    console.log(error);
                    return error;
                });
            },
        },
        ready(){
			this.getTeamTypes();
        }
    }
</script>
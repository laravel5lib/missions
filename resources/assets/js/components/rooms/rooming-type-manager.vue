<template>
	<div class="row" style="position: relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="col-md-5 col-md-push-7">
			<!-- Search and Filter -->
			<form class="form-inline row">
				<div class="form-group col-lg-7 col-md-7 col-sm-6 col-xs-12">
					<div class="input-group input-group-sm col-xs-12">
						<input type="text" class="form-control" v-model="roomTypesSearch" debounce="300" placeholder="Search">
						<span class="input-group-addon"><i class="fa fa-search"></i></span>
					</div>
				</div><!-- end col -->
				<div class="form-group col-lg-5 col-md-5 col-sm-6 col-xs-12">
					<button class="btn btn-primary btn-sm btn-block" type="button" @click="createTypeMode">
						Create Room Type
						<i class="fa fa-plus"></i>
					</button>
				</div>
				<div class="col-xs-12">
					<hr class="divider inv">
				</div>
			</form>
			<!-- Room Types Accordion -->
			<div class="panel-group" id="roomTypesAccordion" role="tablist" aria-multiselectable="true">
				<div class="panel panel-default" v-for="(roomType, typeIndex) in roomTypesFiltered">
					<div class="panel-heading" role="tab" id="headingOne">
						<h4 class="panel-title">
							<div class="row">
								<div class="col-xs-9">
									<a role="button" data-toggle="collapse" data-parent="#roomTypesAccordion" :href="'#roomTypeItem' + typeIndex" aria-expanded="true" aria-controls="collapseOne">
										<h4>{{ roomType.name ? roomType.name[0].toUpperCase() + roomType.name.slice(1) : '' }}</h4>
									</a>
								</div>
								<div class="col-xs-3 text-right action-buttons">
									<dropdown type="default">
										<button slot="button" type="button" class="btn btn-xs btn-primary-hollow dropdown-toggle">
											<span class="fa fa-ellipsis-h"></span>
										</button>
										<ul slot="dropdown-menu" class="dropdown-menu dropdown-menu-right">
											<li><a @click="editType(roomType)">Edit</a></li>
											<li><a @click="confirmDelete(roomType)">Delete</a></li>
										</ul>
									</dropdown>
									<a class="btn btn-xs btn-default-hollow" role="button" data-toggle="collapse" data-parent="#roomTypesAccordion" :href="'#roomTypeItem' + typeIndex" aria-expanded="true" aria-controls="collapseOne">
										<i class="fa fa-angle-down"></i>
									</a>
								</div>
							</div>

						</h4>
					</div>
					<div :id="'roomTypeItem' + typeIndex" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
						<div class="panel-body">
							<div class="row">
								<div class="col-sm-6" v-for="(value, key) in roomType.rules">
									<label v-text="key ? (key[0].toUpperCase() + key.slice(1)) | underscoreToSpace(): ''"></label>
									<p class="small" v-text="value"></p>
								</div>
							</div><!-- end row -->
						</div>
					</div>
				</div>
			</div>

			<div class="col-xs-12 text-center">
				<pagination :pagination="roomTypesPagination" :callback="getRoomTypes"></pagination>
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
									<div class="form-group" v-if="key === 'occupancy_limit'">
										<label v-text="key ? (key[0].toUpperCase() + key.slice(1)) | underscoreToSpace(): ''"></label>
										<input type="number" class="form-control" v-model="currentType.rules[key]" :field="key" v-validate="'required'" :value="value" min="0">
									</div>
									<div class="form-group" v-else>
										<label v-text="key ? (key[0].toUpperCase() + key.slice(1)) | underscoreToSpace(): ''"></label>
										<select class="form-control" v-model="currentType.rules[key]" :field="key" v-validate="[]">
											<option :value="true">Yes</option>
											<option :value="false">No</option>
										</select>
									</div>

								</div>
							</template>
						</div>
						<div class="form-group">
							<button class="btn btn-primary btn-sm" type="submit" v-text="editTypeMode ? 'Update Type' : 'Create Type'"></button>
						</div>
					</form>

			</template>

		</div>

		<modal title="Delete Room Type" small ok-text="Delete" :callback="deleteType" :value="showTypeDeleteModal" @closed="showTypeDeleteModal=false">
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
        name: 'room-type-manager',
        mixins: [errorHandler],
        props: ['campaignId'],
        data(){
            return {
                // mixin settings
                validatorHandle: 'TypeForm',

                currentType: null,
                selectedType: null,
                editTypeMode: false,
                showTypeDeleteModal: false,
                roomTypes: [],
                roomTypesPagination: { current_page: 1 },
                roomTypeResource: this.$resource('rooming/types{/id}'),
                roomTypesSearch: ''
            }
        },
        computed: {
            roomTypesFiltered() {
                return this.roomTypes.filter((room) => {
                    return room.name.indexOf(this.roomTypesSearch) !== -1
                });
            }
        },
        methods: {
            newTypeModel() {
                return {
                    name: '',
                    campaign_id: this.campaignId,
	                rules: {
                        occupancy_limit: 4,
                        married_only: false,
                        same_gender: false,
                    }
                }
            },
            editType(type) {
                this.editTypeMode = true;
                this.currentType = type;
                this.currentType.rules = type.rules;
            },
            createTypeMode() {
                this.editTypeMode = false;
                this.currentType = this.newTypeModel();
            },
	        updateType() {

                this.resetErrors();
                if (this.$TypeForm.valid) {
                    let updatingObject = _.extend({}, this.currentType);
	                let originalType = _.findWhere(this.roomTypes, { id: this.currentType.id});

                    // check if name changed
                    if (originalType.name === this.currentType.name)
	                    delete updatingObject.name;

                    return this.roomTypeResource.update({ id: updatingObject.id }, updatingObject).then((response) => {
                        _.extend(_.findWhere(this.roomTypes, { id: updatingObject.id}), response.data.data);
                        this.$root.$emit('showSuccess', 'Room Type: ' + response.data.data.name + ', successfully updated');
                    }, function (response) {
                        this.$root.$emit('showError', response.data.message);
                    });
                }

	        },
	        createType() {

                this.resetErrors();
                if (this.$TypeForm.valid) {
                    return this.roomTypeResource.post(this.currentType).then((response) => {
                        this.roomTypes.push(response.data.data);
                        this.$root.$emit('showSuccess', 'Room Type: ' + response.data.data.name + ', successfully created');
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
                this.roomTypeResource
	                .delete({ id: this.selectedType.id})
	                .then((response) => {
	                    this.roomTypes = _.reject(this.roomTypes, function (type) {
		                    return type.id === this.selectedType.id
                        }.bind(this));
                        this.showTypeDeleteModal = true;
                        this.$root.$emit('showInfo', 'Room Type: ' + this.selectedType.name + ', successfully deleted');
                        this.$nextTick(function () {
                            this.selectedType = null;
                        });
                    }, function (response) {
                        this.$root.$emit('showError', response.data.message);
                    });
            },
            getRoomTypes() {
                return this.roomTypeResource.get({campaign: this.campaignId, page: this.roomTypesPagination.current_page }).then((response) => {
                    this.roomTypesPagination = response.data.meta.pagination;
                    return this.roomTypes = response.data.data;
                }, function (error) {
                    console.log(error);
                    return error;
                });
            },
        },
        mounted(){
			this.getRoomTypes();
        }
    }
</script>
<template >
	<div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
				<div class="col-xs-6">
					<h5>Facilitators</h5>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#AddFacilitatorModal">
						<i class="fa fa-plus icon-left"></i> Add
					</button>
				</div>
				</div>
			</div><!-- end panel-heading -->
			<div class="panel-body" style="position:relative">
				<spinner ref="spinner" size="sm" text="Loading"></spinner>
				<div class="col-sm-6 col-xs-12 panel panel-default" v-for="facilitator in facilitators" :keyy="facilitator.id">
						<h5>
						<img :src="facilitator.avatar + '?w=50&h=50'" class="img-circle av-left" width="50" height="50" :alt=" facilitator.name ">
						{{ facilitator.name }}
						</h5>
						<div style="position:absolute;right:25px;top:22px;">
						<a @click="removeFacilitator(facilitator)">
								<i class="fa fa-times"></i>
							</a>
						</div>
					</div>
					<hr class="divider inv" />
				</div>

				<!--<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" v-for="facilitator in facilitators" track-by="id">-->
					<!--<div class="panel panel-default">-->
						<!--<img class="img-responsive" :src="facilitator.avatar" :alt=" facilitator.name ">-->
						<!--<div class="panel-body">-->
							<!--<h5 class="text-center" v-text="facilitator.name"></h5>-->
							<!--<p class="text-center">-->
								<!--<a class="btn btn-xs btn-default-hollow" @click="removeFacilitator(facilitator)">-->
									<!--<i class="fa fa-times"></i> Remove-->
								<!--</a>-->
							<!--</p>-->
						<!--</div>&lt;!&ndash; end panel-body &ndash;&gt;-->
					<!--</div>&lt;!&ndash; end panel &ndash;&gt;-->
				<!--</div>-->
			</div><!-- end panel-body -->
		<div class="modal fade" id="AddFacilitatorModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title text-center">Select A Facilitator</h4></div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-12 text-center">

									<form class="form-horizontal" novalidate>
										<div class="form-trip" v-error-handler="{ value: user_id, client: 'user', server: 'user_id' }"><label
												class="col-sm-2 control-label">User</label>
											<div class="col-sm-10">
												<v-select @keydown.enter.prevent=""  class="form-control" id="user" v-model="userObj" :options="users" :on-search="getUsers" label="name" name="user" v-validate="'required'"></v-select>
											</div>
										</div>
									</form>

							</div>
						</div>
						<div class="row">
						<hr class="divider inv">
							<div class="col-sm-12 text-center">
								<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary btn-sm" @click="addFacilitator">Save</button>
							</div>
						</div>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</template>
<script type="text/javascript">
	import vSelect from "vue-select";
    import errorHandler from'../error-handler.mixin';
    export default {
		name: 'admin-trip-facilitators',
		components: {vSelect},
        mixins: [errorHandler],
        props: ['tripId'],
		data: function data() {
			return {
                // mixin settings
                validatorHandle: 'AddFacilitator',

                user_id: null,
				facilitators: [],
				users: [],
				trip: null,
				userObj: null,
				resource: this.$resource('trips{/id}', {include: 'facilitators'}),
//				attemptSubmit: false
			};
		},

		computed: {
			user_id: function user_id() {
				return _.isObject(this.userObj) ? this.userObj.id : null;
			}
		},
		methods: {
			/*checkForError: function errors.has(field) {
				// if user clicked submit button while the field is invalid trigger error styles

				return this.$AddFacilitator[field].invalid && this.attemptSubmit;
			},*/
			getUsers: function getUsers(search, loading) {
				loading(true);
				this.$http.get('users', { params: {search: search} }).then((response) => {
					this.users = response.data.data;
					loading(false);
				});
			},
			addFacilitator: function addFacilitator() {
				// Add Facilitator
                this.$validator.validateAll().then(result => {
                    if (result) {
                        var facilitatorsArr = this.facilitators;
                        facilitatorsArr.push({trip_id: this.tripId, id: this.user_id});
                        this.trip.facilitators = _.pluck(facilitatorsArr, 'id');
                        //this.trip.facilitators = this.facilitators;
                        this.updateTrip();
                    }
                });
			},
			removeFacilitator: function removeFacilitator(facilitator) {
				// Remove Facilitator
				this.facilitators.$remove(facilitator);
				var facilitatorsArr = this.facilitators;
				this.trip.facilitators = _.pluck(facilitatorsArr, 'id');
				this.updateTrip();
			},
			updateTrip: function updateTrip() {
				delete this.trip.rep_id;
				this.trip.difficulty = this.trip.difficulty.split(' ').join('_');
				// Update Trip
				this.resource.put({id: this.tripId}, this.trip).then((response) => {
					this.trip = response.data.data;
					this.facilitators = this.trip.facilitators.data;

					this.user_id = null;
					this.userObj = null;
					this.attemptSubmit = false;
					$('#AddFacilitatorModal').modal('hide');
				}, (error) =>  {
                    this.errors = error.data.errors;
                    console.log(error);
				});
			}
		},
		mounted() {
			this.resource.get({id: this.tripId}).then((response) => {
				this.trip = response.data.data;
				this.facilitators = this.trip.facilitators.data;
				//                $.extend(this.$data, response.data.data);
			}, (response) =>  {
				console.log('Update Failed! :(');
				console.log(response);
			});
		}
	};
</script>

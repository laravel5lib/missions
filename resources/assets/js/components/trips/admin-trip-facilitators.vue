<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"> Facilitators
				<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#AddFacilitatorModal"><span
						class="fa fa-plus"></span> New
				</button>
			</h3>
		</div>
		<div>
			<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3" v-for="facilitator in facilitators" track-by="id">
				<div class="thumbnail">
					<img src="http://lorempixel.com/300/300" alt="">
					<div class="caption">
						<h3>{{ facilitator.name }}</h3>
						<p>
							Content...
						</p>
						<p>
							<a class="btn btn-xs btn-danger" @click="removeFacilitator(facilitator)">
								<i class="fa fa-times"></i> Remove
							</a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade" id="AddFacilitatorModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">Select A Facilitator</h4></div>
					<div class="modal-body">
						<validator name="AddFacilitator">
							<form class="form-horizontal" novalidate>
								<div class="form-trip" :class="{ 'has-error': checkForError('user') }"><label
										class="col-sm-2 control-label">User</label>
									<div class="col-sm-10">
										<v-select class="form-controls" id="user" :value.sync="userObj" :options="users"
												  :on-search="getUsers" label="name"></v-select>
										<select hidden="" v-model="user_id" v-validate:user="{ required: true}">
											<option :value="user.id" v-for="user in users">{{user.name}}</option>
										</select>
									</div>
								</div>
							</form>
						</validator>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm" @click="addFacilitator()">Save</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
	</div>
</template>
<script>
	import vSelect from "vue-select";
	export default {
		name: 'admin-trip-facilitators',
		components: {vSelect},
		props: ['tripId'],
		data: function data() {
			return {
				user_id: null,
				facilitators: [],
				users: [],
				trip: null,
				userObj: null,
				resource: this.$resource('trips{/id}', {include: 'facilitators.user'}),
				attemptSubmit: false
			};
		},

		computed: {
			user_id: function user_id() {
				return _.isObject(this.userObj) ? this.userObj.id : null;
			}
		},
		methods: {
			checkForError: function checkForError(field) {
				// if user clicked submit button while the field is invalid trigger error styles

				return this.$AddFacilitator[field].invalid && this.attemptSubmit;
			},
			getUsers: function getUsers(search, loading) {
				loading(true);
				this.$http.get('users', {search: search}).then(function (response) {
					this.users = response.data.data;
					loading(false);
				});
			},
			addFacilitator: function addFacilitator() {
				// Add Facilitator
				this.attemptSubmit = true;
				if (this.$AddFacilitator.valid) {
					this.facilitators.push({trip_id: this.tripId, user_id: this.user_id});
					this.trip.facilitators = _.pluck(this.facilitators, 'user_id');
					//this.trip.facilitators = this.facilitators;
					this.updateTrip();
				}
			},
			removeFacilitator: function removeFacilitator(facilitator) {
				// Remove Facilitator
				this.facilitators.$remove(facilitator);
				this.trip.facilitators = this.facilitators;
				this.updateTrip();
			},
			updateTrip: function updateTrip() {
				delete this.trip.rep_id;
				// Update Trip
				this.resource.update({id: this.tripId}, this.trip).then(function (response) {
					this.trip = response.data.data;
					this.user_id = null;
					this.userObj = null;
					this.attemptSubmit = false;
					$('#AddFacilitatorModal').modal('hide');
				}, function (response) {
					console.log(response);
				});
			}
		},
		ready: function ready() {
			this.resource.get({id: this.tripId}).then(function (response) {
				this.trip = response.data.data;
				this.facilitators = this.trip.facilitators.data;
				//                $.extend(this.$data, response.data.data);
			}, function (response) {
				console.log('Update Failed! :(');
				console.log(response);
			});
		}
	};
</script>

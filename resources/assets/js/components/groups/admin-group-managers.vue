<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="panel panel-default">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-6">
					<h5>Managers</h5>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#AddManagerModal"><span
							class="fa fa-plus"></span> New
					</button>
				</div>
			</div>
		</div>
		<div class="panel-body">
			<div class="col-xs-12 panel panel-default" v-for="manager in managers" track-by="id">
				<h5>
					<img :src="manager.avatar + '?w=50&h=50'" class="img-circle av-left" width="50" height="50" alt="{{ manager.name }}">
					{{ manager.name }}
				</h5>
				<div style="position:absolute;right:25px;top:22px;">
					<a @click="removeManager(manager)">
						<i class="fa fa-times"></i>
					</a>
				</div>
			</div>
		</div>
		<div class="modal fade" id="AddManagerModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">Add Manager</h4></div>
					<div class="modal-body">
						<validator name="AddManager">
							<form class="form-horizontal" novalidate>
								<div class="form-group" v-error-handler="{ value: user_id, client: 'user', server: 'user_id' }"><label
										class="col-sm-2 control-label">User</label>
									<div class="col-sm-10">
										<v-select class="form-control" id="user" :value.sync="userObj" :options="users"
												  :on-search="getUsers" label="name"></v-select>
										<select hidden="" v-model="user_id" v-validate:user="{ required: true}">
											<option :value="user.id" v-for="user in users">{{user.name}}</option>
										</select></div>
								</div>
							</form>
						</validator>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm" @click="addManager()">Save</button>
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
		name: 'admin-group-managers',
		components: {vSelect},
        mixins: [errorHandler],
        props: ['groupId'],
		data: function data() {
			return {
				user_id: null,
				managers: [],
				users: [],
				group: null,
				userObj: null,
				resource: this.$resource('groups{/id}', {include: 'managers'}),
//				attemptSubmit: false,
                // mixin settings
                validatorHandle: 'AddManager',
            };
		},

		computed: {
			user_id: function user_id() {
				return _.isObject(this.userObj) ? this.userObj.id : null;
			}
		},
		methods: {
			/*checkForError: function checkForError(field) {
				// if user clicked submit button while the field is invalid trigger error styles

				return this.$AddManager[field].invalid && this.attemptSubmit;
			},*/
			getUsers: function getUsers(search, loading) {
				loading(true);
				this.$http.get('users', { params: {search: search} }).then(function (response) {
					this.users = response.body.data;
					loading(false);
				});
			},
			addManager: function addManager() {
				// Add Manager
				this.resetErrors();
				if (this.$AddManager.valid) {
					let managersArr = this.managers;
					managersArr.push({id: this.user_id});
					this.group.managers = _.pluck(managersArr, 'id');
					this.updateGroup();
				}
			},
			removeManager: function removeManager(manager) {
				// Remove Manager
				this.managers.$remove(manager);
				let managersArr = this.managers;
				this.group.managers = _.pluck(managersArr, 'id');
				this.updateGroup();
			},
			updateGroup: function updateGroup() {
				// Update Group
				// this.$refs.spinner.show();
				this.resource.update({id: this.groupId}, this.group).then(function (response) {
					this.group = response.body.data;
					this.managers = this.group.managers.data;
					this.user_id = null;
					this.userObj = null;
					this.attemptSubmit = false;
					$('#AddManagerModal').modal('hide');
					// this.$refs.spinner.hide();
				}, function (response) {
                    this.errors = error.data.errors;
                    console.log(response);
					// this.$refs.spinner.hide();
					//TODO add error alert
				});
			}
		},
		ready: function ready() {
			// this.$refs.spinner.show();
			this.resource.get({id: this.groupId}).then(function (response) {
				this.group = response.body.data;
				this.managers = this.group.managers.data;
				//                $.extend(this.$data, response.body.data);
				// this.$refs.spinner.hide();
			}, function (response) {
				console.log('Update Failed! :(');
				console.log(response);
				// this.$refs.spinner.hide();
				//TODO add error alert
			});
		}
	};
</script>

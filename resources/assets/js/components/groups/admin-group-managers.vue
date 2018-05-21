<template >
<div>
	<div class="panel panel-default">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="panel-heading">
			<div class="row">
				<div class="col-xs-6">
					<h5>Team Coordinators</h5>
				</div>
				<div class="col-xs-6 text-right">
					<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#AddManagerModal"><span
							class="fa fa-plus"></span> Add Coordinator
					</button>
				</div>
			</div>
		</div>
		<div class="table-responsive">
		<table class="table">
			<thead>
				<tr class="active">
					<th>#</th>
					<th>Name</th>
					<th>Email</th>
					<th>Phone</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="(manager, index) in managers" :key="manager.id">
					<td>{{ index+1 }}</td>
					<td>{{ manager.name }}</td>
					<td>{{ manager.email }}</td>
					<td>{{ manager.phone_one }}</td>
					<td><a @click="removeManager(manager)"><i class="fa fa-times"></i></a></td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>

		<div class="modal fade" id="AddManagerModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h4 class="modal-title">Add Manager</h4></div>
					<div class="modal-body">

							<form class="form-horizontal" novalidate data-vv-scope="manager-add">
								<div class="form-group" v-error-handler="{ value: user_id, client: 'user', server: 'user_id', scope: 'manager-add' }"><label
										class="col-sm-2 control-label">User</label>
									<div class="col-sm-10">
										<v-select @keydown.enter.prevent=""  class="form-control" id="user" v-model="userObj" :options="users"
												  :on-search="getUsers" label="name" name="user" v-validate="'required'"></v-select>
									</div>
								</div>
							</form>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary btn-sm" @click="addManager">Save</button>
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
		data() {
			return {
//				user_id: null,
				managers: [],
				users: [],
				group: null,
				userObj: null,
				resource: this.$resource('groups{/id}', {include: 'managers'}),
			}
		},
		computed: {
			user_id: {
			    get() {
                    return _.isObject(this.userObj) ? this.userObj.id : null;
                },
				set() {}
			}
		},
		methods: {
			getUsers(search, loading) {
				loading(true);
				this.$http.get('users', { params: {search: search} }).then((response) => {
					this.users = response.data.data;
					loading(false);
				});
			},
			addManager() {
				// Add Manager
				this.$validator.validateAll('manager-add').then(result => {
                    if (result) {
                        let managersArr = this.managers;
                        managersArr.push({id: this.user_id});
                        this.group.managers = _.pluck(managersArr, 'id');
                        this.updateGroup();
                    }
				})

			},
			removeManager(manager) {
				// Remove Manager
				this.managers = _.reject(this.managers, (m) => m.id === manager.id);
				let managersArr = this.managers;
				this.group.managers = _.pluck(managersArr, 'id');
				this.updateGroup();
			},
			updateGroup() {
				// Update Group
				// this.$refs.spinner.show();
				this.resource.put({id: this.groupId}, this.group).then((response) => {
					this.group = response.data.data;
					this.managers = this.group.managers.data;
					this.user_id = null;
					this.userObj = null;
					this.attemptSubmit = false;
					$('#AddManagerModal').modal('hide');
					// this.$refs.spinner.hide();
				}, (response) =>  {
                    this.SERVER_ERRORS = response.response.data.errors;
                    console.log(response);
					// this.$refs.spinner.hide();
					//TODO add error alert
				});
			}
		},
		mounted() {
			// this.$refs.spinner.show();
			this.resource.get({id: this.groupId}).then((response) => {
				this.group = response.data.data;
				this.managers = this.group.managers.data;
				//                $.extend(this.$data, response.data.data);
				// this.$refs.spinner.hide();
			}, (response) =>  {
				console.log('Update Failed! :(');
				console.log(response);
				// this.$refs.spinner.hide();
				//TODO add error alert
			});
		}
	};
</script>

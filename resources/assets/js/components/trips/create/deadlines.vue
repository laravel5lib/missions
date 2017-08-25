<template >
	<div class="row">
		<div class="col-sm-12">

				<form id="TripDeadlines" class="form-horizontal" novalidate>

					<div class="form-group">
						<label class="col-sm-2 control-label">Deadlines</label>
						<div class="col-sm-10">
							<div class="text-right">
								<button type="button" class="btn btn-sm btn-primary" @click="toggleNewDeadline=!toggleNewDeadline">
									<i class="fa fa-plus"></i> New Deadline
								</button>

							</div>
							<hr>

							<div class="panel panel-default" v-if="toggleNewDeadline">
								<div class="panel-heading">
									New Deadline
								</div>
								<div class="panel-body">

										<form class="form" novalidate>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group" :class="{'has-error': errors.has('item')}">
														<label for="name">Name</label>
														<input type="text" id="name" v-model="newDeadline.name" class="form-control input-sm">
													</div>

													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('grace') }">
																<label for="grace_period">Grace Period</label>
																<div class="input-group input-group-sm" :class="{'has-error': errors.hasPayment('grace') }">
																	<input id="grace_period" type="number" class="form-control" number v-model="newDeadline.grace_period"
																		   name="grace" v-validate="'required|min:0'">
																	<span class="input-group-addon">Days</span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('due')}">
																<label for="due_at">Due</label>
																<date-picker input-sm v-model="newDeadline.due_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
																<input type="datetime" id="due_at" class="form-control input-sm hidden"
																	   v-model="newDeadline.due_at" name="due" v-validate="'required'">
															</div>

														</div>
													</div>

													<br>
													<div class="checkbox">
														<label>
															<input type="checkbox" v-model="newDeadline.enforced">
															Enforced?
														</label>
													</div>
												</div>
											</div>
										</form>

								</div>
								<div class="panel-footer text-right">
									<a class="btn btn-xs btn-default" @click="toggleNewDeadline=false">
										<i class="fa fa-times"></i> Cancel
									</a>
									<button type="button" class="btn btn-xs btn-success" @click="addDeadline()">
										<i class="fa fa-plus"></i> Add Deadline
									</button>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
								<tr>
									<th>Name</th>
									<th>Due</th>
									<th>Grace</th>
									<th>Enforced</th>
									<th>Actions</th>
								</tr>
								</thead>
								<tbody>
								<tr v-for="deadline in orderByProp(deadlines, 'due_at')">
									<td>{{deadline.name}}</td>
									<td>{{deadline.due_at|moment}}</td>
									<td>{{deadline.grace_period}} {{pluralize(deadline.grace_period, 'day')}}</td>
									<td>{{deadline.enforced}}</td>
									<td>
										<!--<a @click="editPayment(payment, cost)"><i class="fa fa-pencil"></i></a>-->
										<a @click="deadlines.$remove(deadline)"><i class="fa fa-times"></i></a>
									</td>
								</tr>
								</tbody>
							</table>
						</div>
					</div>
				</form>

		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'trip-deadlines',
		data(){
			return {
				toggleNewDeadline: false,
				attemptedAddDeadline: false,
				attemptedContinue: false,

				// deadlines data
				todos: [],
				deadlines: [],
				newDeadline: {
					name: '',
					date: null,
					grace_period: 0,
					enforced: false,
				}
			}
		},
		computed: {

		},
		methods: {
			populateWizardData(){
				$.extend(this.$parent.wizardData, {
					todos: this.todos,
					deadlines: this.deadlines
				});
			},
			onValid(){
				this.populateWizardData();
				this.$emit('deadlines', true);
			},
			resetDeadline(){
				this.newDeadline = {
					item: '',
					item_type: '',
					due_at: null,
					grace_period: 0,
					enforced: false,
				};
			},
			addDeadline(){
				this.attemptedAddDeadline = true;
                this.$validator.validateAll().then(result => {
                    if (result) {
                        this.deadlines.push(this.newDeadline);
                        this.resetDeadline();
                        this.toggleNewDeadline = false;
                        this.attemptedAddDeadline = false;
                    }
                });
			},
		},
		activated(){
			$('html, body').animate({scrollTop: 0}, 300);
			this.$emit('deadlines', true);
		}
	}
</script>
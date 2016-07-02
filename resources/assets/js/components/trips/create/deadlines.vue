<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripDeadlines">
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
									<validator name="TripDeadlinesCreate">
										<form class="form" novalidate>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group" :class="{'has-error': checkForError('item')}">
														<label for="name">Name</label>
														<input type="text" id="name" v-model="newDeadline.name" class="form-control input-sm">
													</div>

													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': checkForError('grace') }">
																<label for="grace_period">Grace Period</label>
																<div class="input-group input-group-sm" :class="{'has-error': checkForErrorPayment('grace') }">
																	<input id="grace_period" type="number" class="form-control" number v-model="newDeadline.grace_period"
																		   v-validate:grace="{required: true, min:0}">
																	<span class="input-group-addon">Days</span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': checkForError('due')}">
																<label for="due_at">Due</label>
																<input type="date" id="due_at" class="form-control input-sm"
																	   v-model="newDeadline.due_at" v-validate:due="{required: true}">
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
									</validator>
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
								<tr v-for="deadline in deadlines|orderBy 'due_at'">
									<td>{{deadline.name}}</td>
									<td>{{deadline.due_at|moment}}</td>
									<td>{{deadline.grace_period}} {{deadline.amount_owed|pluralize 'day'}}</td>
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
			</validator>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'trip-deadlines',
		data(){
			return {
				todos: [],
				deadlines: [],
				toggleNewDeadline: false,
				attemptedAddDeadline: false,
				attemptedContinue: false,

				// deadlines data
				rep_id: null,
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
			onValid(){
				this.$dispatch('reqs', true);
				//this.$parent.details = this.details;
			},
			checkForError(field){
				return this.$TripDeadlinesCreate[field.toLowerCase()].invalid && this.attemptedAddDeadline;
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
				if(this.$TripDeadlinesCreate.valid) {
					this.deadlines.push(this.newDeadline);
					this.resetDeadline();
					this.toggleNewDeadline = false;
					this.attemptedAddDeadline = false;
				}
			}
		},
		activate(done){
			$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>
<template >
	<div class="row">
		<div class="col-sm-12">

				<form id="TripReqs" class="form-horizontal" novalidate>

					<div class="form-group">
						<label class="col-sm-2 control-label">Requirements</label>
						<div class="col-sm-10">
							<div class="text-right">
								<button type="button" class="btn btn-sm btn-primary" @click="toggleNewRequirement=!toggleNewRequirement">
									<i class="fa fa-plus"></i> New Requirement
								</button>

							</div>
							<hr>

							<div class="panel panel-default" v-if="toggleNewRequirement">
								<div class="panel-heading">
									New Requirement
								</div>
								<div class="panel-body">

										<form class="form" novalidate>
											<div class="row">
												<div class="col-sm-12">
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('item')}">
																<label for="item">Item</label>
																<select id="item" class="form-control input-sm" v-model="newReq.item" name="item" v-validate="'required'">
																	<option value="">-- select --</option>
																	<option :value="option" v-for="option in resources">{{option}}</option>
																</select>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group">
																<label for="type">Item Type</label>
																<select id="type" class="form-control input-sm" v-model="newReq.item_type">
																	<option value="">-- select --</option>
																</select>
															</div>
														</div>
													</div>

													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('grace') }">
																<label for="grace_period">Grace Period</label>
																<div class="input-group input-group-sm" :class="{'has-error': errors.has('grace') }">
																	<input id="grace_period" type="number" class="form-control" number v-model="newReq.grace_period"
																		   name="grace" v-validate="'required|min:0'">
																	<span class="input-group-addon">Days</span>
																</div>
															</div>
														</div>
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('due')}">
																<label for="due_at">Due</label>
																<date-picker input-sm v-model="newReq.due_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="due" v-validate="'required'"></date-picker>
																<!--<input type="datetime" id="due_at" class="form-control input-sm hidden"
																	   v-model="newReq.due_at">-->
															</div>

														</div>
													</div>

													<br>
													<!--<div class="checkbox">
														<label>
															<input type="checkbox" v-model="newReq.enforced">
															Enforced?
														</label>
													</div>-->
												</div>
											</div>
										</form>

								</div>
								<div class="panel-footer text-right">
									<a class="btn btn-xs btn-default" @click="toggleNewRequirement=false"><i class="fa fa-times"></i> Cancel</a>
									<button type="button" class="btn btn-xs btn-success" @click="addRequirement">
										<i class="fa fa-plus"></i> Add Requirement
									</button>
								</div>
							</div>

							<table class="table table-striped table-hover">
								<thead>
								<tr>
									<th>Item</th>
									<th>Type</th>
									<th>Due</th>
									<th>Grace</th>
									<!--<th>Enforced</th>-->
									<th>Actions</th>
								</tr>
								</thead>
								<tbody>
								<tr v-for="requirement in orderByProp(requirements, 'due_at')">
									<td>{{requirement.item}}</td>
									<td>{{requirement.item_type}}</td>
									<td>
										{{requirement.due_at|moment}}
									</td>
									<td>
										{{requirement.grace_period}} {{pluralize(requirement.amount_owed, 'day')}}
									</td>
									<!--<td>{{requirement.enforced}}</td>-->
									<td>
										<!--<a @click="editPayment(payment, cost)"><i class="fa fa-pencil"></i></a>-->
										<a @click="requirements.$remove(requirement)"><i class="fa fa-times"></i></a>
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
	import $ from 'jquery'
	export default{
		name: 'trip-requirement',
		data(){
			return {
				resources: [
					'Medical Release',
					'Passport',
					'Visa',
					'Referral',
					'Credentials',
					'Minor Release',
					'Immunization',
					'Itinerary',
					'Arrival Designation'
				],
				toggleNewRequirement: false,
				attemptedAddRequirement: false,
				attemptedContinue: false,

				// requirements data
				requirements:[],
				newReq: {
					item: '',
					item_type: '',
					due_at: null,
					grace_period: 0,
					// enforced: false,
				}
			}
		},
		computed: {

		},
		methods: {
			populateWizardData(){
				$.extend(this.$parent.wizardData, {
					requirements: this.requirements,
				});
			},

			onValid(){
				this.populateWizardData();
				this.$emit('reqs', true);
				//this.$parent.details = this.details;
			},
			resetRequirement(){
				this.newReq = {
					item: '',
					item_type: '',
					due_at: null,
					grace_period: 0,
					// enforced: false,
				};
			},
			addRequirement(){
				this.attemptedAddRequirement = true;
				this.$validator.validateAll().then(result => {
                    if(result) {
                        this.requirements.push(this.newReq);
                        this.resetRequirement();
                        this.toggleNewRequirement = false;
                        this.attemptedAddRequirement = false;
                    }
				})
				
			}
		},
		activated(){
			$('html, body').animate({scrollTop: 0}, 300);
			this.$emit('reqs', true);
		}
	}
</script>
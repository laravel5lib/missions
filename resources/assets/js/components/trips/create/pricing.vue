<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="TripPricing">
				<form id="TripPricing" class="form-horizontal" novalidate>
					<div class="text-right">
						<button type="button" class="btn btn-sm btn-primary" @click="toggleNewCost=!toggleNewCost">
							<i class="fa fa-plus"></i> New Cost
						</button>

					</div>
					<hr>

					<div class="form-group">
						<label class="col-sm-2 control-label">Pricing</label>
						<div class="col-sm-10">
							<div class="panel panel-default" v-if="toggleNewCost">
								<div class="panel-heading">
									New Cost
								</div>
								<div class="panel-body">
									<validator name="TripPricingCost">
										<form class="form" novalidate>
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group" :class="{'has-error': checkForErrorCost('costName')}">
														<label for="cost_name">Name</label>
														<input type="text" class="form-control input-sm" id="cost_name"
															   v-model="newCost.name" v-validate:costName="{required: true}"
															   placeholder="Name" autofocus>
													</div>
													<div class="form-group" :class="{'has-error': checkForErrorCost('costDescription')}">
														<label for="cost_description">Description</label>
														<textarea class="form-control input-sm" id="cost_description"
																  v-model="newCost.description" v-validate:costDescription="{required: true, minlength:1}"></textarea>
													</div>
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': checkForErrorCost('costActive')}">
																<label for="newCost_active_at">Active</label>
																<input type="date" id="newCost_active_at" class="form-control input-sm"
																	   v-model="newCost.active_at" v-validate:costActive="{required: true}">
															</div>

														</div>
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': checkForErrorCost('costAmount')}">
																<label for="newCost_amount">Amount</label>
																<div class="input-group input-group-sm">
																	<span class="input-group-addon"><i class="fa fa-usd"></i></span>
																	<input type="number" number id="newCost_amount" class="form-control"
																		   v-model="newCost.amount" v-validate:costAmount="{required: true, min: 1}">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>
									</validator>
								</div>
								<div class="panel-footer text-right">
									<a class="btn btn-xs btn-default" @click="toggleNewCost=false"><i class="fa fa-times"></i> Cancel</a>
									<button type="button" class="btn btn-xs btn-success" @click="addCost()"><i
											class="fa fa-plus"></i> Add Cost
									</button>
								</div>
							</div>
							<div class="panel panel-default" v-for="cost in costs">
								<div class="panel-heading">{{cost.name}}</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											{{cost.description}}
										</div>
										<div class="col-sm-6">
											<ul class="list-unstyled">
												<li>{{cost.active_at|moment}}</li>
												<li>{{cost.amount|currency}}</li>
											</ul>
										</div>
									</div>
								</div>
								<table class="table table-striped table-hover">
									<thead>
									<tr>
										<th>Amount</th>
										<th>Percent</th>
										<th>Due</th>
										<th>Upfront</th>
										<th>Grace</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									<tr v-for="payment in cost.payments">
										<td>{{payment.amount_owed|currency}}</td>
										<td>{{payment.percent_owed}}%</td>
										<td>
											<span v-if="payment.due_at">{{payment.due_at|moment}}</span>
											<span v-else>None</span>
										</td>
										<td>{{payment.upfront}}</td>
										<td>{{payment.grace_period}} {{payment.amount_owed|pluralize 'day'}}</td>
										<td>
											<a @click="editPayment(payment, cost)"><i class="fa fa-pencil"></i></a>
											<a @click="cost.payments.$remove(payment)"><i class="fa fa-times"></i></a>
										</td>
									</tr>
									</tbody>
								</table>
								<ul class="list-group">
									<li class="list-group-item" v-for="payment in cost.payments">
										{{payment|json}}
									</li>
									<li class="list-group-item" v-if="(editPaymentMode && cost.toggleNewPayment) || toggleNewPayment">
										<validator name="TripPricingCostPayment">
											<form class="form-inline" novalidate>
												<div class="row">
													<div class="col-sm-12">
														<label for="amountOwed">Owed</label>
													</div>
													<div class="col-sm-6">
														<div class="input-group input-group-sm" :class="{'has-error': checkForErrorPayment('amount') }">
															<span class="input-group-addon"><i class="fa fa-usd"></i></span>
															<input id="amountOwed" class="form-control" type="number" :max="calculateMaxAmount(cost)" number v-model="newPayment.amount_owed"
															   v-validate:amount="{required: true, min: 0.01}" debounce="100">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="input-group input-group-sm" :class="{'has-error': checkForErrorPayment('percent') }">
															<input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(cost)" v-model="newPayment.percent_owed"
																   v-validate:percent="{required: true, min: 0.01}" debounce="100">
															<span class="input-group-addon"><i class="fa fa-percent"></i></span>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-6">
														<div class="form-group">
															<label for="dueAt">Due</label>
															<input id="dueAt" class="form-control input-sm" type="date" v-model="newPayment.due_at">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group" :class="{'has-error': checkForErrorPayment('grace') }">
															<label for="grace_period">Grace Period</label>
															<div class="input-group input-group-sm" :class="{'has-error': checkForErrorPayment('grace') }">
																<input id="grace_period" type="number" class="form-control" number v-model="newPayment.grace_period"
																	   v-validate:grace="{required: true, min:0}">
																<span class="input-group-addon">Days</span>
															</div>

														</div>
													</div>
												</div>
												<div class="checkbox">
													<label>
														<input type="checkbox" v-model="newPayment.upfront">
														Due upfront?
													</label>
												</div>
												<div class="row">
													<div class="col-sm-12">
														<a class="btn btn-xs btn-default" @click="toggleNewPayment=false"><i class="fa fa-times"></i> Cancel</a>
														<a class="btn btn-xs btn-success" v-if="!editPaymentMode" @click="addPayment(cost)"><i class="fa fa-plus"></i> Add Payment</a>
														<a class="btn btn-xs btn-info" v-if="editPaymentMode" @click="updatePayment(cost)"><i class="fa fa-plus"></i> Update Payment</a>
													</div>
												</div>
											</form>
										</validator>
									</li>
								</ul>
								<div class="panel-footer text-right">
									<a v-if="calculateMaxAmount(cost) > 0" @click="toggleNewPaymentForm(cost)" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> New Payment</a>
								</div>
							</div>

						</div>
					</div>
					</form>
			</validator>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'trip-pricing',
		data(){
			return {
				costs: [
					{
						id: "abcs",
						"name": "Cost A",
						"description": "Lorem Ipsum",
						"active_at": "2016-06-30",
						"amount": "220",
						"type": "",
						"toggleNewPayment": false,
						"payments": [
							{ id:"mwhi", "amount_owed": 22, "percent_owed": 10, "due_at": null, "upfront": false, "grace_period": 0 }
						],
					}
				],
				attemptedContinue: false,
				attemptedAddCost: false,
				attemptedAddPayment: false,
				toggleNewCost: false,
				toggleNewPayment: false,
				selectedCost: null,
				editCostMode: false,
				editPaymentMode: false,

				// pricing data
				spots: null,
				newCost: {
					name: '',
					description: '',
					active_at: null,
					amount: 0,
					type: '',
					payments: [],
					toggleNewPayment: false
				},
				newPayment: {
					amount_owed: 0,
					percent_owed: 0,
					due_at: null,
					upfront: false,
					grace_period: 0,
				}
			}
		},
		watch:{
			'newPayment.amount_owed': function (val, oldVal) {
				var max = this.calculateMaxAmount(this.selectedCost);
				if (val > max)
					this.newPayment.amount_owed = this.selectedCost.amount;
				this.newPayment.percent_owed = (val / this.selectedCost.amount) * 100;
			},
			'newPayment.percent_owed': function (val, oldVal) {
				var max = this.calculateMaxPercent(this.selectedCost);
				if (val > max)
					this.newPayment.percent_owed = max;
				this.newPayment.amount_owed = (val / 100) * this.selectedCost.amount;
			}
		},
		computed: {

		},
		methods: {
			onValid(){
				this.$dispatch('pricing', true);
				//this.$parent.details = this.details;
			},
			checkForError(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripPricing[field.toLowerCase()].invalid && this.attemptedContinue;
			},
			checkForErrorCost(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripPricingCost[field.toLowerCase()].invalid && this.attemptedAddCost;
			},
			checkForErrorPayment(field){
				// if user clicked continue button while the field is invalid trigger error styles
				return this.$TripPricingCostPayment[field.toLowerCase()].invalid && this.attemptedAddPayment;
			},
			toggleNewPaymentForm(cost, updateMode) {
				this.selectedCost = cost; //this.selectedCost != cost ? cost : null;
				if(updateMode) {
					this.selectedCost.toggleNewPayment = !this.selectedCost.toggleNewPayment;
				} else {
					this.toggleNewPayment = !this.toggleNewPayment;
				}
			},
			calculateMaxAmount(cost){
				var max = cost.amount;
				if (cost.payments.length) {
					cost.payments.forEach(function (payment) {
						max -= payment.amount_owed;
					})
				}
				return max;
			},
			calculateMaxPercent(cost){
				var max = 100;
				if (cost.payments.length) {
					cost.payments.forEach(function (payment) {
						max -= payment.percent_owed;
					})
				}

				return max;
			},
			editCost(cost){
				this.editCostMode = true;
				this.toggleNewCost = true;
				this.newCost = cost;
			},
			editPayment(payment, cost){
				this.editPaymentMode = true;
				this.toggleNewPaymentForm(cost, true);
				this.newPayment = payment;
			},
			addCost(){
				this.attemptedAddCost = true;
				if (this.$TripPricingCost.valid) {
					this.costs.push(this.newCost);
					this.newCost = {
						id: this.generateUUID(), // used for tracking only
						name: '',
						description: '',
						active_at: null,
						amount: 0,
						type: '',
						payments: [],
						toggleNewPayment: false
					};
					this.toggleNewCost = false;
					this.attemptedAddCost = false;
				}
			},
			updateCost(){
				this.attemptedAddCost = true;
				if (this.$TripPricingCost.valid) {
					this.newCost = {
						id: this.generateUUID(), // used for tracking only
						name: '',
						description: '',
						active_at: null,
						amount: 0,
						type: '',
						payments: [],
						toggleNewPayment: false
					};
					this.toggleNewCost = false;
					this.attemptedAddCost = false;
					this.editCostMode = false;
				}
			},
			addPayment(cost){
				this.attemptedAddPayment = true;
				if(this.$TripPricingCostPayment.valid) {
					cost.payments.push(this.newPayment);
					this.newPayment = {
						id: this.generateUUID(), // used for tracking only
						amount_owed: 0,
						percent_owed: 0,
						due_at: null,
						upfront: false,
						grace_period: 0
					};
					this.toggleNewPayment = false;
					this.attemptedAddPayment = false;
				}
			},
			updatePayment(cost){
				this.attemptedAddPayment = true;
				if(this.$TripPricingCostPayment.valid) {
					this.newPayment = {
						amount_owed: 0,
						percent_owed: 0,
						due_at: null,
						upfront: false,
						grace_period: 0
					};
					this.selectedCost.toggleNewPayment = false;
					this.attemptedAddPayment = false;
					this.editPaymentMode = false;
				}
			},
			generateUUID() {
				return ("0000" + (Math.random()*Math.pow(36,4) << 0).toString(36)).slice(-4);
			}
		},
		activate(done){
			$('html, body').animate({scrollTop: 0}, 300);
			done();
		}
	}
</script>

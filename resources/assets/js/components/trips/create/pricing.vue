<template >
	<div class="row">
		<div class="col-sm-12">

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

										<form class="form" novalidate data-vv-scope="trip-pricing-cost">
											<div class="row">
												<div class="col-sm-12">
													<div class="form-group" :class="{'has-error': errors.has('costName', 'trip-pricing-cost')}">
														<label for="cost_name">Name</label>
														<input type="text" class="form-control input-sm" id="cost_name"
															   v-model="newCost.name" name="costName" v-validate="'required'"
															   placeholder="Name" autofocus>
													</div>
													<div class="form-group" :class="{'has-error': errors.has('costDescription', 'trip-pricing-cost')}">
														<label for="cost_description">Description</label>
														<textarea class="form-control input-sm" id="cost_description"
																  v-model="newCost.description" name="costDescription" v-validate="'required|min:1'"></textarea>
													</div>
													<div class="form-group" :class="{'has-error': errors.has('costType', 'trip-pricing-cost')}">
														<label for="cost_type">Type</label>
														<select id="cost_type" class="form-control input-sm" v-model="newCost.type" name="costType" v-validate="'required'">
															<option value="">-- select --</option>
															<option value="static">Static</option>
															<option value="incremental">Incremental</option>
															<option value="optional">Optional</option>
														</select>
													</div>
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('costActive', 'trip-pricing-cost')}">
																<label for="newCost_active_at">Active</label>
																<date-picker input-sm v-model="newCost.active_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="costActive" v-validate="'required'"></date-picker>
																<!--<input type="datetime" id="newCost_active_at" class="form-control input-sm hidden"
																	   v-model="newCost.active_at">-->
															</div>

														</div>
														<div class="col-sm-6">
															<div class="form-group" :class="{'has-error': errors.has('costAmount', 'trip-pricing-cost')}">
																<label for="newCost_amount">Amount</label>
																<div class="input-group input-group-sm">
																	<span class="input-group-addon"><i class="fa fa-usd"></i></span>
																	<input type="number" number id="newCost_amount" class="form-control"
																		   v-model="newCost.amount" name="costAmount" v-validate="'required|min:1'">
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</form>

								</div>
								<div class="panel-footer text-right">
									<a class="btn btn-xs btn-default" @click="toggleNewCost=false"><i class="fa fa-times"></i> Cancel</a>
									<button type="button" class="btn btn-xs btn-success" @click="addCost">
										<i class="fa fa-plus"></i> Add Cost
									</button>
								</div>
							</div>
							<div class="panel panel-default" v-for="(cost, costIndex) in costs" :class="{ 'panel-warning': costsErrors[costIndex] != false, 'panel-success': costsErrors[costIndex] === false }">
								<div class="panel-heading">{{cost.name}}</div>
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											{{cost.description}}
										</div>
										<div class="col-sm-6">
											<ul class="list-unstyled">
												<li>{{ cost.type|capitalize }}</li>
												<li>{{cost.active_at|moment()}}</li>
												<li>{{currency(cost.amount) }}</li>
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
										<th>Grace</th>
										<th>Actions</th>
									</tr>
									</thead>
									<tbody>
									<tr v-for="payment in orderByProp(cost.payments, 'due_at')">
										<td>{{currency(payment.amount_owed)}}</td>
										<td>{{payment.percent_owed.toFixed(2)}}%</td>
										<td>
											<span v-if="payment.upfront">Upfront</span>
											<span v-else>
												<span v-if="payment.due_at">{{payment.due_at|moment}}</span>
												<span v-else>None</span>
											</span>

										</td>
										<td>
											<span v-if="payment.upfront">N/A</span>
											<span v-else>
												{{payment.grace_period}} {{pluralize(payment.amount_owed, 'day')}}
											</span>
										</td>
										<td>
											<a @click="editPayment(payment, cost)"><i class="fa fa-pencil"></i></a>
											<a @click="cost.payments.$remove(payment)"><i class="fa fa-times"></i></a>
										</td>
									</tr>
									<tr v-if="costsErrors[$index] != false" class="danger">
										<td colspan="5" v-if="costsErrors[$index] === 'empty'">
											<b>At least 1 payment is required!</b>
										</td>
										<td colspan="5" v-else>
											<b>Payments must total the cost amount!</b>
										</td>
									</tr>
									</tbody>
								</table>
								<ul class="list-group">
									<li class="list-group-item" v-if="cost.toggleNewPayment">

											<form class="form-inline" data-vv-scope="trip-pricing-cost-payment">
												<div class="row">
													<div class="col-sm-12">
														<label for="amountOwed">Owed</label>
													</div>
													<div class="col-sm-6">
														<div class="input-group input-group-sm" :class="{'has-error': errors.has('amount', 'trip-pricing-cost-payment') }">
															<span class="input-group-addon"><i class="fa fa-usd"></i></span>
															<input id="amountOwed" class="form-control" type="number" :max="calculateMaxAmount(cost)" number v-model="newPayment.amount_owed"
															   name="amount" debounce="100" v-validate="'required|min_value:0.01'">
														</div>
													</div>
													<div class="col-sm-6">
														<div class="input-group input-group-sm" :class="{'has-error': errors.has('percent', 'trip-pricing-cost-payment') }">
															<input id="percentOwed" class="form-control" type="number" number :max="calculateMaxPercent(cost)" v-model="newPayment.percent_owed.toFixed(2)"
																   name="percent" debounce="100" v-validate="'required|min_value:0.01'">
															<span class="input-group-addon"><i class="fa fa-percent"></i></span>
														</div>
													</div>
												</div>
												<br>
												<div class="checkbox">
													<label>
														<input type="checkbox" v-model="newPayment.upfront">
														Due upfront?
													</label>
												</div>
												<div class="row" v-if="!newPayment.upfront">
													<div class="col-sm-6">
														<div class="form-group">
															<label for="dueAt">Due</label>
															<date-picker input-sm v-model="newPayment.due_at" :view-format="['YYYY-MM-DD HH:mm:ss']"></date-picker>
															<!--<input id="dueAt" class="form-control input-sm hidden" type="datetime" v-model="newPayment.due_at" required>-->
														</div>
													</div>
													<div class="col-sm-6">
														<div class="form-group" :class="{'has-error': errors.has('grace', 'trip-pricing-cost-payment') }">
															<label for="grace_period">Grace Period</label>
															<div class="input-group input-group-sm" :class="{'has-error': errors.has('grace', 'trip-pricing-cost-payment') }">
																<input id="grace_period" type="number" class="form-control" number v-model="newPayment.grace_period"
																	   name="grace" v-validate="'required|min:0'">
																<span class="input-group-addon">Days</span>
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-sm-12" v-if="!editPaymentMode">
														<a class="btn btn-xs btn-default" @click="cost.toggleNewPayment=false"><i class="fa fa-times"></i> Cancel</a>
														<a class="btn btn-xs btn-success" @click="addPayment(cost)"><i class="fa fa-plus"></i> Add Payment</a>
													</div>
													<div class="col-sm-12" v-if="editPaymentMode">
														<a class="btn btn-xs btn-default" @click="cancelEditPayment(cost)"><i class="fa fa-times"></i> Cancel</a>
														<a class="btn btn-xs btn-info" @click="updatePayment(cost)"><i class="fa fa-plus"></i> Update Payment</a>
													</div>
												</div>
											</form>

									</li>
								</ul>
								<div class="panel-footer text-right" v-if="calculateMaxAmount(cost) > 0">
									<a @click="toggleNewPaymentForm(cost)" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> New Payment</a>
								</div>
							</div>

						</div>
					</div>
					</form>

		</div>
	</div>
</template>
<script type="text/javascript">
	import _ from 'underscore';
	import $ from 'jquery';
	export default{
		name: 'trip-pricing',
		data(){
			return {
				attemptedContinue: false,
				attemptedAddCost: false,
				attemptedAddPayment: false,
				toggleNewCost: false,
				toggleNewPayment: false,
				selectedCost: null,
				editCostMode: false,
				editPaymentMode: false,
				costsErrors:[],

				// pricing data
				costs: [],
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
		/*validators:{
			costs
		},*/
		watch:{
			'newPayment.amount_owed'(val, oldVal) {
				var max = this.calculateMaxAmount(this.selectedCost);
				if (val > max)
					this.newPayment.amount_owed = this.selectedCost.amount;
				this.newPayment.percent_owed = (val / this.selectedCost.amount) * 100;
			},
			'newPayment.percent_owed'(val, oldVal) {
				var max = this.calculateMaxPercent(this.selectedCost);
				if (val > max)
					this.newPayment.percent_owed = max;
				this.newPayment.amount_owed = (val / 100) * this.selectedCost.amount;
			},
			'costs'(val, oldVal) {
				this.checkCostsErrors();
			}
		},
		computed: {

		},
		methods: {
			populateWizardData(){
				$.extend(this.$parent.wizardData, {
					costs: this.costs,
				});
			},
			onValid(){

				this.$emit('pricing', true);
				//this.$parent.details = this.details;
			},
			checkCostsErrors(){
				var errors = [];
				this.costs.forEach(function (cost, index) {
					// cost must have at least 1 payment
					if (!cost.payments.length) {
						errors.push('empty');
					} else {
						// cost payments must total full amount owed and percent owed
						var amount = 0;
						cost.payments.forEach(function (payment, index) {
							amount += payment.amount_owed;
						}, this);
						// evaluate difference
						if (amount != cost.amount) {
							errors.push('incomplete');
						}
					}

					// no errors
					errors.push(false);
				}, this);
				this.costsErrors = errors;
			},
			resetCost(){
				this.newCost = {
					name: '',
					description: '',
					active_at: null,
					amount: 0,
					type: '',
					payments: [],
					toggleNewPayment: false
				}
			},
			resetPayment(){
				this.newPayment = {
					amount_owed: 0,
					percent_owed: 0,
					due_at: null,
					upfront: false,
					grace_period: 0,
				}
			},
			calculateMaxAmount(cost){
				var max = cost.amount;
				if (cost.payments.length) {
					cost.payments.forEach(function (payment) {
						// must ignore current payment in editMode
						if(this.newPayment !== payment) {
							max -= payment.amount_owed;
						}
					}, this);
				}
				return max;
			},
			calculateMaxPercent(cost){
				var max = 100;
				if (cost.payments.length) {
					cost.payments.forEach(function (payment) {
						// must ignore current payment in editMode
						if(this.newPayment !== payment) {
							max -= payment.percent_owed;
						}
					}, this);
				}
				return max;
			},
			editCost(cost){
				this.editCostMode = true;
				this.toggleNewCost = true;
				this.newCost = cost;
			},
			cancelEditPayment(){
				this.editPaymentMode = false;
				this.resetCost();
			},
			editPayment(payment, cost){
				this.editPaymentMode = true;
				this.toggleNewPaymentForm(cost, true);
				this.newPayment = payment;
			},
			toggleNewPaymentForm(cost, updateMode) {
				this.selectedCost = cost;
					this.selectedCost.toggleNewPayment = !this.selectedCost.toggleNewPayment;
			},
			addCost(){
				this.attemptedAddCost = true;
                this.$validator.validateAll('trip-pricing-cost').then(result => {
                    if (result) {
                        this.costs.push(this.newCost);
                        this.resetCost();
                        this.toggleNewCost = false;
                        this.attemptedAddCost = false;
                    }
                });
				this.checkCostsErrors();
			},
			updateCost(){
				this.attemptedAddCost = true;
                this.$validator.validateAll('trip-pricing-cost').then(result => {
                    if (result) {
                        this.resetCost();
                        this.toggleNewCost = false;
                        this.attemptedAddCost = false;
                        this.editCostMode = false;
                    }
                });
				this.checkCostsErrors();
			},
			addPayment(cost){
				this.attemptedAddPayment = true;
                this.$validator.validateAll('trip-pricing-cost-payment').then(result => {
                    if (result) {
                        cost.payments.push(this.newPayment);
                        this.resetPayment();
                        this.selectedCost.toggleNewPayment = false;
                        this.attemptedAddPayment = false;
                    }
                });
				this.checkCostsErrors();
			},
			updatePayment(cost){
				this.attemptedAddPayment = true;
                this.$validator.validateAll('trip-pricing-cost-payment').then(result => {
                    if (result) {
                        this.resetPayment();
                        this.selectedCost.toggleNewPayment = false;
                        this.attemptedAddPayment = false;
                        this.editPaymentMode = false;
                    }
                });
				this.checkCostsErrors();
			},
			generateUUID() {
				return ("0000" + (Math.random()*Math.pow(36,4) << 0).toString(36)).slice(-4);
			}
		},
		activated(){
			$('html, body').animate({scrollTop: 0}, 300);
			this.$emit('pricing', true);
		}
	}
</script>

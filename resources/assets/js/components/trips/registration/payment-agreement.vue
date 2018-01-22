<template >
	<div class="row">
		<div class="col-sm-12">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group" :class="{ 'has-error': promoValid === false && promoError, 'has-success': promoValid > 0 }">
						<label for="cardHolderName">Promo Code</label>
						<div class="input-group">
							<span class="input-group-addon"><span class="fa" :class="{'fa-check' : promoValid, 'fa-times' : promoError !== '' && !promoValid, 'fa-gift': !promoValid && promoError === ''}"></span></span>
							<input type="text" class="form-control" id="promo" placeholder=""
							       v-model="promo" />
							<span class="input-group-btn">
						        <button class="btn btn-default" type="button" @click.prevent="checkPromo">Apply</button>
							</span>
						</div>
						<div class="help-block" v-if="promoError" v-text="promoError"></div>
					</div>
				</div>

				<div class="col-md-12">
					<ul class="list-group">
						<li class="list-group-item">
							Item
							<span class="pull-right">Cost</span>
						</li>
						<li class="list-group-item" v-for="cost in staticCosts">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{currency(cost.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed, '$', 'USD')}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item" v-for="cost in incrementalCosts">
							<h5 class="list-group-item-heading">
								{{cost.name}}
								<span class="pull-right">{{currency(cost.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in cost.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed, '$', 'USD')}}</td>
									</tr>
								</tbody>
							</table>
						</li>
						<li class="list-group-item">
							<h5 class="list-group-item-heading">
								{{selectedOptions.name}}
								<span class="pull-right">{{currency(selectedOptions.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{selectedOptions.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
							<table class="table">
								<tbody>
									<tr v-for="p in selectedOptions.payments.data" :class="{'text-danger': p.upfront}">
										<td>{{p.percent_owed}}% is Due {{toDate(p.due_at)}}</td>
										<td class="text-right">{{p.upfront ? '-': ''}}{{currency(p.amount_owed, '$', 'USD')}}</td>
									</tr>
								</tbody>
							</table>
						</li>
					</ul>

					<table class="table table-hover">
						<tfoot>
							<tr>
								<td style="border-top:2px solid #000000;">Fundraising Goal</td>
								<td style="border-top:2px solid #000000;" class="text-right">{{currency(totalCosts, '$', 'USD')}}</td>
							</tr>
							<tr>
								<td>Up-front Charges</td>
								<td class="text-danger text-right">-{{currency(upfrontTotal, '$', 'USD')}}</td>
							</tr>
							<tr v-if="promoValid">
								<td>Promo Credit</td>
								<td class="text-danger text-right">{{-currency(promoValid, '$', 'USD')}}</td>
							</tr>
							<tr>
								<td style="border-top:2px solid #000000;"><h5>Total to Raise</h5></td>
								<td style="border-top:2px solid #000000;"><h5 class="text-success text-right">{{ promoValid ? fundraisingGoal - promoValid : currency(fundraisingGoal, '$', 'USD')}}</h5></td>
							</tr>

						</tfoot>
					</table>
				</div>
				<hr class="divider" />
				<!--<div class="col-sm-12">
					<h4>Cost Deadlines</h4>
					<div class="panel panel-default" v-for="cost in costs.static">
						&lt;!&ndash; Default panel contents &ndash;&gt;
						<div class="panel-heading">
							{{cost.name}}
							<span class="pull-right">{{currency(cost.amount)}}</span>
						</div>
						<div class="panel-body">
							<p>This cost is applied to registrants after {{ toDate(cost.active_at) }}</p>
							<div class="list-group">
								<div class="list-group-item" v-for="payment in cost.payments.data">
									<h4 class="list-group-item-heading">
										Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
									</h4>
									<p class="list-group-item-text">
										The amount of <b>{{currency(payment.amount_owed)}}</b>, {{payment.percent_owed}}&percnt; of the total amount, is due.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default" v-for="cost in costs.incremental">
						&lt;!&ndash; Default panel contents &ndash;&gt;
						<div class="panel-heading">
							{{cost.name}}
							<span class="pull-right">{{currency(cost.amount)}}</span>
						</div>
						<div class="panel-body">
							<p>This cost is applied to registrants after {{ toDate(cost.active_at) }}</p>
							<div class="list-group">
								<div class="list-group-item" v-for="payment in cost.payments.data">
									<h4 class="list-group-item-heading">
										Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
									</h4>
									<p class="list-group-item-text">
										The amount of <b>{{currency(payment.amount_owed)}}</b>, {{payment.percent_owed}}&percnt; of the total amount is due.
										If this amount is not received by the deadline, additional costs may be applied.
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="panel panel-default" v-for="cost in selectedOptionalCosts">
						&lt;!&ndash; Default panel contents &ndash;&gt;
						<div class="panel-heading">
							{{cost.name}}
							<span class="pull-right">{{currency(cost.amount)}}</span>
						</div>
						<div class="panel-body">
							<p>This cost is applied to registrants after {{ toDate(cost.active_at) }}</p>
							<div class="list-group">
								<div class="list-group-item" v-for="payment in cost.payments.data">
									<h4 class="list-group-item-heading">
										Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
									</h4>
									<p class="list-group-item-text">
										The amount of <b>{{currency(payment.amount_owed)}}</b>, {{payment.percent_owed}}&percnt; of the total amount is due.
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<hr>-->
				<div class="col-sm-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="paymentAgree">
							I have read and agree to meet the above financial deadlines and amounts or risk being dropped from the trip. 
						</label>
					</div>
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="noRefundAgree">
							I understand that all donations are considered 501(C)3 tax-deductible donations (not payments for goods or services) and are 100% non-refundable and non-transferable.
						</label>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<style>
	#StripeForm .field {
		display: block;
		height: 34px;
		padding: 8px 6px;
		font-size: 14px;
		color: #555555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-radius: 4px;
		box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
		-webkit-transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
		transition: border-color ease-in-out 0.15s, box-shadow ease-in-out 0.15s;
	}
</style>
<script type="text/javascript">
	export default{
		name: 'payment-agreement',
        props: {
            showButton: {
                'default': true
            },
            callback: {
                required: false
            },
            showLabels: {
                'default': false
            },
            devMode: {
                'default': true
            }
        },
		data(){
			return {
			    handle: 'PaymentAgreement',
				title: 'Payment Agreement',
                paymentAgree: false,
				noRefundAgree: false,
                promo: '',
                promoValid: false,
                promoError: '',
			}
		},
        computed: {
            fundraisingGoal(){
                return this.totalCosts - this.upfrontTotal;
            },
            costs(){
	            return this.$parent.tripCosts || [];
            },
            selectedOptionalCosts(){
                return [this.$parent.selectedOptions] || [];
            },
            staticCosts(){
                return this.$parent.tripCosts.static || [];
            },
            incrementalCosts(){
                return this.$parent.tripCosts.incremental || [];
            },
            selectedOptions(){
                return this.$parent.selectedOptions || null;
            },
            totalCosts(){
                let amount = 0;
                // add static costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
//					this.selectedOptions.forEach(function (cost) {
                    amount += parseFloat(this.selectedOptions.amount);
//					});
                }

                // add incremental costs if they exists
                if (this.incrementalCosts && this.incrementalCosts.constructor === Array) {
                    this.incrementalCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }

                return amount;
            },
            upfrontTotal(){
                let amount = 0;
                // add static costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        cost.payments.data.forEach(function (payment) {
                            if (payment.upfront) {
                                amount += parseFloat(payment.amount_owed);
                            }
                        });

                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
                    //this.selectedOptions.forEach(function (cost) {
                    this.selectedOptions.payments.data.forEach(function (payment) {
                        if (payment.upfront) {
                            amount += parseFloat(payment.amount_owed);
                        }
                    });
                    //});
                }

                // add incremental costs if they exists
                if (this.incrementalCosts && this.incrementalCosts.constructor === Array) {
                    this.incrementalCosts.forEach(function (cost) {
                        cost.payments.data.forEach(function (payment) {
                            if (payment.upfront) {
                                amount += parseFloat(payment.amount_owed);
                            }
                        });
                    });
                }
                this.$parent.upfrontTotal = amount;
                return amount;
            },
        },
		watch: {
          promo(val, oldVal) {
            this.promoError = '';
          },
          paymentAgree(val) {
			  if (this.noRefundAgree) {
            	this.$emit('step-completion', val);
			  }
          },
		  noRefundAgree(val) {
			  if (this.paymentAgree) {
				this.$emit('step-completion', val);
			  }
		  }
		},
        methods: {
          checkPromo(){
            this.$http.post(`trips/${this.$parent.tripId}/promo`, {promocode: this.promo}).then((response) => {
              this.promoValid = parseInt(response.data.replace(/,+/, ''));
              this.$parent.promocode = this.promoValid ? this.promo : null;
            }, (error) => {
              this.promoError = error.message;
              this.promoValid = false;
            });
          },

          toDate(date){
                if(date) {
                    return moment(date).format('LL');
                } else {
                    return 'Now';
                }
            },
        },
        mounted() {},
		activated(){
			$('html, body').animate({scrollTop : 0},300);
		}
	}
</script>

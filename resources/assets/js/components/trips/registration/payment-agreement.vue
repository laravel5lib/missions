<template >
	<div class="row">
		<div class="col-sm-12">
			<h4>Financial Agreement</h4>
			<hr>
			<div class="row">
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
				<div class="col-sm-12">
					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="paymentAgree">
							I have read and agree to the above financial deadlines and amounts.
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
			}
		},
        computed: {
            fundraisingGoal(){
                return this.totalCosts - this.upfrontTotal;
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
          paymentAgree(val) {
            this.$emit('step-completion', val);
          }
		},
        methods: {
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
			$('html, body').animate({scrollTop : 200},300);
		}
	}
</script>

<template >
	<div class="row">
		<div class="col-sm-12">
			<div class="row">

				<div class="col-md-12">
					<ul class="list-group">
						<li class="list-group-item">
							Item
							<span class="pull-right">Cost</span>
						</li>
						<li class="list-group-item" v-for="price in upfrontCosts" :key="price.id">
							<h5 class="list-group-item-heading">
								{{ price.cost.name }}
								<span class="pull-right">{{currency(price.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{price.cost.description}}</p>
							</h5>
						</li>
						<li class="list-group-item" v-for="price in staticCosts" :key="price.id">
							<h5 class="list-group-item-heading">
								{{ price.cost.name }}
								<span class="pull-right">{{currency(price.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{price.cost.description}}</p>
							</h5>
						</li>
						<li class="list-group-item">
							<h5 class="list-group-item-heading">
								{{incrementalCost.cost.name}}
								<span class="pull-right">{{currency(incrementalCost.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{incrementalCost.cost.description}}</p>
							</h5>
						</li>
						<li class="list-group-item">
							<h5 class="list-group-item-heading">
								{{selectedOptions.cost.name}}
								<span class="pull-right">{{currency(selectedOptions.amount, '$', 'USD')}}</span>
								<hr class="divider sm inv">
								<p class="small">{{selectedOptions.cost.description}}</p>
							</h5>
							<p class="list-group-item-text">

							</p>
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
								<td class="text-danger text-right">-{{currency(promoValid, '$', 'USD')}}</td>
							</tr>
							<tr>
								<td style="border-top:2px solid #000000;"><h5>Total to Raise</h5></td>
								<td style="border-top:2px solid #000000;"><h5 class="text-success text-right">{{ currency(promoValid ? fundraisingGoal - promoValid : fundraisingGoal, '$', 'USD')}}</h5></td>
							</tr>

						</tfoot>
					</table>
				</div>
				<hr class="divider" />
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
            incrementalCost(){
                return this.$parent.tripCosts.incremental || [];
            },
			upfrontCosts(){
                return this.$parent.tripCosts.upfront || [];
            },
            selectedOptions(){
                return this.$parent.selectedOptions || null;
            },
            totalCosts(){
                let amount = 0;
                // add upfront costs if they exists
				if(this.upfrontCosts && this.upfrontCosts.constructor === Array) {
                    this.upfrontCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
				// add additional costs if they exists
                if(this.staticCosts && this.staticCosts.constructor === Array) {
                    this.staticCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
                    });
                }
                // add optional costs if they exists
                if (this.selectedOptions && _.isObject(this.selectedOptions)) {
                    amount += parseFloat(this.selectedOptions.amount);
                }
                
				// add the registration cost
				amount += parseFloat(this.incrementalCost.amount);

                return amount;
            },
            upfrontTotal(){
                let amount = 0;
				if(this.upfrontCosts && this.upfrontCosts.constructor === Array) {
                    this.upfrontCosts.forEach(function (cost) {
                        amount += parseFloat(cost.amount);
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
              this.promoValid = parseInt(response.data);
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

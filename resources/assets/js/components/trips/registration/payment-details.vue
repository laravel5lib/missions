<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Payment Details</h4>

        </div>
        <div class="col-sm-12">
            <hr>
        </div>
        <div class="col-sm-12">

        </div>
    </div>
</template>
<script>
	export default{
		name: 'payment-details',
		data(){
			return {
				title: 'Payment Details',
				paymentComplete: true
			}
		},
		watch:{
			'paymentComplete'(val, oldVal) {
				this.$dispatch('payment-complete', val)
			}
		},
		ready(){
		    // until process is added
		    this.$dispatch('payment-complete', true)
		}

	}
</script>

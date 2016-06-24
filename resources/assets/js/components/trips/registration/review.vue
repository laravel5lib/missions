<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Review</h4>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						Basic Traveler Information
					</div>
				</div>
				<div class="panel-body">
					<address>
						<strong>{{userInfo.firstName}} {{userInfo.middleName}} {{userInfo.lastName}}</strong><br>
						{{userInfo.address}}<br>
						{{userInfo.city}}, {{userInfo.state}} {{userInfo.zipCode}}<br>
						{{userInfo.country | uppercase}}<br>
						<br>
						Date of Birth: {{userInfo.dob}}<br>
						Gender: {{userInfo.gender}}<br>
						Relationship Status: {{userInfo.relStatus}}<br>
						Height: {{userInfo.height}}<br>
						Weight: {{userInfo.weight}}<br>
						<br>
						<abbr title="Phone">P:</abbr> {{userInfo.phone}}<br>
						<abbr title="Mobile">M:</abbr> {{userInfo.mobile}}<br>
						<abbr title="Email"><i class="fa fa-envelope"></i></abbr> {{userInfo.email}}<br>
					</address>
				</div>
			</div>

			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						Payment Details
					</div>
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal" v-if="paymentInfo">
						<dt>Card Holder Name</dt>
						<dd>{{paymentInfo.token.card.name}}</dd>
						<dt>Card Number</dt>
						<dd>.... .... .... {{paymentInfo.token.card.last4}}</dd>
						<dt>Card Expiration</dt>
						<dd>{{paymentInfo.token.card.exp_month}} {{paymentInfo.token.card.exp_year}}</dd>
						<dt>Billing Email</dt>
						<dd>{{paymentInfo.email}}</dd>
						<dt>Billing Zip/Postal Code</dt>
						<dd>{{paymentInfo.token.card.zip}}</dd>
						<dt>Save payment method</dt>
						<dd>{{paymentInfo.save ? 'Yes' : 'No'}}</dd>
					</dl>
					<hr>
					<p>Amount to be charged immediately: {{upfrontTotal|currency}}</p>
				</div>
			</div>

		</div>
		<div class="col-sm-12">
			<hr>
		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="review">
					I have reviewed and verified that all information provided is correct.
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'review',
		data(){
			return {
				title: 'Review',
				review: false
			}
		},
		computed:{
			userInfo(){
				return this.$parent.userInfo;
			},
			paymentInfo(){
				return this.$parent.paymentInfo;
			},
			upfrontTotal(){
				return this.$parent.upfrontTotal;
			}
		},
		watch:{
			'review'(val, oldVal) {
				this.$dispatch('review', val)
			}
		}

	}
</script>

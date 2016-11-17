<template>
	<div class="row">
		<div class="col-sm-12">
			<h4>Review</h4>
			<div class="panel panel-primary">
				<div class="panel-heading">
					<div class="panel-title">
						<h5>Basic Traveler Information</h5>
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
						Gender: {{userInfo.gender|capitalize}}<br>
						Relationship Status: {{userInfo.relationshipStatus|capitalize}}<br>
						Height: {{userInfo.height}}<br>
						Weight: {{userInfo.weight}} lbs.<br>
						<br>
						<abbr title="Phone"><span class="fa fa-phone"></span></abbr> {{userInfo.phone}}<br>
						<abbr title="Mobile"><span class="fa fa-mobile"></span></abbr> {{userInfo.mobile}}<br>
						<abbr title="Email"><span class="fa fa-envelope"></span></abbr> {{userInfo.email}}<br>
					</address>
				</div>
			</div>

			<div class="panel panel-primary" v-if="paymentInfo.card">
				<div class="panel-heading">
					<div class="panel-title">
						<h5>Payment Details</h5>
					</div>
				</div>
				<div class="panel-body">
					<dl class="dl-horizontal" v-if="paymentInfo">
						<dt>Card Holder Name</dt>
						<dd>{{paymentInfo.card.cardholder}}</dd>
						<dt>Card Number</dt>
						<dd>&middot;&middot;&middot;&middot; &middot;&middot;&middot;&middot; &middot;&middot;&middot;&middot; {{paymentInfo.card.number.substr(-4)}}</dd>
						<dt>Card Expiration</dt>
						<dd>{{paymentInfo.card.exp_month}}/{{paymentInfo.card.exp_year}}</dd>
						<dt>Billing Email</dt>
						<dd>{{paymentInfo.email}}</dd>
						<dt>Billing Zip</dt>
						<dd>{{paymentInfo.address_zip}}</dd>
						<dt>Save Payment Method</dt>
						<dd>{{paymentInfo.save ? 'Yes' : 'No'}}</dd>
					</dl>
					<hr>
					<p class="list-group-item-text">Amount to be charged immediately: {{upfrontTotal|currency}}</p>
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
<script type="text/javascript">
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

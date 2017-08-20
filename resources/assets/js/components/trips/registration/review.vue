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
						<strong>{{userInfo.firstName}} {{userInfo.lastName}}</strong><br>
						{{userInfo.address}}<br>
						{{userInfo.city}}, {{userInfo.state}} {{userInfo.zipCode}}<br>
						{{userInfo.country_name}}<br>
					</address>
						<hr class="divider">
						<div class="row">
							<div class="col-sm-4">
								<label>Date of Birth</label><p>{{userInfo.dob}}</p>
							</div><!-- end col -->
							<div class="col-sm-4">
								<label>Gender</label><p>{{ userInfo.gender|capitalize }}</p>
							</div><!-- end col -->
							<div class="col-sm-4">
								<label>Relationship Status</label><p>{{ userInfo.relationshipStatus|capitalize }}</p>
							</div><!-- end col -->
						</div><!-- end row -->
						<div class="row">
							<div class="col-sm-4">
								<label>Height</label><p>{{userInfo.heightA}} {{userInfo.heightUnitA}} {{userInfo.heightB}} {{userInfo.heightUnitB}}</p>
							</div><!-- end col -->
							<div class="col-sm-4">
								<label>Weight</label><p>{{userInfo.weight}} {{userInfo.weightUnit}}</p>
							</div><!-- end col -->
							<div class="col-sm-4">
								<label>Role</label><p>{{userInfo.desired_role.name}}</p>
							</div><!-- end col -->
						</div><!-- end row -->
						<hr class="divider">
						<div class="row">
							<div class="col-sm-4">
								<label><i class="fa fa-phone"></i> Phone</label><p>{{userInfo.phone}}</p>
							</div>
							<div class="col-sm-4">
								<label><i class="fa fa-mobile"></i> Mobile</label><p>{{userInfo.mobile}}</p>
							</div>
							<div class="col-sm-4">
								<label><i class="fa fa-envelope"></i> Email</label><p>{{userInfo.email}}</p>
							</div><!-- end col -->
						</div><!-- end row -->
				</div>
			</div>

			<div class="panel panel-primary" v-if="paymentInfo.card">
				<div class="panel-heading">
					<div class="panel-title">
						<h5>Payment Details</h5>
					</div>
				</div><!-- end panel-heading -->
				<div class="panel-body">
					<div class="dl-horizontal" v-if="paymentInfo">
					<div class="row">
						<div class="col-sm-6">
							<label>Card Holder Name</label>
							<p>{{paymentInfo.card.cardholder}}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Card Number</label>
							<p>&middot;&middot;&middot;&middot; &middot;&middot;&middot;&middot; &middot;&middot;&middot;&middot; {{paymentInfo.card.number.substr(-4)}}</p>
						</div><!-- end col -->
					</div><!-- end row -->
					<div class="row">
						<div class="col-sm-6">
							<label>Card Expiration</label>
							<p>{{paymentInfo.card.exp_month}}/{{paymentInfo.card.exp_year}}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Billing Email</label>
							<p>{{paymentInfo.email}}</p>
						</div><!-- end col -->
					</div><!-- end row -->
					<div class="row">
						<div class="col-sm-6">
							<label>Billing Zip</label>
							<p>{{paymentInfo.address_zip}}</p>
						</div><!-- end col -->
						<div class="col-sm-6">
							<label>Save Payment Method</label>
							<p>{{paymentInfo.post ? 'Yes' : 'No'}}</p>
						</div><!-- end col -->
					</div><!-- end row -->
					</div>
					<hr class="divider">
					<p class="list-group-item-text">Amount to be charged immediately: <span class="text-success">{{upfrontTotal|currency}}</span></p>
				</div><!-- end panel-body -->
				<div class="panel-footer text-center">
	                <a href="https://stripe.com/" target="_blank"><span style="font-size:.6em;color:#bcbcbc;text-transform:uppercase;letter-spacing:1px;">Securely</span> <img style="width:90px; height:20px;opacity:.65;" src="/images/powered-by-stripe@2x.png" alt="Powered by Stripe"></a>
	            </div><!-- end panel-footer -->
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
				this.$emit('review', val)
			},
			'$parent.paymentErrors'(val) {
                if (val.length > 0) {
                    this.review = false;
                }
			}
		},
		events: {
		    'review'(val) {
		        this.review = val;
		    }
		},
		activate(done){
			$('html, body').animate({scrollTop : 200},300);
			done();
		}

	}
</script>

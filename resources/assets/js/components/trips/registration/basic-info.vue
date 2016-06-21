<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div class="row">
		<div class="col-sm-12">
			<validator name="BasicInfo" @valid="onValid" @invalid="onInvalid">
				<form novalidate name="BasicInfoForm" id="BasicInfoForm">
					<div class="col-md-6">
						<div class="form-group">
							<label for="infoAddress">Address</label>
							<input type="text" class="form-control input-sm" v-model="address" v-validate:address="{ required: true }" id="infoAddress" placeholder="Street Address">
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="infoCity">City</label>
									<input type="text" class="form-control input-sm" v-model="city" v-validate:city="{ required: true }" id="infoCity" placeholder="">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="infoState">State/Prov.</label>
									<input type="text" class="form-control input-sm" v-model="state" v-validate:state="{ required: true }" id="infoState" placeholder="">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="infoZip">ZIP/Postal Code</label>
									<input type="text" class="form-control input-sm" v-model="zipCode" v-validate:zipCode="{ required: true }" id="infoZip" placeholder="12345">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="infoCountry">Country</label>
									<select class="form-control input-sm" v-model="country" v-validate:country="{ required: true }" id="infoCountry">
										<option value="us" selected>United States</option>
									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="infoPhone">Home Phone</label>
							<input type="text" class="form-control input-sm" v-model="phone | phone" v-validate:phone="{ required: true }" id="infoPhone" placeholder="123-456-7890">
						</div>

						<div class="form-group">
							<label for="infoMobile">Cell Phone</label>
							<input type="text" class="form-control input-sm" v-model="mobile | phone" v-validate:mobile="{ required: false }" id="infoMobile" placeholder="123-456-7890">
						</div>

					</div>

					<div class="col-md-6">
						<label>Full Legal Name</label>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoFirstName">First</label>-->
									<input type="text" class="form-control input-sm" v-model="firstName" v-validate:firstName="{ required: true }" placeholder="First" id="infoFirstName">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoMiddleName">Middle</label>-->
									<input type="text" class="form-control input-sm" v-model="middleName" v-validate:middleName="{ required: true }" placeholder="Middle" id="infoMiddleName">
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoLastName">Last</label>-->
									<input type="text" class="form-control input-sm" v-model="lastName" v-validate:lastName="{ required: true }" placeholder="Last" id="infoLastName">
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="infoEmailAddress">Email Address</label>
							<input type="text" class="form-control input-sm" v-model="email" v-validate:email="['email']" id="infoEmailAddress">
						</div>

						<label>Date of Birth</label>
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoDobMonth">Month</label>-->
									<select class="form-control input-sm" v-model="dobMonth" v-validate:dobMonth="{ required: true }" id="infoDobMonth">
										<option value="">Month</option>
										<option value="01">January</option>
										<option value="02">February</option>
										<option value="03">March</option>
										<option value="04">April</option>
										<option value="05">May</option>
										<option value="06">June</option>
										<option value="07">July</option>
										<option value="08">August</option>
										<option value="09">September</option>
										<option value="10">October</option>
										<option value="11">November</option>
										<option value="12">December</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoDobDay">Day</label>-->
									<select class="form-control input-sm" v-model="dobDay" v-validate:dobDay="{ required: true }" id="infoDobDay">
										<option value="">Day</option>
										<option value="01">01</option>
										<option value="02">02</option>
										<option value="03">03</option>
										<option value="04">04</option>
										<option value="05">05</option>
										<option value="06">06</option>
										<option value="07">07</option>
										<option value="08">08</option>
										<option value="09">09</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
										<option value="29">29</option>
										<option value="30">30</option>
										<option value="31">31</option>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<!--<label for="infoDobYear">Year</label>-->
									<select class="form-control input-sm" v-model="dobYearCalc" v-validate:dobYear="{ required: true }" id="infoDobYear">
										<option value="">Year</option>
										<option v-for="n in 100 | orderBy true -1" :value="n">
											{{ currentYear - 100 + n }}
										</option>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<label>Gender</label>
								<label class="radio">
									<input type="radio" v-model="gender" v-validate:gender="{ required: { rule: true} }" value="male"> Male
								</label>
								<label class="radio">
									<input type="radio" v-model="gender" v-validate:gender value="female"> Female
								</label>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="infoRelStatus">Relationship Status</label>
									<select class="form-control input-sm" v-model="relStatus" v-validate:relStatus="{ required: true }" id="infoRelStatus">
										<option value="single">Single</option>
										<option value="married">Married</option>
										<option value="divorced">Divorced</option>
										<option value="widowed">Widowed</option>

									</select>
								</div>
							</div>
						</div>

						<div class="form-group">
							<label for="infoShirtSize">Shirt Sizes</label>
							<select class="form-control input-sm" v-model="size" v-validate:size="{ required: true }" id="infoShirtSize">
								<option value="s">S (Small)</option>
								<option value="m">M (Medium)</option>
								<option value="l">L (Large)</option>
								<option value="xl">XL (Extra Large)</option>
								<option value="xxl">2XL (2 Extra Large)</option>
							</select>
						</div>

					</div>
				</form>
			</validator>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'roca',
		data(){
			return {
				title: 'Rules of Conduct Agreement',
				currentYear: new Date().getFullYear(),
				// basic info data
				address: null,
				city:null,
				state:null,
				zipCode:null,
				country:'us',
				phone:'',
				mobile:'',
				firstName:null,
				middleName:null,
				lastName:null,
				email:null,
				dobDay:'',
				dobMonth:'',
				dobYearCalc:'',
				dobYear:null,
				gender:null,
				relStatus:'single',
				size:null,
				height:null,
				weight:null
			}
		},
		computed: {
			dobYear(){
				return (this.currentYear - 100 + this.dobYearCalc);
			}
		},
		methods: {
			onValid(){
				this.$dispatch('basic-info', true)
			},
			onInvalid(){
				// for now allow to continue
				this.$dispatch('basic-info', false)
			}
		},
		activate(done){
			$('html, body').animate({scrollTop : 0},300);
			done();
		}
	}
</script>

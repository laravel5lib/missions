<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<validator name="UpdateCampaign">
		<form id="UpdateCampaignForm" class="form-horizontal" novalidate>
			<div class="form-group" :class="{ 'has-error': checkForError('name') }">
				<label for="name" class="col-sm-2 control-label">Name</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="name" id="name" v-model="name"
						   placeholder="Campaign Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
						   maxlength="100" minlength="1" required>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('country') }">
				<label for="country" class="col-sm-2 control-label">Country</label>
				<div class="col-sm-10">
					<select name="country" id="country" class="form-control" v-model="country" v-validate:country="{ required: true }" required>
						<option value="af">Afghanistan</option>
						<option value="ax">Åland Islands</option>
						<option value="al">Albania</option>
						<option value="dz">Algeria</option>
						<option value="as">American Samoa</option>
						<option value="ad">Andorra</option>
						<option value="ao">Angola</option>
						<option value="ai">Anguilla</option>
						<option value="aq">Antarctica</option>
						<option value="ag">Antigua and Barbuda</option>
						<option value="ar">Argentina</option>
						<option value="am">Armenia</option>
						<option value="aw">Aruba</option>
						<option value="au">Australia</option>
						<option value="at">Austria</option>
						<option value="az">Azerbaijan</option>
						<option value="bs">Bahamas</option>
						<option value="bh">Bahrain</option>
						<option value="bd">Bangladesh</option>
						<option value="bb">Barbados</option>
						<option value="by">Belarus</option>
						<option value="be">Belgium</option>
						<option value="bz">Belize</option>
						<option value="bj">Benin</option>
						<option value="bm">Bermuda</option>
						<option value="bt">Bhutan</option>
						<option value="bo">Bolivia, Plurinational State of</option>
						<option value="bq">Bonaire, Sint Eustatius and Saba</option>
						<option value="ba">Bosnia and Herzegovina</option>
						<option value="bw">Botswana</option>
						<option value="bv">Bouvet Island</option>
						<option value="br">Brazil</option>
						<option value="io">British Indian Ocean Territory</option>
						<option value="bn">Brunei Darussalam</option>
						<option value="bg">Bulgaria</option>
						<option value="bf">Burkina Faso</option>
						<option value="bi">Burundi</option>
						<option value="kh">Cambodia</option>
						<option value="cm">Cameroon</option>
						<option value="ca">Canada</option>
						<option value="cv">Cape Verde</option>
						<option value="ky">Cayman Islands</option>
						<option value="cf">Central African Republic</option>
						<option value="td">Chad</option>
						<option value="cl">Chile</option>
						<option value="cn">China</option>
						<option value="cx">Christmas Island</option>
						<option value="cc">Cocos (Keeling) Islands</option>
						<option value="co">Colombia</option>
						<option value="km">Comoros</option>
						<option value="cg">Congo</option>
						<option value="cd">Congo, the Democratic Republic of the</option>
						<option value="ck">Cook Islands</option>
						<option value="cr">Costa Rica</option>
						<option value="ci">Côte d'Ivoire</option>
						<option value="hr">Croatia</option>
						<option value="cu">Cuba</option>
						<option value="cw">Curaçao</option>
						<option value="cy">Cyprus</option>
						<option value="cz">Czech Republic</option>
						<option value="dk">Denmark</option>
						<option value="dj">Djibouti</option>
						<option value="dm">Dominica</option>
						<option value="do">Dominican Republic</option>
						<option value="ec">Ecuador</option>
						<option value="eg">Egypt</option>
						<option value="sv">El Salvador</option>
						<option value="gq">Equatorial Guinea</option>
						<option value="er">Eritrea</option>
						<option value="ee">Estonia</option>
						<option value="et">Ethiopia</option>
						<option value="fk">Falkland Islands (Malvinas)</option>
						<option value="fo">Faroe Islands</option>
						<option value="fj">Fiji</option>
						<option value="fi">Finland</option>
						<option value="fr">France</option>
						<option value="gf">French Guiana</option>
						<option value="pf">French Polynesia</option>
						<option value="tf">French Southern Territories</option>
						<option value="ga">Gabon</option>
						<option value="gm">Gambia</option>
						<option value="ge">Georgia</option>
						<option value="de">Germany</option>
						<option value="gh">Ghana</option>
						<option value="gi">Gibraltar</option>
						<option value="gr">Greece</option>
						<option value="gl">Greenland</option>
						<option value="gd">Grenada</option>
						<option value="gp">Guadeloupe</option>
						<option value="gu">Guam</option>
						<option value="gt">Guatemala</option>
						<option value="gg">Guernsey</option>
						<option value="gn">Guinea</option>
						<option value="gw">Guinea-Bissau</option>
						<option value="gy">Guyana</option>
						<option value="ht">Haiti</option>
						<option value="hm">Heard Island and McDonald Islands</option>
						<option value="va">Holy See (Vatican City State)</option>
						<option value="hn">Honduras</option>
						<option value="hk">Hong Kong</option>
						<option value="hu">Hungary</option>
						<option value="is">Iceland</option>
						<option value="in">India</option>
						<option value="id">Indonesia</option>
						<option value="ir">Iran, Islamic Republic of</option>
						<option value="iq">Iraq</option>
						<option value="ie">Ireland</option>
						<option value="im">Isle of Man</option>
						<option value="il">Israel</option>
						<option value="it">Italy</option>
						<option value="jm">Jamaica</option>
						<option value="jp">Japan</option>
						<option value="je">Jersey</option>
						<option value="jo">Jordan</option>
						<option value="kz">Kazakhstan</option>
						<option value="ke">Kenya</option>
						<option value="ki">Kiribati</option>
						<option value="kp">Korea, Democratic People's Republic of</option>
						<option value="kr">Korea, Republic of</option>
						<option value="kw">Kuwait</option>
						<option value="kg">Kyrgyzstan</option>
						<option value="la">Lao People's Democratic Republic</option>
						<option value="lv">Latvia</option>
						<option value="lb">Lebanon</option>
						<option value="ls">Lesotho</option>
						<option value="lr">Liberia</option>
						<option value="ly">Libya</option>
						<option value="li">Liechtenstein</option>
						<option value="lt">Lithuania</option>
						<option value="lu">Luxembourg</option>
						<option value="mo">Macao</option>
						<option value="mk">Macedonia, the former Yugoslav Republic of</option>
						<option value="mg">Madagascar</option>
						<option value="mw">Malawi</option>
						<option value="my">Malaysia</option>
						<option value="mv">Maldives</option>
						<option value="ml">Mali</option>
						<option value="mt">Malta</option>
						<option value="mh">Marshall Islands</option>
						<option value="mq">Martinique</option>
						<option value="mr">Mauritania</option>
						<option value="mu">Mauritius</option>
						<option value="yt">Mayotte</option>
						<option value="mx">Mexico</option>
						<option value="fm">Micronesia, Federated States of</option>
						<option value="md">Moldova, Republic of</option>
						<option value="mc">Monaco</option>
						<option value="mn">Mongolia</option>
						<option value="me">Montenegro</option>
						<option value="ms">Montserrat</option>
						<option value="ma">Morocco</option>
						<option value="mz">Mozambique</option>
						<option value="mm">Myanmar</option>
						<option value="na">Namibia</option>
						<option value="nr">Nauru</option>
						<option value="np">Nepal</option>
						<option value="nl">Netherlands</option>
						<option value="nc">New Caledonia</option>
						<option value="nz">New Zealand</option>
						<option value="ni">Nicaragua</option>
						<option value="ne">Niger</option>
						<option value="ng">Nigeria</option>
						<option value="nu">Niue</option>
						<option value="nf">Norfolk Island</option>
						<option value="mp">Northern Mariana Islands</option>
						<option value="no">Norway</option>
						<option value="om">Oman</option>
						<option value="pk">Pakistan</option>
						<option value="pw">Palau</option>
						<option value="ps">Palestinian Territory, Occupied</option>
						<option value="pa">Panama</option>
						<option value="pg">Papua New Guinea</option>
						<option value="py">Paraguay</option>
						<option value="pe">Peru</option>
						<option value="ph">Philippines</option>
						<option value="pn">Pitcairn</option>
						<option value="pl">Poland</option>
						<option value="pt">Portugal</option>
						<option value="pr">Puerto Rico</option>
						<option value="qa">Qatar</option>
						<option value="re">Réunion</option>
						<option value="ro">Romania</option>
						<option value="ru">Russian Federation</option>
						<option value="rw">Rwanda</option>
						<option value="bl">Saint Barthélemy</option>
						<option value="sh">Saint Helena, Ascension and Tristan da Cunha</option>
						<option value="kn">Saint Kitts and Nevis</option>
						<option value="lc">Saint Lucia</option>
						<option value="mf">Saint Martin (French part)</option>
						<option value="pm">Saint Pierre and Miquelon</option>
						<option value="vc">Saint Vincent and the Grenadines</option>
						<option value="ws">Samoa</option>
						<option value="sm">San Marino</option>
						<option value="st">Sao Tome and Principe</option>
						<option value="sa">Saudi Arabia</option>
						<option value="sn">Senegal</option>
						<option value="rs">Serbia</option>
						<option value="sc">Seychelles</option>
						<option value="sl">Sierra Leone</option>
						<option value="sg">Singapore</option>
						<option value="sx">Sint Maarten (Dutch part)</option>
						<option value="sk">Slovakia</option>
						<option value="si">Slovenia</option>
						<option value="sb">Solomon Islands</option>
						<option value="so">Somalia</option>
						<option value="za">South Africa</option>
						<option value="gs">South Georgia and the South Sandwich Islands</option>
						<option value="ss">South Sudan</option>
						<option value="es">Spain</option>
						<option value="lk">Sri Lanka</option>
						<option value="sd">Sudan</option>
						<option value="sr">Suriname</option>
						<option value="sj">Svalbard and Jan Mayen</option>
						<option value="sz">Swaziland</option>
						<option value="se">Sweden</option>
						<option value="ch">Switzerland</option>
						<option value="sy">Syrian Arab Republic</option>
						<option value="tw">Taiwan, Province of China</option>
						<option value="tj">Tajikistan</option>
						<option value="tz">Tanzania, United Republic of</option>
						<option value="th">Thailand</option>
						<option value="tl">Timor-Leste</option>
						<option value="tg">Togo</option>
						<option value="tk">Tokelau</option>
						<option value="to">Tonga</option>
						<option value="tt">Trinidad and Tobago</option>
						<option value="tn">Tunisia</option>
						<option value="tr">Turkey</option>
						<option value="tm">Turkmenistan</option>
						<option value="tc">Turks and Caicos Islands</option>
						<option value="tv">Tuvalu</option>
						<option value="ug">Uganda</option>
						<option value="ua">Ukraine</option>
						<option value="ae">United Arab Emirates</option>
						<option value="gb">United Kingdom</option>
						<option value="us">United States</option>
						<option value="um">United States Minor Outlying Islands</option>
						<option value="uy">Uruguay</option>
						<option value="uz">Uzbekistan</option>
						<option value="vu">Vanuatu</option>
						<option value="ve">Venezuela, Bolivarian Republic of</option>
						<option value="vn">Viet Nam</option>
						<option value="vg">Virgin Islands, British</option>
						<option value="vi">Virgin Islands, U.S.</option>
						<option value="wf">Wallis and Futuna</option>
						<option value="eh">Western Sahara</option>
						<option value="ye">Yemen</option>
						<option value="zm">Zambia</option>
						<option value="zw">Zimbabwe</option>
					</select>
				</div>
			</div>
			<div class="form-group" :class="{ 'has-error': checkForError('description') }">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<textarea name="short_desc" id="description" rows="2" v-model="short_desc" class="form-control"
							  v-validate:description="{ required: true, minlength:1, maxlength:120 }" maxlength="120"
							  minlength="1"></textarea>
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': (checkForError('start') || checkForError('end')) }">
				<label for="started_at" class="col-sm-2 control-label">Dates</label>
				<div class="col-sm-10">
					<div class="row">
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('start') }">
								<span class="input-group-addon">Start</span>
								<input type="date" class="form-control" v-model="started_at" id="started_at"
									   v-validate:start="{ required: true }" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="input-group" :class="{ 'has-error': checkForError('end') }">
								<span class="input-group-addon">End</span>
								<input type="date" class="form-control" v-model="ended_at" id="ended_at"
									   v-validate:end="{ required: true }" required>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label for="published_at" class="col-sm-2 control-label">Published Date</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">Published</span>
						<input type="date" class="form-control" v-model="published_at" id="published_at">
					</div>
				</div>
			</div>

			<div class="form-group" :class="{ 'has-error': checkForError('url') }">
				<label for="description" class="col-sm-2 control-label">Page Url</label>
				<div class="col-sm-10">
					<div class="input-group">
						<span class="input-group-addon">www.missions.me/campaigns/</span>
						<input type="text" id="page_url" v-model="page_url" class="form-control"
							   v-validate:url="{ required: false,  }" />
					</div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<a href="/admin/campaigns/{{campaignId}}" class="btn btn-default btn-sm">Cancel</a>
					<a @click="update()" class="btn btn-primary btn-sm">Update</a>
					<a class="btn btn-danger btn-sm pull-right" data-toggle="modal" data-target="#deleteConfirmationModal"	>Delete</a>
				</div>
			</div>
		</form>

		<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
			<div class="modal-dialog modal-sm">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Delete Campaign</h4>
					</div>
					<div class="modal-body">
						<p>Are you sure you want to delete this campaign?</p>
						<div class="row">
							<div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
							<div class="col-xs-6"><a @click="deleteCampaign()" class="btn btn-sm btn-block btn-primary">Yes</a></div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</validator>
</template>
<script>
	export default{
		name: 'campaign-edit',
		props: ['campaignId'],
		data(){
			return {
				name: null,
				country: null,
				country_code: null,
				short_desc: null,
				started_at: null,
				ended_at: null,
				published_at: null,
				page_url: null,
				attemptSubmit: false,
				resource: this.$resource('campaigns{/id}')
			}
		},
		computed: {
			country_code(){
				return [this.country];
			}
		},
		methods: {
			checkForError(field){
				// if user clicked submit button while the field is invalid trigger error styles 
				return this.$UpdateCampaign[field].invalid && this.attemptSubmit;
			},
			update(){
				this.attemptSubmit = true;
				if (this.$UpdateCampaign.valid) {
					this.resource.update(null, {
						name: this.name,
						country_code: this.country_code,
						short_desc: this.description
					}).then(function (resp) {
						debugger;
					}, function (error) {
						debugger;
					});
				}
			},
			deleteCampaign(){
				// delete campaign
				this.resource.delete({id: this.campaignId}).then(function(response) {
					window.location.href = '/admin/campaigns/'
				});
			}
		},
		created(){
			// get campaign data
			this.resource.get({id: this.campaignId}).then(function(response) {
				var campaign = response.data.data;

				$.extend(this, campaign);
				// loop through list of countries
				var country = campaign.country;
				var selectedValue = null;
				$.each($('#country option'), function (index, option) {
					// find country that matches name, get country code
					if (option.innerHTML === country) {
						return selectedValue = option.value;
					}
				});
				this.country = selectedValue;
			});

		}
	}
</script> 
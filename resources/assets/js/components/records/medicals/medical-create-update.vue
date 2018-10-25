<template>
	<div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-8">
						<h3 class="panel-header">Medical Release</h3>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<spinner ref="spinner" global size="sm" text="Loading"></spinner>
				<template v-if="currentStep === 1">
					<div class="row">
						<div class="col-sm-4">
							<h4>Insurance Information</h4>
							<p class="text-muted">We'll need to know your provider and policy number in the unlikey case of an emergency.</p> <p class="text-muted">Medical Insurance is not required for travel, but highly recommend.</p>
						</div>
						<div class="col-sm-8">
							<template v-if="forAdmin">
								<div class="row">
									<div class="col-sm-12">
										<div class="form-group" :class="{ 'has-error': errors.has('manager') }">
											<label for="infoManager">Record Manager</label>
											<v-select @keydown.enter.prevent="" class="form-control" id="infoManager"
																name="manager" v-model="userObj" :options="usersArr"
																:on-search="getUsers" label="name" v-validate="'required'"></v-select>
										</div>
									</div>
								</div>
							</template>
							<div class="row">
								<div class="col-sm-12">
									<div v-error-handler="{ value: name, handle: 'name', messages: { req: 'Please enter the release holder\'s name.'} }">
										<label for="name" class="control-label">Name</label>
										<input type="text" class="form-control" name="name" id="name" v-model="name"
										       placeholder="Name" v-validate="'required|min:1'"
										       minlength="1" required>
									</div>
								</div>

								<template v-if="!noInsurance">
									<div class="col-sm-12">
										<div v-error-handler="{ value: ins_provider, client: 'provider', server: 'ins_provider', messages: { req: 'Please enter the insurance provider\'s name.'} }">
											<label for="ins_provider" class="control-label">Insurance Provider</label>
											<input type="text" class="form-control" name="provider" id="ins_provider"
											       v-model="ins_provider"
											       placeholder="Insurance Provider"
											       v-validate="noInsurance?'':'required|min:1|max:100'"
											       maxlength="100" minlength="1" required>
										</div>
									</div>
									<div class="col-sm-12">
										<div v-error-handler="{ value: ins_policy_no, client: 'policy', server: 'ins_policy_no', messages: { req: 'Please enter the policy number.'} }">
											<label for="ins_policy_no"
											       class="control-label">Insurance Policy Number</label>
											<input type="text" class="form-control" name="policy" id="ins_policy_no"
											       v-model="ins_policy_no"
											       placeholder="Insurance Policy Number"
											       v-validate="noInsurance?'':'required|min:1'"
											       maxlength="100" minlength="1" required>
										</div>
									</div>
								</template>

								<div class="col-xs-12">
									<hr class="divider inv">
									<div class="checkbox">
										<label>
											<input type="checkbox" v-model="noInsurance">
											I do not have medical insurance
										</label>
										<span class="help-block">
					                      Medical Insurance is not required for travel, but highly recommend.
					                    </span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</template>
				<template v-else-if="currentStep === 2">
					<div class="row">
						<div class="col-sm-4">
							<h4>Personal Info</h4>
							<p class="text-muted">Your height and weight is required by some chartered airlines.</p>
							<p class="text-muted">Your medical information is confidential.</p>
						</div>
						<div class="col-sm-8">
							<div class="form-group">
								<div class="btn-group btn-group-xs">
									<button class="btn" :class="[measurementSystem === 'standard' ? 'btn-default' : 'btn-link']"
									        @click="measurementSystem = 'standard'">Standard</button>
									<button class="btn" :class="[measurementSystem === 'metric' ? 'btn-default' : 'btn-link']"
									        @click="measurementSystem = 'metric'">Metric</button>
								</div>
							</div>
							<div class="form-group">
							<div class="row">
								<div class="col-md-4 col-sm-6">
									<label for="weight">What is your Weight?</label><br>
									<div class="input-group">
										<input type="number" class="form-control" name="weight" id="weight"
										       v-model="weightA" placeholder="Your Weight" v-validate="'required'" min="1"
										       required>
										<span class="input-group-addon" v-text="measurementSystem === 'standard' ? 'lbs.' : 'kg.'"></span>
									</div>
								</div>
								<div class="col-md-4 col-sm-6">
									<label for="height">What is your Height?</label><br>
									<select v-if="measurementSystem === 'standard'" class="form-control" name="height" id="height" v-model="heightA">
										<optgroup label="3'">
											<option value="3&rsquo;">3&rsquo;</option>
											<option value="3&rsquo;&nbsp;1&rdquo;">3&rsquo;&nbsp;1&rdquo;</option>
											<option value="3&rsquo;&nbsp;2&rdquo;">3&rsquo;&nbsp;2&rdquo;</option>
											<option value="3&rsquo;&nbsp;3&rdquo;">3&rsquo;&nbsp;3&rdquo;</option>
											<option value="3&rsquo;&nbsp;4&rdquo;">3&rsquo;&nbsp;4&rdquo;</option>
											<option value="3&rsquo;&nbsp;5&rdquo;">3&rsquo;&nbsp;5&rdquo;</option>
											<option value="3&rsquo;&nbsp;6&rdquo;">3&rsquo;&nbsp;6&rdquo;</option>
											<option value="3&rsquo;&nbsp;7&rdquo;">3&rsquo;&nbsp;7&rdquo;</option>
											<option value="3&rsquo;&nbsp;8&rdquo;">3&rsquo;&nbsp;8&rdquo;</option>
											<option value="3&rsquo;&nbsp;9&rdquo;">3&rsquo;&nbsp;9&rdquo;</option>
											<option value="3&rsquo;&nbsp;10&rdquo;">3&rsquo;&nbsp;10&rdquo;</option>
											<option value="3&rsquo;&nbsp;11&rdquo;">3&rsquo;&nbsp;11&rdquo;</option>
										</optgroup>
										<optgroup label="4'">
											<option value="4&rsquo;">4&rsquo;</option>
											<option value="4&rsquo;&nbsp;1&rdquo;">4&rsquo;&nbsp;1&rdquo;</option>
											<option value="4&rsquo;&nbsp;2&rdquo;">4&rsquo;&nbsp;2&rdquo;</option>
											<option value="4&rsquo;&nbsp;3&rdquo;">4&rsquo;&nbsp;3&rdquo;</option>
											<option value="4&rsquo;&nbsp;4&rdquo;">4&rsquo;&nbsp;4&rdquo;</option>
											<option value="4&rsquo;&nbsp;5&rdquo;">4&rsquo;&nbsp;5&rdquo;</option>
											<option value="4&rsquo;&nbsp;6&rdquo;">4&rsquo;&nbsp;6&rdquo;</option>
											<option value="4&rsquo;&nbsp;7&rdquo;">4&rsquo;&nbsp;7&rdquo;</option>
											<option value="4&rsquo;&nbsp;8&rdquo;">4&rsquo;&nbsp;8&rdquo;</option>
											<option value="4&rsquo;&nbsp;9&rdquo;">4&rsquo;&nbsp;9&rdquo;</option>
											<option value="4&rsquo;&nbsp;10&rdquo;">4&rsquo;&nbsp;10&rdquo;</option>
											<option value="4&rsquo;&nbsp;11&rdquo;">4&rsquo;&nbsp;11&rdquo;</option>
										</optgroup>
										<optgroup label="5'">
											<option value="5&rsquo;">5&rsquo;</option>
											<option value="5&rsquo;&nbsp;1&rdquo;">5&rsquo;&nbsp;1&rdquo;</option>
											<option value="5&rsquo;&nbsp;2&rdquo;">5&rsquo;&nbsp;2&rdquo;</option>
											<option value="5&rsquo;&nbsp;3&rdquo;">5&rsquo;&nbsp;3&rdquo;</option>
											<option value="5&rsquo;&nbsp;4&rdquo;">5&rsquo;&nbsp;4&rdquo;</option>
											<option value="5&rsquo;&nbsp;5&rdquo;">5&rsquo;&nbsp;5&rdquo;</option>
											<option value="5&rsquo;&nbsp;6&rdquo;">5&rsquo;&nbsp;6&rdquo;</option>
											<option value="5&rsquo;&nbsp;7&rdquo;">5&rsquo;&nbsp;7&rdquo;</option>
											<option value="5&rsquo;&nbsp;8&rdquo;">5&rsquo;&nbsp;8&rdquo;</option>
											<option value="5&rsquo;&nbsp;9&rdquo;">5&rsquo;&nbsp;9&rdquo;</option>
											<option value="5&rsquo;&nbsp;10&rdquo;">5&rsquo;&nbsp;10&rdquo;</option>
											<option value="5&rsquo;&nbsp;11&rdquo;">5&rsquo;&nbsp;11&rdquo;</option>
										</optgroup>
										<optgroup label="6'">
											<option value="6&rsquo;">6&rsquo;</option>
											<option value="6&rsquo;&nbsp;1&rdquo;">6&rsquo;&nbsp;1&rdquo;</option>
											<option value="6&rsquo;&nbsp;2&rdquo;">6&rsquo;&nbsp;2&rdquo;</option>
											<option value="6&rsquo;&nbsp;3&rdquo;">6&rsquo;&nbsp;3&rdquo;</option>
											<option value="6&rsquo;&nbsp;4&rdquo;">6&rsquo;&nbsp;4&rdquo;</option>
											<option value="6&rsquo;&nbsp;5&rdquo;">6&rsquo;&nbsp;5&rdquo;</option>
											<option value="6&rsquo;&nbsp;6&rdquo;">6&rsquo;&nbsp;6&rdquo;</option>
											<option value="6&rsquo;&nbsp;7&rdquo;">6&rsquo;&nbsp;7&rdquo;</option>
											<option value="6&rsquo;&nbsp;8&rdquo;">6&rsquo;&nbsp;8&rdquo;</option>
											<option value="6&rsquo;&nbsp;9&rdquo;">6&rsquo;&nbsp;9&rdquo;</option>
											<option value="6&rsquo;&nbsp;10&rdquo;">6&rsquo;&nbsp;10&rdquo;</option>
											<option value="6&rsquo;&nbsp;11&rdquo;">6&rsquo;&nbsp;11&rdquo;</option>
										</optgroup>
									</select>
									<template v-else>
										<div class="input-group">
											<input type="number" class="form-control" name="height" id="height"
											       v-model="heightA" placeholder="Your Height" v-validate="'required'" min="1"
											       required>
											<span class="input-group-addon">cm.</span>
										</div>
									</template>
								</div>
							</div>
							</div>
						</div>
					</div>
					<hr class="divider">
					<div class="row">
						<div class="col-sm-4">
							<h4>General Health</h4>
							<p class="text-muted">Please answer the following question(s) truthfully. It is important not only for your safety but the safety of your team.</p>
							<p class="text-muted">Your medical information is confidential.</p>
						</div>
						<div class="col-sm-8">
							<div class="form-group">
								<label>Are you pregnant?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="pregnant" v-model="pregnant"
									              :value="true" v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="pregnant" v-model="pregnant"
									              :value="false">No</label>
								</div>
							</div>
							<div class="form-group">
								<label>Are you currently taking any medication?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesMedication"
									              :value="true" v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesMedication"
									              :value="false">No</label>
								</div>
							</div>
							<div v-if="takesMedication === false" class="form-group">
								<label>Do you have any medically diagnosed conditions?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="hasConditions" v-model="hasConditions"
									              :value="true" v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="hasConditions" v-model="hasConditions"
									              :value="false">No</label>
								</div>
							</div>
							<div v-if="hasConditions === false" class="form-group">
								<label>Do you have any allergies?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="hasAllergies" v-model="hasAllergies" :value="true"
									              v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="hasAllergies" v-model="hasAllergies"
									              :value="false">No</label>
								</div>
							</div>
						</div>
					</div>
				</template>
				<template v-else-if="currentStep === 3">
					<div class="row">
						<div class="col-sm-4">
							<h4>Medical Conditions</h4>
							<p class="text-muted">A conditon may not necessarily disqualify you for travel, but will require a doctor's note.</p>
							<p class="text-muted">Disclosing any and all conditions is very important so your team can take any necessary precautions to ensure your safety.</p>
							<p class="text-muted">Your medical information is confidential.</p>
						</div>
						<div class="col-sm-8">
							<div class="form-group" v-if="!takesMedication">
								<label>Are you taking medication for any existing conditions?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesConditionMedication"
									              :value="true" v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesConditionMedication"
									              :value="false">No</label>
								</div>
							</div>

							<!-- List of Conditions-->
							<label>Please select any conditions you are currently experiencing</label><br>
							<div class="row">
								<div class="col-xs-12 col-sm-6" v-for="condition in conditionsListOrdered" :key="condition.id">
									<div class="checkbox">
										<label>
											<input type="checkbox" v-model="condition.selected"> {{condition.name}}
										</label>
									</div>
								</div>
							</div>

							<!-- Additional Conditions List-->
							<div class="row">
								<div class="col-xs-12 col-sm-6" v-for="condition in additionalConditionsList" :key="condition.id">
									<div class="checkbox">
										<label>
											<input type="checkbox" v-model="condition.selected"> {{condition.name}}
										</label>
									</div>
								</div>

								<div class="col-xs-12">
									<form name="NewCondition">
										<label>Add Unlisted Condition</label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" v-model="newCondition.name"
											       placeholder="eg: an unlisted condition">
											<span class="input-group-btn">
                                        <button class="btn btn-default" type="button"
                                                @click="addCondition(newCondition)">Add</button>
                                    </span>
										</div>
										<p class="help-block">
											Don't see your condition listed? Please add it to the list.</p>
									</form>
								</div>
							</div>
						</div>
					</div>
				</template>
				<template v-else-if="currentStep === 4">
					<div class="row">
						<div class="col-sm-4">
							<h4>Allergies</h4>
							<p class="text-muted">An allergy may not necessarily disqualify you for travel, but could require a doctor's note.</p>
							<p class="text-muted">Disclosing any and all allergies is very important so your team can take any necessary precautions to ensure your safety.</p>
						</div>
						<div class="col-sm-8">
							<div class="form-group" v-if="!takesMedication">
								<label>Are you taking medication for any specific allergies?</label><br>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesAllergyMedication"
									              :value="true" v-validate="'required'">Yes</label>
								</div>
								<div class="radio-inline">
									<label><input type="radio" name="takesMedication" v-model="takesAllergyMedication"
									              :value="false">No</label>
								</div>
							</div>

							<!-- List of Allergies-->
							<label>Please select any allergies you are currently experiencing</label><br>
							<div class="row">
								<div class="col-xs-12 col-sm-6" v-for="allergy in allergiesListOrdered" :key="allergy.id">
									<div class="checkbox">
										<label>
											<input type="checkbox" v-model="allergy.selected"> {{allergy.name}}
										</label>
									</div>
								</div>
							</div>

							<!-- Additional Allergies List-->
							<div class="row">
								<div class="col-xs-12 col-sm-6" v-for="allergy in additionalAllergiesList" :key="allergy.id">
									<div class="checkbox">
										<label>
											<input type="checkbox" v-model="allergy.selected"> {{allergy.name}}
										</label>
									</div>
								</div>

								<div class="col-xs-12">
									<form name="newAllergy">
										<label>Add Unlisted Allergy</label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control" v-model="newAllergy.name"
											       placeholder="eg: an unlisted allergy">
											<span class="input-group-btn">
                                                <button class="btn btn-default" type="button"
                                                        @click="addAllergy(newAllergy)">Add</button>
                                            </span>
										</div>
										<p class="help-block">
											Don't see your allergy listed? Please add it to the list.</p>
									</form>
								</div>
							</div>
						</div>
					</div>
				</template>
				<template v-else-if="currentStep === 5">
					<div class="row">
						<div class="col-sm-4">
							<h4>Emergency Contact</h4>
							<p class="text-muted">In the unlikely event of a medical emergency, this individual will be notified immedately.</p>
						</div>
						<div class="col-sm-8">
							<form name="newContact">
								<div class="row">
									<div class="col-sm-12">
										<div v-error-handler="{ value: emergency_contact.name, handle: 'emergencyname' }">
											<label>Name</label>
											<input type="text" class="form-control" v-model="emergency_contact.name"
											       name="emergencyname" v-validate="'required|min:1'" minlength="1"
											       required>
										</div>
									</div>
									<div class="col-sm-12">
										<div v-error-handler="{ value: emergency_contact.email, handle: 'emergencyemail', messages: { email: 'Please enter a valid email address.'} }">
											<label>Email</label>
											<input type="email" class="form-control" v-model="emergency_contact.email"
											       name="emergencyemail" v-validate="'required|email'" minlength="1"
											       required>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<div v-error-handler="{ value: emergency_contact.phone, handle: 'emergencyphone' }">
											<label>Phone</label>
											<phone-input v-model="emergency_contact.phone" name="emergencyphone"
											             v-validate="'required'"></phone-input>
										</div>
									</div>
									<div class="col-sm-12">
										<div v-error-handler="{ value: emergency_contact.relationship, handle: 'emergencyrelationship' }">
											<label>Relationship</label>
											<select class="form-control" v-model="emergency_contact.relationship"
											        name="emergencyrelationship" v-validate="'required'" required>
												<option value="friend">Friend</option>
												<option value="spouse">Spouse</option>
												<option value="family">Family</option>
												<option value="guardian">Guardian</option>
												<option value="other">Other</option>
											</select>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</template>
				<template v-else-if="currentStep === 6">

					<template v-if="hasConditionsOrAllergies || takesMedication">
						<h3>Doctor's Note Required</h3>
						<p class="help-block">
							You have indicated that you have one or more medical conditions or allergies or that you take medication. In order to be cleared for travel, you must download the form below and have it completed by your doctor.  You will need to supply <strong>one form per condition or allergy</strong>
							as certain conditions may be treated by different doctors.
						</p>
						<p>
							<a class="btn btn-primary btn-md" href="/downloads/medication_condition_form2017.pdf"
							   target="_blank">
								<i class="fa fa-file-pdf-o icon-left"></i> Download Permission Form
							</a>
						</p>
						<hr class="divider inv">
						<h5>Completed Forms</h5>
						<p>
							Once you have completed the form(s) please attach them to your medical release by uploading them here in PDF format.</p>
						<ul class="list-group" v-if="uploads.length">
							<li class="list-group-item" v-for="upload in uploads" :key="upload.id">
								<i class="fa fa-file-pdf-o"></i> {{upload.name}}
								<a class="badge" @click="confirmUploadRemoval(upload)"><i class="fa fa-close"></i></a>
							</li>
						</ul>
						<upload-create-update type="file" lock-type :ui-selector="2" ui-locked is-child :tags="['User']"
						                      button-text="Attach" allow-name
						                      :name="'medical-release-'+ today + '-' + uploadCounter"
						                      @uploads-complete="uploadsComplete"></upload-create-update>

					</template>

					<h3>Agreement</h3>

					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="consent">
							I agree to release this information.
						</label>
						<p class="help-block">
							I hereby release all medical information, prescription information, insurance information and physician information completed in this portion of my registration to Missions.Me and Rescue International. I understand that this information will also be shared with my Trip Representative, Project Director and/or my Squad Leader to ensure my safety during the duration of my trip.
						</p>
					</div>

					<div class="checkbox">
						<label>
							<input type="checkbox" v-model="agreement">
							I have verified that the information provided is truthful and accurate to the very best of my knowledge.
						</label>
						<p class="help-block">
							By clicking the above checkbox and submitting this medical release, I am agreeing to
							Missions.Me's Terms of Service agreement and privacy policy. I understand that falsifying
							medical information or withholding important medical informations is immediate grounds for
							dismissal from a Missions.Me trip.
						</p>
					</div>
				</template>
			</div>
			<div class="panel-footer" style="padding: 2em">
				<div class="row">
					<div class="col-sm-6 hidden-xs">
						<hr class="divider sm inv">
						<ol class="carousel-indicators">
							<li v-for="i in 6" :key="i" :class="{'active': currentStep === i}"></li>
						</ol>
					</div>
					<div class="col-sm-6 text-right">
						<a @click="back" class="btn btn-link">Cancel</a>
						<button v-if="currentStep !== 1" type="button" class="btn btn-default" @click="backStep">
							<i class="fa fa-chevron-left"></i> Previous
						</button>
						<button v-if="currentStep !== 6" type="button" class="btn btn-primary" @click="nextStep">
							Next <i class="fa fa-chevron-right"></i>
						</button>
						<button v-else type="button" :disabled="!agreement || !consent" class="btn btn-primary" @click="isUpdate?update():submit()">Finish
						</button>
					</div>
				</div>
			</div>
		</div>
		<modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue"
		       cancel-text="Cancel" :callback="forceBack">
			<div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
		</modal>
	</div>
</template>
<style>
	.carousel-indicators {
		bottom: 0;
		position: relative;
		left: 10%;
	}

	.carousel-indicators li {
		background-color: #999999;
	}

	.carousel-indicators .active {
		background-color: #F33644;
	}
</style>
<script type="text/javascript">
  import $ from 'jquery';
  import _ from 'underscore';
  import vSelect from "vue-select";
  import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
  import errorHandler from '../../error-handler.mixin';

  export default {
    name: 'medical-create-update',
    components: {vSelect, 'upload-create-update': uploadCreateUpdate},
    mixins: [errorHandler],
    props: {
		isUpdate: {
			type: Boolean,
			default: false
		},
		id: {
			type: String,
			default: null
		},
		forAdmin: {
			type: Boolean,
			default: false
		},
		reservationId: {
			type: String,
			default: null
		},
		requirementId: {
			type: String,
			default: null
		}
    },
    data() {
      return {
        noInsurance: false,
        usersArr: [],
        userObj: null,
        name: '',
        ins_provider: '',
        ins_policy_no: '',
        is_risk: true,
        conditions: [],
        allergies: [],
        uploads: [],
        upload_ids: [],
        uploadCounter: 1,
        emergency_contact: {
          name: '',
          email: '',
          phone: '',
          relationship: '',
        },

        newCondition: {
          name: '',
          selected: true
        },
        newAllergy: {
          name: '',
          selected: true
        },

        // logic vars
        measurementSystem: 'metric',
        cmRange: _.range(90, 216),
        conditionsList: [],
        additionalConditionsList: [],
        allergiesList: [],
        additionalAllergiesList: [],
        deleteModal: false,
        showSaveAlert: false,
        today: moment().format('YYYY-MM-DD'),
        yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
        tomorrow: moment().add(1, 'days').format('YYYY-MM-DD'),
        resource: this.$resource('medical-releases{/id}'),

        currentStep: 1,
        weightA: 0,
        heightA: 0,
        pregnant: null,
        takesMedication: null,
        takesConditionMedication: null,
        takesAllergyMedication: null,
        hasConditions: null,
        hasAllergies: null,
        agreement: false,
				consent: false
      }
    },
    watch: {
      measurementSystem(val, oldVal) {
        // 1 ft === 30.48 cm
        // 1 in === 2.54 cm
        // 1 lb === 0.45359237 kg
        if (val !== oldVal) {
          if (val === 'standard') {
            // convert to standard
            if (this.weightA !== 0) {
              this.weightA = this.toStandard(this.weightA, 'weight');
            }
            if (this.heightA) {
              this.heightA = this.toStandard(this.heightA);
            }
          } else {
            // convert to metric
            if (this.weightA !== 0) {
              this.weightA = this.toMetric(this.weightA, 'weight');
            }
            if (this.heightA !== 0) {
              this.heightA = this.toMetric(this.heightA);
            }
          }
        }
      }
    },
    computed: {
      weight: {
        get() {
            return `${this.weightA} ${this.measurementSystem === 'standard' ? 'lbs.' : 'kg.'}`;
        },
        set(val) {},
      },
      height: {
        get() {
          if (this.measurementSystem === 'standard') {
            if ( /&rdquo;/.test(this.heightA) )
                return this.heightA.replace('&rsquo;&nbsp;', ' ft. ').replace('&rdquo;', ' in.');
            else {
              return this.heightA.replace('&rsquo;', ' ft.');
            }
          }
          return this.heightA + ' cm.';
        },
        set(val) {},
      },
      conditionsListOrdered() {
        return _.sortBy(this.conditionsList, 'name')
      },
      allergiesListOrdered() {
        return _.sortBy(this.allergiesList, 'name')
      },
      country_code() {
        return _.isObject(this.countryObj) ? this.countryObj.code : null;
      },
      user_id: {
        get() {
          return _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
        },
        set() {}
      },
      hasConditionsOrAllergies() {
        return _.contains(_.pluck(this.conditionsList, 'selected'), true) || _.contains(_.pluck(this.allergiesList, 'diagnosed'), true) || _.contains(_.pluck(this.allergiesList, 'medication'), true) || _.contains(_.pluck(this.additionalConditionsList, 'selected'), true) || _.contains(_.pluck(this.additionalAllergiesList, 'medication'), true) || _.contains(_.pluck(this.additionalAllergiesList, 'diagnosed'), true)
      }
    },
    methods: {
      toMetric(value, type = 'height', round = true) {
        let thisVal;
        switch(type) {
          case 'height': // expects string ft. + in.
            if ( /&rdquo;/.test(value) ) {
              thisVal = value.replace('&rsquo;&nbsp;', '-').replace('&rdquo;', '').split('-');
              thisVal =  (parseInt(thisVal[0]) * 30.48) + (parseInt(thisVal[1]) * 2.54);
            } else {
              thisVal = parseInt(value.replace('&rsquo;', ''));
              thisVal *= 30.48;
            }
            if (round)
              return Math.round(thisVal);
            return thisVal;
          case 'weight':
            if (round)
              return Math.round(parseFloat(value) * 0.45359237);
            return parseFloat(value) * 0.45359237;
        }
      },
      toStandard(value, type = 'height', round = true) {
        let thisVal;
        switch(type) {
          case 'height':
            thisVal = parseFloat(value) / 2.54; // convert to inches
            if ( (thisVal % 12) > 0 )
              return `${parseInt(thisVal / 12)}&rsquo;&nbsp;${Math.round(thisVal % 12)}&rdquo;`;
            else
              return `${parseInt(thisVal / 12)}&rsquo;`;
          case 'weight':
            if (round)
              return Math.round(parseFloat(value) / 0.45359237);
            return parseFloat(value) / 0.45359237;
        }
      },

      backStep() {
        switch (this.currentStep) {
          case 4:
            if (this.hasConditions || this.takesMedication) {
              this.currentStep--;
            } else {
              this.currentStep -= 2;
            }
            break;
          case 5:
            if (this.hasAllergies || this.takesMedication || this.hasConditions) {
              this.currentStep--;
            } else {
              if (this.hasConditions) {
                this.currentStep -= 2;
              } else {
                this.currentStep -= 3;
              }
            }
            break;
          default:
            this.currentStep--;
        }

      },
      nextStep() {
        this.$validator.validateAll().then(result => {
          if (!result) {
            this.$root.$emit('showError', 'Please check form.');
            return false;
          }

					$('html, body').animate({scrollTop : 0},300);

          switch (this.currentStep) {
            case 2:
              if (this.hasConditions || this.takesMedication) {
                this.currentStep++;
              } else {
                if (this.hasAllergies) {
                  this.currentStep += 2;
                } else {
                  this.currentStep += 3;
                }
              }
              break;
            case 3:
              if (this.hasAllergies || this.takesMedication || this.hasConditions) {
                this.currentStep++;
              } else {
                this.currentStep += 2;
              }
              break;
            default:
              this.currentStep++;
          }
        });
      },
      getUsers(search, loading) {
        loading ? loading(true) : void 0;
        this.$http.get('users', {params: {search: search}}).then((response) => {
          this.usersArr = response.data.data;
          loading ? loading(false) : void 0;
        })
      },
      back(force) {
        if (this.isFormDirty && !force) {
          this.showSaveAlert = true;
          return false;
        }

        if (this.reservationId && this.requirementId) {
            window.location.href = `/${this.firstUrlSegment}/reservations/${this.reservationId}/requirements/${this.requirementId}`;
        } else {
            window.location.href = `/${this.firstUrlSegment}/records/medical-releases/${this.id}`;
        }
      },
      forceBack() {
        return this.back(true);
      },
      addCondition(condition) {
        this.additionalConditionsList.push(condition);
        this.reset('condition');
      },
      addAllergy(allergy) {
        this.additionalAllergiesList.push(allergy);
        this.reset('allergy');
      },
      reset(type) {
        switch (type) {
          case 'condition':
            this.newCondition = {
              name: '',
              medication: false,
              diagnosed: false,
              selected: true
            };
            $('#newCondition').collapse();
            break;
          case 'allergy':
            this.newAllergy = {
              name: '',
              medication: false,
              diagnosed: false,
              selected: true
            };
            $('#newAllergy').collapse();
            break;
        }
      },
      prepArrays() {
        this.conditions = _.where(_.union(this.conditionsList, this.additionalConditionsList), {selected: true});
        this.allergies = _.where(_.union(this.allergiesList, this.additionalAllergiesList), {selected: true});
      },
      submit() {
        let data;
        this.$validator.validateAll().then(result => {
          if (!result) {
            this.showError = true;
            return;
          }

          this.prepArrays();
          data = {
            name: this.name,
            ins_provider: this.ins_provider,
            ins_policy_no: this.ins_policy_no,
            conditions: this.conditions,
            allergies: this.allergies,
            height: this.measurementSystem === 'standard' ? this.toMetric(this.heightA) : this.heightA,
            weight: this.measurementSystem === 'standard' ? this.toMetric(this.weightA, 'weight') : this.weightA,
            pregnant: this.pregnant,
            emergency_contact: this.emergency_contact,
            is_risk: this.is_risk,
            user_id: this.user_id,
            takes_medication: !!(this.takesMedication ? true : (this.takesConditionMedication || this.takesAllergyMedication)),
            upload_ids: _.uniq(this.upload_ids),
            reservation_id: this.reservationId
          };

          this.resource.post({}, data).then((resp) => {
            this.$root.$emit('showSuccess', 'Medical Release created.');
            let that = this;
            setTimeout(() => {
            	if (that.reservationId && that.requirementId) {
                    window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                } else {
                    window.location.href = '/' + that.firstUrlSegment + '/records/medical-releases/' + resp.data.data.id;
                }
            }, 1000);
          }, (error) => {
            this.errors = error.data.errors;
            this.$root.$emit('showError', 'Unable to create medical release.');
          });
        });
      },
      update() {
        let data;
        this.$validator.validateAll().then(result => {
          if (!result) {
            return;
          }

          this.prepArrays();
          data = {
            name: this.name,
            ins_provider: this.ins_provider,
            ins_policy_no: this.ins_policy_no,
            conditions: this.conditions,
            allergies: this.allergies,
            height: this.measurementSystem === 'standard' ? this.toMetric(this.heightA) : this.heightA,
            weight: this.measurementSystem === 'standard' ? this.toMetric(this.weightA, 'weight') : this.weightA,
            pregnant: this.pregnant,
            emergency_contact: this.emergency_contact,
            takes_medication: !!(this.takesMedication ? true : (this.takesConditionMedication || this.takesAllergyMedication)),
            is_risk: this.is_risk,
            user_id: this.user_id,
            upload_ids: _.uniq(this.upload_ids),
            reservation_id: this.reservationId
          };
          this.resource.put({id: this.id, include: 'uploads'}, data).then((resp) => {
            this.$root.$emit('showSuccess', 'Changes saved.');
            let that = this;
            setTimeout(() => {
              	
              	if (that.reservationId && that.requirementId) {
                    window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                } else {
                    window.location.href = '/' + that.firstUrlSegment + '/records/medical-releases/' + that.id;
                }

            }, 1000);
          }, (error) => {
            this.errors = error.data.errors;
            this.$root.$emit('showError', 'Unable to save changes.');
          });
        });
      },
      confirmUploadRemoval(upload) {
        this.uploads.$remove(upload);
        this.upload_ids.$remove(upload.id);
      },
      uploadsComplete(data) {
        switch (data.type) {
          case 'file':
            this.uploads.push(data);
            this.uploads = _.uniq(this.uploads);
            this.upload_ids.push(data.id);
            this.upload_ids = _.uniq(this.upload_ids);
            this.uploadCounter++;
            break;
        }
      },

    },
    mounted() {
      if (this.isUpdate) {
        this.$http.get(`medical-releases/${this.id}`).then((response) => {
          this.user_id = response.data.data.id;
          let medical_release = response.data.data;
          medical_release.uploads = medical_release.uploads;
          this.upload_ids = _.pluck(medical_release.uploads, 'id');
          this.uploadCounter = medical_release.uploads ? medical_release.uploads.length + 1 : 0;
          this.userObj = medical_release.user;
          this.usersArr.push(this.userObj);
          $.extend(this, medical_release);
	          this.takesMedication = medical_release.takes_medication || false;
						this.noInsurance = medical_release.ins_provider ? false : true;
						this.hasConditions = medical_release.has_conditions;
						this.hasAllergies = medical_release.has_allergies;
						this.takesConditionMedication = medical_release.takes_medication;
						this.takesAllergyMedication = medical_release.takes_medication;
						this.heightA = medical_release.height,
            this.weightA = medical_release.weight,
						this.pregnant = medical_release.pregnant

          this.$http.get('medical/conditions').then((response) => {
            // prepare conditions for UI
            let med_conditions = medical_release.conditions.data;
            _.each(response.data.data, (condition) => {
              let obj = {name: condition, medication: false, diagnosed: false, selected: false};
              let match = _.find(med_conditions, function (c, i) {
                med_conditions[i].selected = true;
                return c.name === condition;
              });
              if (match) {
                med_conditions = _.reject(med_conditions, function (mc) {
                  return mc.name === match.name;
                });
                _.extend(obj, match, {selected: true});
              }
              this.conditionsList.push(obj);
            });
            this.additionalConditionsList = med_conditions;
          });
          this.$http.get('medical/allergies').then((response) => {
            // prepare conditions for UI
            let med_allergies = medical_release.allergies;

            console.log(med_allergies);
            _.each(response.data.data, (allergy) => {
              let obj = {name: allergy, medication: false, diagnosed: false, selected: false};
              let match = _.find(med_allergies, function (a, i) {
                med_allergies[i].selected = true;
                return a.name === allergy;
              });
              if (match) {
                med_allergies = _.reject(med_allergies, function (ma) {
                  return ma.name === match.name;
                });
                _.extend(obj, match, {selected: true});
              }
              this.allergiesList.push(obj);
            });
            this.additionalAllergiesList = med_allergies;
          });
        });
      } else {
        this.$http.get('medical/conditions').then((response) => {
          _.each(response.data.data, (condition) => {
            this.conditionsList.push({name: condition, medication: false, diagnosed: false, selected: false});
          });
        });
        this.$http.get('medical/allergies').then((response) => {
          _.each(response.data.data, (allergy) => {
            this.allergiesList.push({name: allergy, medication: false, diagnosed: false, selected: false});
          });
        });
      }
    }
  }
</script>

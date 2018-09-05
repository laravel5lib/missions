<template >
	<div style="position:relative;">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>

			<form id="CreateUpdateMedicalCredential" class="form-horizontal" novalidate>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-12">
								<h5 class="panel-header">Basic Information</h5>
							</div>
						</div>
					</div>
					<div class="panel-body">
                        
                        <template v-if="forAdmin">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="infoManager">Record Manager</label>
                                    <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" v-model="userObj" :options="usersArr" :on-search="getUsers" label="name" name="manager" v-validate="'required'"></v-select>
                                </div>
                            </div>
                        </template>

                        <div class="row">
    						<div class="col-sm-6" v-error-handler="{ value: applicant_name, handle: 'name', messages: { req: 'Please provide credential holder\'s name.'} }">
    							<label for="name" class="control-label">Credential Holder's Name</label>
    							<input type="text" class="form-control" name="name" id="name" v-model="applicant_name"
    							       placeholder="Name" v-validate="'required'"
    							       minlength="1" required>
    						</div>
                             <div class="col-sm-6" v-error-handler="{ value: selectedRole, handle: 'role', messages: { req: 'Please select a medical role.'} }">
                                <label class="control-label">Medical Role</label>
	                             <v-select @keydown.enter.prevent="" class="form-control" id="group" v-model="selectedRoleObj"
	                                       :options="rolesOrdered" label="name" placeholder="Select Role" name="role" v-validate="'required'"></v-select>
                             </div>
                        </div>
                    </div>
                </div>
                
			    <div v-for="(QA, indexQA) in content">
                    
                    <!-- start has type designation -->
					<template v-if="QA.type && checkConditions(QA)">
                            
                        <!-- radio -->
						<template v-if="QA.type === 'radio'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('radio' + indexQA), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<label class="radio-inline" v-for="(choice, choiceIndex) in QA.options">
										<input type="radio" :value="choice.value" v-model="QA.a" :name="'radio' + indexQA  " v-validate="choiceIndex === 0 ?'required' : ''"> {{ choice.name }}
									</label>
								</div>
								<div class="panel-footer" v-show= "errors.has('radio' + indexQA)">
									<div class="errors-block"></div>
								</div>
							</div>
						</template>
                        <!-- end radio -->
                        
                        <!-- checkbox -->
						<template v-if="QA.type === 'checkbox' && QA.id === 'certifications'">
                            
                            <!-- start certification checklist -->
							<template v-if="selectedRoleObj && !!isCertified && !isStudent">
								<div class="panel panel-default" v-error-handler="{ value: QA.certifiedOptions, client: 'certifications', class: 'panel-danger has-error', messages: { req: 'Please select at least one role.'} }">
									<div class="panel-heading">
										<h5>I am certified in the following:</h5>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="checkbox col-sm-6 col-xs-12" v-for="(choice, choiceIndex) in QA.certifiedOptions">
												<label>
													<input type="checkbox" :checked="choice.value" :value="true" v-model="choice.value" name="certifications" v-validate="choiceIndex === 0 ?'required' : ''">
													{{ choice.name }}
												</label>
											</div>
										</div>
									</div>
									<div class="panel-footer" v-show= "errors.has('certifications')">
										<div class="errors-block"></div>
									</div>
								</div>
							</template>

                            <!-- end certification checklist -->
                            
                            <!-- start participation checklist -->
                            <div class="panel panel-default" v-error-handler="{ value: QA.allOptions, client: 'participations', class: 'panel-danger has-error', messages: { req: 'Please select at least one role.'} }">
                                <div class="panel-heading">
                                    <h5>I am willing to participate in the following:</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
    									<div class="checkbox col-sm-6 col-xs-12" v-for="(choice, choiceIndex) in QA.allOptions">
    										<label>
											    <input type="checkbox" :checked="choice.value" :value="true" v-model="choice.value" name="participations" v-validate="choiceIndex === 0 ? 'required' : ''">
											    {{ choice.name }}
                                            </label>
    									</div>
                                    </div>
                                </div>
	                            <div class="panel-footer" v-show= "errors.has('participations')">
		                            <div class="errors-block"></div>
	                            </div>
                            </div>
                            <!-- end participation checklist -->
						</template>
                        <!-- end check box -->
                        
                        <!-- textarea -->
						<template v-if="QA.type === 'textarea'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('textarea' + indexQA), class: 'panel-danger has-error', messages: { req: 'Please explain.'} }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<span class="help-block">Please Explain:</span>
									<textarea class="form-control" v-model="QA.a" :name="'textarea' + indexQA" v-validate="'required'"></textarea>
								</div>
								<div class="panel-footer" v-show= "errors.has('textarea' + indexQA)">
									<div class="errors-block"></div>
								</div>
							</div>
						</template>
                        <!-- end textarea -->
                        
                        <!-- start select -->
						<template v-if="QA.type === 'select' && QA.id !== 'role'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('select' + indexQA), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<select class="form-control" v-model="QA.a" :name="'select' + indexQA" v-validate="''">
										<option value="">-- Select Role --</option>
										<option v-for="option in QA.options" :value="option.value">{{option.name}}</option>
									</select>
								</div>
								<div class="panel-footer" v-show= "errors.has('select' + indexQA)">
									<div class="errors-block"></div>
								</div>
							</div>
						</template>
                        <!-- end select -->
                        
                        <!-- start file -->
						<template v-if="QA.type === 'file'">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h5 v-text="QA.q"></h5>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-offset-1 col-xs-9">
            								<ul class="list-group" v-if="uploads.length">
            									<li class="list-group-item" v-for="upload in uploads">
            										<i class="fa fa-file-pdf-o"></i> {{upload.name}}
                                                        <a class="badge" @click="confirmUploadNoteRemoval(upload)"><i class="fa fa-close"></i></a>
            									</li>
            								</ul>
            								<upload-create-update v-show="uploads.length < 1" type="file" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" :name="'credentials-professor-note-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</template>
                        <!-- end file -->
                        
                        <!-- start date -->
						<template v-if="QA.type === 'date'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('date' + indexQA), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<date-picker v-model="QA.a" :view-format="['YYYY-MM-DD']" type="date" :name="'date' + indexQA"></date-picker>
								</div>
								<div class="panel-footer" v-show= "errors.has('date' + indexQA)">
									<div class="errors-block"></div>
								</div>
							</div>
						</template>
                        <!-- end date -->

					</template>
                    <!-- end has type designation -->
				</div>

				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-header">Required Documents</h5>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-12">
								<ul class="list-group" v-if="uploads.length">
									<li class="list-group-item" v-for="upload in uploads">
										<i class="fa fa-file-pdf-o"></i> {{upload.name}} <span v-if="upload.expires">| Expires: {{upload.expires|moment('YYYY-MM-DD')}}</span>
                                    <a class="badge" @click="confirmUploadRemoval(upload)"><i class="fa fa-close"></i></a>
									</li>
								</ul>
								<div v-else class="text-center">
									<em class="text-muted">No Documents Attached</em>
								</div>
							</div>
							<div class="col-sm-12" v-if="(requiredByRole('license') || optionalByRole('license')) && !uploadSatisfied('license')">
                                <hr class="divider">

									<form id="UploadLicenseForm" data-vv-scope="upload-license-form">
										<!-- <div class="form-group"> -->
										<div class="col-xs-12">
											<div class="col-sm-6">
												<label>License</label>
											</div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" v-model="show_expire.license"> This document Expires
                                                </label>
											</div>
										</div>
										<div class="col-sm-6">
											<upload-create-update type="other" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" hide-submit submit-event="upload-license" :name="'credentials-license-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
											<a @click="uploadDoc('upload-license-form', 'license')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.license" class="col-sm-6" :class="{ 'has-error': errors.has('licenseexpires', 'upload-license-form') || dateIsValid(expires.license)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="errors.has('licenseexpires', 'upload-license-form') || dateIsValid(expires.license)" v-model="expires.license" :view-format="['YYYY-MM-DD']" type="date" ></date-picker>
											</div>
										</div>
									</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('certification') || optionalByRole('certification')) && !uploadSatisfied('certification')">
                                <hr class="divider">

								<form id="UploadCertificationForm" data-vv-scope="upload-certification-form">
									<div class="col-xs-12">
										<div class="col-sm-6"><label>Certification</label></div>
										<div class="col-sm-6">
											<label>
												<input type="checkbox" v-model="show_expire.certification"> This document Expires
                                            </label>
										</div>

									</div>
									<div class="col-sm-6">
										<upload-create-update type="other" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-certification" :name="'credentials-certification-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
										<a @click="uploadDoc('upload-certification-form', 'certification')" class="btn btn-primary">Attach</a>
									</div>
									<div v-if="show_expire.certification" class="col-sm-6" :class="{ 'has-error': errors.has('certexpires', 'upload-certification-form') || dateIsValid(expires.certification)}">
										<label class="control-label">Expires</label>
										<div>
											<date-picker :has-error="errors.has('certexpires', 'upload-certification-form') || dateIsValid(expires.certification)" v-model="expires.certification" :view-format="['YYYY-MM-DD']" type="date" ></date-picker>
										</div>
									</div>
								</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('diploma') || optionalByRole('diploma')) && !uploadSatisfied('diploma')">
                                <hr class="divider">

								<form id="UploadDiplomaForm" data-vv-scope="upload-diploma-form">
									<div class="col-xs-12">
										<div class="col-sm-6"><label>Diploma</label></div>
										<div class="col-sm-6">
											<label>
												<input type="checkbox" v-model="show_expire.diploma"> This document Expires
                                            </label>
										</div>
									</div>
									<div class="col-sm-6">
										<upload-create-update type="other" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-diploma" :name="'credentials-diploma-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
										<a @click="uploadDoc('upload-diploma-form', 'diploma')" class="btn btn-primary">Attach</a>
									</div>
									<div class="col-sm-6" v-if="show_expire.diploma" :class="{ 'has-error': errors.has('diplomaexpires', 'upload-diploma-form') || dateIsValid(expires.diploma)}">
										<label class="control-label">Expires</label>
										<div>
											<date-picker :has-error="errors.has('diplomaexpires', 'upload-diploma-form') || dateIsValid(expires.diploma)" v-model="expires.diploma" :view-format="['YYYY-MM-DD']" type="date" ></date-picker>
										</div>
									</div>
								</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('letter') || optionalByRole('letter')) && !uploadSatisfied('letter')">
                                <hr class="divider">

									<form id="UploadLetterForm" data-vv-scope="upload-letter-form">
										<div class="col-xs-12">
											<div class="col-sm-6"><label>Note from Professor</label></div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" v-model="show_expire.letter"> This document Expires
	                                            </label>
											</div>

										</div>
										<div class="col-sm-6">
											<upload-create-update type="other" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-letter" :name="'credentials-letter-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
											<a @click="uploadDoc('upload-letter-form', 'letter')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.letter" class="col-sm-6" :class="{ 'has-error': errors.has('letterexpires', 'upload-letter-form') || dateIsValid(expires.letter)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="errors.has('letterexpires', 'upload-letter-form') || dateIsValid(expires.letter)" v-model="expires.letter" :view-format="['YYYY-MM-DD']" type="date" ></date-picker>
											</div>
										</div>
									</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('resume') || optionalByRole('resume')) && !uploadSatisfied('resume')">
                                <hr class="divider">

									<form id="UploadResumeForm" data-vv-scope="upload-resume-form">
										<div class="col-sm-12">
											<div class="col-sm-6"><label>Resume</label></div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" v-model="show_expire.resume"> This document Expires
	                                            </label>
											</div>
										</div>
										<div class="col-sm-6">
											<upload-create-update type="file" lock-type :ui-selector="2" ui-locked is-child :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-resume" :name="'credentials-resume-'+ today + '-' + uploadCounter"  @uploads-complete="uploadsComplete"></upload-create-update>
											<a @click="uploadDoc('upload-resume-form', 'resume')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.resume" class="col-sm-6" :class="{ 'has-error': errors.has('resumeexpires', 'upload-resume-form') || dateIsValid(expires.resume)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="errors.has('resumeexpires', 'upload-resume-form') || dateIsValid(expires.resume)" v-model="expires.resume" :view-format="['YYYY-MM-DD']" type="date" ></date-picker>
											</div>
										</div>
									</form>

							</div>
						</div>
					</div>
				</div>

				<div class="form-group text-center">
					<div class="col-xs-12">
						<template v-if="!isUpdate">
							<a @click="back" class="btn btn-default">Cancel</a>
							<a @click="submit" class="btn btn-primary">Create</a>
						</template>
						<template v-else>
							<a @click="back" class="btn btn-default">Cancel</a>
							<a @click="update" class="btn btn-primary">Update</a>
						</template>
					</div>
				</div>
			</form>

			<modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
				<div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
			</modal>

	</div>

</template>
<script type="text/javascript">
	import _ from 'underscore';
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../../uploads/admin-upload-create-update.vue';
    import errorHandler from'../../error-handler.mixin';
    export default{
        name: 'medical-credential-create-update',
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
        data(){
            return {
                applicant_name: '',
                usersArr: [],
                userObj: null,
                selectedRoleObj: null,
                roles: [
                    {value: 'MDPF', name: 'Medical Professional', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'MDSG', name: 'Medical Student', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSN', name: 'Medical Student: Nursing', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSP', name: 'Medical Student: Pre-Med', required: ['letter', ], optional: ['certification']},
                    {value: 'MDSD', name: 'Medical Student: Dental', required: ['letter', ], optional: ['certification']},
                    {value: 'RESP', name: 'Respitory Therapist', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHYS', name: 'Physician', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'PHYA', name: 'Physician\'s Assistant', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'PHYT', name: 'Physical Therapist', required: ['license', 'diploma','resume' ], optional: []},
                    {value: 'PHAT', name: 'Pharmacy Tech', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHAA', name: 'Pharmacy Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'PHAR', name: 'Pharmacist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'OTEC', name: 'Optometry Tech', required: ['license', 'diploma', ], optional: []},
                    {value: 'ODOC', name: 'Optometry Doctor', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'OAST', name: 'Optical Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'DIET', name: 'Dietitian', required: ['license', 'diploma', ], optional: []},
                    {value: 'NUTR', name: 'Nutrionist', required: ['license', 'resume'], optional: ['certification']},
                    {value: 'LACT', name: 'Lactation Consultant', required: ['license', 'resume'], optional: ['certification']},
                    {value: 'NAST', name: 'Nurse Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'NTEC', name: 'Nurse Tech', required: ['license', 'diploma', ], optional: []},
                    {value: 'NPRC', name: 'Nurse Practitioner', required: [], optional: []},
                    {value: 'REGN', name: 'Nurse (RN)', required: ['license', 'diploma', ], optional: []},
                    {value: 'LPNN', name: 'Nurse (LPN)', required: ['license', 'diploma', ], optional: []},
                    {value: 'NCRT', name: 'Non-Certified', required: [], optional: []},
                    {value: 'MEDA', name: 'Medical Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'LVNN', name: 'LVN', required: ['license', 'diploma', ], optional: []},
                    {value: 'HEDU', name: 'Health Education', required: [], optional: ['license', 'diploma', 'resume']},
                    {value: 'ETEC', name: 'EMT', required: ['license', 'diploma', ], optional: []},
                    {value: 'MDFG', name: 'Doctor (OB/GYN)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'MDOC', name: 'Doctor (MD)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DDOC', name: 'Doctor (DO)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DENT', name: 'Dentist (DDS)', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'DENH', name: 'Dental Hygienist', required: ['license', 'diploma', ], optional: []},
                    {value: 'DENA', name: 'Dental Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'CHRA', name: 'Chiropractor Assistant', required: ['license', 'diploma', ], optional: []},
                    {value: 'CHRO', name: 'Chiropractor', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'RDIO', name: 'Radiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'CRDO', name: 'Cardiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'ANES', name: 'Anesthesiologist', required: ['license', 'diploma', 'resume'], optional: []},
                    {value: 'PRAY', name: 'Prayer Team', required: [], optional: []},
                    {value: 'PHLE', name: 'Phlebotomist', required: ['license', 'diploma'], optional: []},
                    {value: 'STEC', name: 'Surgical Tech', required: ['license', 'diploma'], optional: []}
                ],
                certified: ['MDPF','RESP','PHYS','PHYA','PHAT','PHAA','PHAR','OTEC','ODOC','OAST','DIET','NUTR','LACT','NAST',
	                'NTEC','NPRC','REGN','LPNN','MEDA','LVNN','HEDU','ETEC','MDFG','MDOC','DDOC','DENT','DENH','DENA',
	                'CHRA','CHRO','RDIO','CRDO','ANES', 'PHLE', 'STEC'],
                student: ['HEDU', 'MDSG', 'MDSN', 'MDSP', 'MDSD'],
                content: [
                    { id: 'role', q: 'Medical role', a:'', type: 'select', options: []},
                    { id: 'certifications', q: 'Certifications', a: [], type: 'checkbox', certifiedOptions: [
                        { name: 'Collect and review patient medical and family histories', value: false},
                        { name: 'Take blood pressure readings and record vital signs', value: false},
                        { name: 'Take blood glucose readings', value: false},
                        { name: 'Give injections', value: false},
                        { name: 'Distribute eyeglasses', value: false},
                        { name: 'Distribute pharmaceuticals', value: false},
                        { name: 'Assist in the dental clinic', value: false},
                        { name: 'Conduct dental cleanings', value: false},
                        { name: 'Run dental sterilization', value: false},
                        { name: 'Educate on hygiene and diet', value: false},
                        { name: 'Professional Counseling / Psychology', value: false},
                        { name: 'Chiropractic Care', value: false},
                        { name: 'Massage Therapy', value: false},
                        { name: 'X-ray Tech', value: false},
                        { name: 'Dermatology', value: false},
                        { name: 'Pediatrics', value: false},
                        { name: 'Other', value: false},
                    ], allOptions: [
                        { name: 'Register patients by going over their chief concern and record medical and family histories', value: false},
                        { name: 'Patient processing and clinic flow', value: false},
                        { name: 'Dental Sterilization', value: false},
                        { name: 'Dental Assisting', value: false},
                        { name: 'Patient Education', value: false},
                        { name: 'Pharmacy Assistant', value: false},
                        { name: 'Eyeglass Clinic', value: false},
                    ]},
//	                { conditions: ['student'], id: 'note', q: 'Note from Professor', a:[], type: 'file'},
	                { conditions: ['student'], q: 'Do you have clinic experience?', a:'', type: 'radio', options: [{name: 'Yes', value: 'yes'}, {name: 'No', value: 'no'}]},
	                { conditions: ['student'], q: 'Do you have any specializations?', a:'', type: 'textarea' },
                    {
                        id: 'files', q: '', a: {
                        letter: [],
                        certification: [],
                        license: [],
                        diploma: [],
                        resume: [],
                    }, type:'file'},
                ],
                expired_at: moment().add(1, 'y').add(1, 'days').startOf('day').format('YYYY-MM-DD'),
                show_expire: {
                    license: true,
                    certification: true,
                    diploma: false,
                    letter: false,
                    resume: false,
                },
                expires: {
                    license: null,
                    certification: null,
                    diploma: null,
                    letter: null,
                    resume: null,
                },

                uploads: [],
                upload_ids: [],
                uploadCounter: 1,
                attemptUploadDoc: false,
                showSaveAlert: false,

                resumeUploads: [],
                resumeUploadIds: [],

                letterUploads: [],
                letterUploadIds: [],

                // logic vars
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow: moment().add(1, 'days').format('YYYY-MM-DD'),
                resource: this.$resource('medical-credentials{/id}', { 'include': 'uploads'})
            }
        },
        computed: {
            rolesOrdered() {
                return _.sortBy(this.roles, 'name');
            },
            country_code(){
                return _.isObject(this.countryObj) ? this.countryObj.code : null;
            },
            isCertified(){
                return _.isObject(this.selectedRoleObj) && _.contains(this.certified, this.selectedRoleObj.value);
            },
            isStudent(){
                return _.isObject(this.selectedRoleObj) && _.contains(this.student, this.selectedRoleObj.value);
            },
            selectedRole(){
                let roleObj = _.findWhere(this.content, {id: 'role'}); // seems unnecessary but we should not assume the order of the data
	            return roleObj.a = _.isObject(this.selectedRoleObj) ? this.selectedRoleObj.value : null;
            },
            user_id(){
                return  _.isObject(this.userObj) ? this.userObj.id : this.$root.user.id;
            },
        },
        watch:{
			content: {
                handler(val, oldVal) {
                    let roleObj = _.findWhere(val, {id: 'role'}); // seems unnecessary but we should not assume the order of the data
                    if (_.isObject(this.selectedRoleObj) && this.selectedRoleObj.value !== roleObj.a) {
                        roleObj.a = this.selectedRoleObj.value;
                    }
                    // console.log('Content: ', val);
                },
                deep: true
            }
        },
        methods: {
            getUsers(search, loading){
                loading ? loading(true) : void 0;
                this.$http.get('users', { params: { search: search} }).then((response) => {
                    this.usersArr = response.data.data;
                    loading ? loading(false) : void 0;
                })
            },
            requiredByRole(group){
                return _.isObject(this.selectedRoleObj) && _.contains(this.selectedRoleObj.required, group);
            },
            optionalByRole(group){
                return _.isObject(this.selectedRoleObj) && _.contains(this.selectedRoleObj.optional, group);
            },
            uploadSatisfied(type){
                return !!_.findWhere(this.uploads, {type: type });
            },
            checkboxMinimum(array){
                return !_.findWhere(array, {value: true});
            },
            checkCheckbox(id) {
                // debugger;
            },
            back(force){
                if (this.isFormDirty && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                
                if (this.reservationId && this.requirementId) {
                    window.location.href = `/${this.firstUrlSegment}/reservations/${this.reservationId}/requirements/${this.requirementId}`;
                } else {
                    window.location.href = `/${this.firstUrlSegment}/records/medical-credentials/${this.id}`;
                }
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.showError = true;
                        this.$root.$emit('showError', 'Please check the form.');
                        return;
                    }

                    this.resource.post({}, {
                        applicant_name: this.applicant_name,
                        holder_id: this.user_id,
                        holder_type: 'users',
                        content: this.content,
                        expired_at: moment(this.expired_at).startOf('day').format('YYYY-MM-DD HH:mm:ss'),
                        uploads: _.uniq(this.upload_ids),
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Medical Credential created.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/medical-credentials/' + resp.data.data.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create medical release.');
                    });
                });
            },
            update(){
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return;
                    }

                    this.resource.put({id:this.id, include: 'uploads'}, {
                        applicant_name: this.applicant_name,
                        holder_id: this.user_id,
                        holder_type: 'users',
                        content: this.content,
                        expired_at: moment(this.expired_at).startOf('day').format('YYYY-MM-DD HH:mm:ss'),
                        uploads: _.uniq(this.upload_ids),
                        reservation_id: this.reservationId
                    }).then((resp) => {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(() =>  {
                            
                            if (that.reservationId && that.requirementId) {
                                window.location.href = `/${that.firstUrlSegment}/reservations/${that.reservationId}/requirements/${that.requirementId}`;
                            } else {
                                window.location.href = '/' + that.firstUrlSegment + '/records/medical-credentials/' + that.id;
                            }

                        }, 1000);
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                });
            },
            confirmUploadRemoval(upload){
                this.uploads.$remove(upload);
                this.upload_ids.$remove(upload.id);
	            // remove upload data from content
                _.findWhere(this.content, { id: 'files'}).a[upload.type].$remove(upload)

            },
            checkConditions(question){
                // skip files and role question
	            if (_.contains(['files', 'role', 'note'], question.id))
	                return false;

                if (question.hasOwnProperty('conditions')) {
                    // handles multiple conditions
                    if (!_.isObject(this.selectedRoleObj))
                        return false;

	                // if any condition is not met, return false
	                if (_.contains(question.conditions, 'student')) {
                        if (!this.isStudent) {
                            return false;
                        }
                    }

	                return true;
                } else return true;
            },
	        toUnderscoreString(string){
                return string.toLowerCase().replace(/\s/g, '_');
	        },
	        uploadDoc(scope, type){
                this.attemptUploadDoc = true;
                this.$validator.validateAll(scope).then(result => {
                    if (result && (this.expires[type] === null || moment(this.expires[type]).isValid())) {
                        this.$root.$emit('upload-' + type);
                        this.attemptUploadDoc = false;
                    }
                })

	        },
	        dateIsValid(date){
                return moment(date).isValid();
	        },

            uploadsComplete(data){
                let contentIndex;
                switch(data.type){
                    case 'other':
                        this.upload_ids.push(data.id);
                        this.upload_ids = _.uniq(this.upload_ids);
                        contentIndex = _.findIndex(this.content, {id: 'files'});

                        if (data.name.indexOf('license') !== -1) {
                            this.content[contentIndex].a.license.push({ id: data.id, name: data.name, expires: this.expires.license });
                            this.uploads.push({ id: data.id, name: data.name, can_expire: false, expires: this.expires.license, type: 'license'});
                            this.expires.license = null;
                            break;
                        } else if (data.name.indexOf('certification') !== -1) {
                            this.content[contentIndex].a.certification.push({ id: data.id, name: data.name, expires: this.expires.certification});
                            this.uploads.push({ id: data.id, name: data.name, can_expire: false, expires: this.expires.certification, type: 'certification'});
                            this.expires.certification = null;
                            break;
                        } else if (data.name.indexOf('diploma') !== -1) {
                            this.content[contentIndex].a.diploma.push({ id: data.id, name: data.name, expires: this.expires.diploma});
                            this.uploads.push({ id: data.id, name: data.name, can_expire: false, expires: this.expires.diploma, type: 'diploma'});
                            this.expires.diploma = null;
                            break;
                        } else if (data.name.indexOf('letter') !== -1) {
                            this.content[contentIndex].a.letter.push({ id: data.id, name: data.name, expires: null});
                            this.uploads.push({ id: data.id, name: data.name, can_expire: false, expires: this.expires.letter, type: 'letter'});
                            break;
                        } else {
                            // minor failsafe
                            contentIndex = _.findIndex(this.content, { id: 'files'});
                            this.content[contentIndex].a.other = this.content[contentIndex].a.other || [];
                            this.content[contentIndex].a.push({ id: data.id, expires: this.expires, type: 'other'});
                            this.content[contentIndex].a.other = _.uniq(this.content[contentIndex].a);
                            break;
                        }
                    case 'file':
                        this.upload_ids.push(data.id);
                        this.upload_ids = _.uniq(this.upload_ids);
                        contentIndex = _.findIndex(this.content, {id: 'files'});

                        if (data.name.indexOf('resume') !== -1) {
                            this.content[contentIndex].a.resume.push({ id: data.id, name: data.name, expires: null});
                            this.uploads.push({ id: data.id, name: data.name, can_expire: false, expires: this.expires.resume, type: 'resume'});
                            break;
                        } else {
                            // minor failsafe
                            contentIndex = _.findIndex(this.content, { id: 'files'});
                            this.content[contentIndex].a.other = this.content[contentIndex].a.other || [];
                            this.content[contentIndex].a.push({ id: data.id, expires: this.expires, type: 'other'});
                            this.content[contentIndex].a.other = _.uniq(this.content[contentIndex].a);
                            break;
                        }
                    default:
                        this.$root.$emit('showError', 'Wrong file type, please try another file...');
                        break;
                }
                this.uploadCounter++;
            }

        },
        mounted(){
                if (this.isUpdate) {
                    this.resource.get({ id: this.id, include: 'holder'}).then((response) => {
                        let credential = response.data.data;
                        this.applicant_name = credential.applicant_name;
                        this.content = credential.content;
                        this.expired_at = credential.expired_at;
                        this.userObj = credential.user;
                        this.usersArr.push(this.userObj);
                        this.selectedRoleObj = _.findWhere(this.roles, { value: _.findWhere(credential.content, { id: 'role'}).a});
                        let filesArr = _.findWhere(credential.content, { id: 'files'}).a;
                        _.each(filesArr, (list, index) => {
	                        _.each(list, (obj) => {
	                            obj.type = index;
		                        this.uploads.push(obj);
		                        this.upload_ids.push(obj.id);
                            })
                        });
                    });
                }
        }
    }
</script>

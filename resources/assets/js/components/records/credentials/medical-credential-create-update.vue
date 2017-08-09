<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
	<div style="position:relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>

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
                                    <v-select @keydown.enter.prevent="" class="form-control" id="infoManager" :value.sync="userObj" :options="usersArr" :on-search="getUsers" label="name"></v-select>
                                    <select hidden name="manager" id="infoManager" class="hidden" v-model="user_id" v-validate="'required'">
                                        <option :value="user.id" v-for="user in usersArr">{{user.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </template>

                        <div class="row">
    						<div class="col-sm-6" v-error-handler="{ value: applicant_name, handle: 'name', messages: { req: 'Please provide credential holder\'s name.'} }">
    							<label for="name" class="control-label">Credential Holder's Name</label>
    							<input type="text" class="form-control" name="name" id="name" v-model="applicant_name"
    							       placeholder="Name" name="name" v-validate="{ required: true, minlength:1 }"
    							       minlength="1" required>
    						</div>
                             <div class="col-sm-6" v-error-handler="{ value: selectedRole, handle: 'role', messages: { req: 'Please select a medical role.'} }">
                                <label class="control-label">Medical Role</label>
	                             <v-select @keydown.enter.prevent="" class="form-control" id="group" :value.sync="selectedRoleObj"
	                                       :options="roles|orderBy 'name'" label="name" placeholder="Select Role"></v-select>
	                             <select hidden class="form-control hidden" v-model="selectedRole" name="role" v-validate="'required'">
		                             <option value="">-- Select Role --</option>
		                             <option v-for="option in roles|orderBy 'name'" :value="option.value">{{option.name}}</option>
	                             </select>
	                             <div class="errors-block"></div>
                             </div>
                        </div>
                    </div>
                </div>
                
			    <div v-for="(indexQA, QA) in content">
                    
                    <!-- start has type designation -->
					<template v-if="QA.type && checkConditions(QA)">
                            
                        <!-- radio -->
						<template v-if="QA.type === 'radio'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('radio' + indexQA), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<label class="radio-inline" v-for="choice in QA.options">
										<input type="radio" :value="choice.value" v-model="QA.a" :field="'radio' + indexQA  " v-validate="$index === 0 ?['required'] : void 0"> {{ choice.name }}
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

							<!--<template v-for="role in QA.roleOptions" v-show="role.id === selectedRole">
								<div class="checkbox col-sm-6 col-xs-12" v-for="choice in role.options">
									<label>
										&lt;!&ndash;<checkbox :checked.sync="choice.value" :value="true">{{ choice.name }}</checkbox>&ndash;&gt;
										<input type="checkbox" :value="true" v-model="choice.value"> {{ choice.name }}
                                    </label>
								</div>
							</template>-->
                            
                            <!-- start certification checklist -->
							<template v-if="selectedRoleObj && !!isCertified && !isStudent">
								<div class="panel panel-default" v-error-handler="{ value: QA.certifiedOptions, client: 'certifications', class: 'panel-danger has-error', messages: { min: 'Please select at least one role.'} }">
									<div class="panel-heading">
										<h5>I am certified in the following:</h5>
									</div>
									<div class="panel-body">
										<div class="row">
											<div class="checkbox col-sm-6 col-xs-12" v-for="choice in QA.certifiedOptions">
												<label>
													<input type="checkbox" :checked.sync="choice.value" :value="true" v-model="choice.value" name="certifications" v-validate="$index === 0 ?{ minlength: 1 } : void 0">
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
                            <div class="panel panel-default" v-error-handler="{ value: QA.allOptions, client: 'participations', class: 'panel-danger has-error', messages: { min: 'Please select at least one role.'} }">
                                <div class="panel-heading">
                                    <h5>I am willing to participate in the following:</h5>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
    									<div class="checkbox col-sm-6 col-xs-12" v-for="choice in QA.allOptions">
    										<label>
											    <input type="checkbox" :checked.sync="choice.value" :value="true" v-model="choice.value" name="participations" v-validate="$index === 0 ?{ minlength: 1 } : void 0">
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
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('textarea' + $index), class: 'panel-danger has-error', messages: { req: 'Please explain.'} }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<span class="help-block">Please Explain:</span>
									<textarea class="form-control" v-model="QA.a" :field="'textarea' + $index" v-validate="'required'"></textarea>
								</div>
								<div class="panel-footer" v-show= "errors.has('textarea' + $index)">
									<div class="errors-block"></div>
								</div>
							</div>
						</template>
                        <!-- end textarea -->
                        
                        <!-- start select -->
						<template v-if="QA.type === 'select' && QA.id !== 'role'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('select' + $index), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<select class="form-control" v-model="QA.a" :field="'select' + $index" v-validate="">
										<option value="">-- Select Role --</option>
										<option v-for="option in QA.options" :value="option.value">{{option.name}}</option>
									</select>
								</div>
								<div class="panel-footer" v-show= "errors.has('select' + $index)">
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
            								<upload-create-update v-show="uploads.length < 1" type="file" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :name="'credentials-professor-note-'+ today + '-' + uploadCounter"></upload-create-update>
                                        </div>
                                    </div>
                                </div>
                            </div>
						</template>
                        <!-- end file -->
                        
                        <!-- start date -->
						<template v-if="QA.type === 'date'">
							<div class="panel panel-default" v-error-handler="{ value: QA.a, client: ('date' + $index), class: 'panel-danger has-error' }">
								<div class="panel-heading">
									<h5 v-text="QA.q"></h5>
								</div>
								<div class="panel-body">
									<date-picker :model.sync="QA.a|moment('YYYY-MM-DD')" type="date" ></date-picker>
									<input type="datetime" class="form-control hidden" v-model="QA.a | moment('LLLL')" id="started_at" required :field="'date' + $index" v-validate="">
								</div>
								<div class="panel-footer" v-show= "errors.has('date' + $index)">
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

									<form id="UploadLicenseForm">
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
											<upload-create-update type="other" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-license" :name="'credentials-license-'+ today + '-' + uploadCounter"></upload-create-update>
											<a @click="uploadDoc('UploadLicenseForm', 'license')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.license" class="col-sm-6" :class="{ 'has-error': checkForUploadDocError('UploadLicenseForm', 'licenseexpires') || dateIsValid('UploadLicenseForm', expires.license)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="checkForUploadDocError('UploadLicenseForm', 'licenseexpires') || dateIsValid('UploadLicenseForm', expires.license)" :model.sync="expires.license|moment('YYYY-MM-DD')" type="date" ></date-picker>
												<input type="datetime" class="form-control hidden" name="licenseexpires="['required']" v-model="expires.license" id" v-validate="licenseexpires" required>
											</div>
										</div>
										<!-- </div> -->
									</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('certification') || optionalByRole('certification')) && !uploadSatisfied('certification')">
                                <hr class="divider">

								<form id="UploadCertificationForm">
									<div class="col-xs-12">
										<div class="col-sm-6"><label>Certification</label></div>
										<div class="col-sm-6">
											<label>
												<input type="checkbox" v-model="show_expire.certification"> This document Expires
                                            </label>
										</div>

									</div>
									<div class="col-sm-6">
										<upload-create-update type="other" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-certification" :name="'credentials-certification-'+ today + '-' + uploadCounter"></upload-create-update>
										<a @click="uploadDoc('UploadCertificationForm', 'certification')" class="btn btn-primary">Attach</a>
									</div>
									<div v-if="show_expire.certification" class="col-sm-6" :class="{ 'has-error': checkForUploadDocError('UploadCertificationForm', 'certexpires') || dateIsValid('UploadCertificationForm', expires.certification)}">
										<label class="control-label">Expires</label>
										<div>
											<date-picker :has-error="checkForUploadDocError('UploadCertificationForm', 'certexpires') || dateIsValid('UploadCertificationForm', expires.certification)" :model.sync="expires.certification|moment('YYYY-MM-DD')" type="date" ></date-picker>
											<input type="datetime" class="form-control hidden" name="certexpires="['required']" v-model="expires.certification" id" v-validate="certexpires" required>
										</div>
									</div>
								</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('diploma') || optionalByRole('diploma')) && !uploadSatisfied('diploma')">
                                <hr class="divider">

								<form id="UploadDiplomaForm">
									<div class="col-xs-12">
										<div class="col-sm-6"><label>Diploma</label></div>
										<div class="col-sm-6">
											<label>
												<input type="checkbox" v-model="show_expire.diploma"> This document Expires
                                            </label>
										</div>
									</div>
									<div class="col-sm-6">
										<upload-create-update type="other" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-diploma" :name="'credentials-diploma-'+ today + '-' + uploadCounter"></upload-create-update>
										<a @click="uploadDoc('UploadDiplomaForm', 'diploma')" class="btn btn-primary">Attach</a>
									</div>
									<div class="col-sm-6" v-if="show_expire.diploma" :class="{ 'has-error': checkForUploadDocError('UploadDiplomaForm', 'diplomaexpires') || dateIsValid('UploadDiplomaForm', expires.diploma)}">
										<label class="control-label">Expires</label>
										<div>
											<date-picker :has-error="checkForUploadDocError('UploadDiplomaForm', 'diplomaexpires') || dateIsValid('UploadDiplomaForm', expires.diploma)" :model.sync="expires.diploma|moment('YYYY-MM-DD')" type="date" ></date-picker>
											<input type="datetime" class="form-control hidden" name="diplomaexpires="['required']" v-model="expires.diploma" id" v-validate="diplomaexpires" required>
										</div>
									</div>
								</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('letter') || optionalByRole('letter')) && !uploadSatisfied('letter')">
                                <hr class="divider">

									<form id="UploadLetterForm">
										<div class="col-xs-12">
											<div class="col-sm-6"><label>Note from Professor</label></div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" v-model="show_expire.letter"> This document Expires
	                                            </label>
											</div>

										</div>
										<div class="col-sm-6">
											<upload-create-update type="other" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-letter" :name="'credentials-letter-'+ today + '-' + uploadCounter"></upload-create-update>
											<a @click="uploadDoc('UploadLetterForm', 'letter')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.letter" class="col-sm-6" :class="{ 'has-error': checkForUploadDocError('UploadLetterForm', 'letterexpires') || dateIsValid('UploadLetterForm', expires.letter)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="checkForUploadDocError('UploadLetterForm', 'letterexpires') || dateIsValid('UploadLetterForm', expires.letter)" :model.sync="expires.letter|moment('YYYY-MM-DD')" type="date" ></date-picker>
												<input type="datetime" class="form-control hidden" name="letterexpires="['required']" v-model="expires.letter" id" v-validate="letterexpires" required>
											</div>
										</div>
									</form>

							</div>
							<div class="col-sm-12" v-if="(requiredByRole('resume') || optionalByRole('resume')) && !uploadSatisfied('resume')">
                                <hr class="divider">

									<form id="UploadResumeForm">
										<div class="col-sm-12">
											<div class="col-sm-6"><label>Resume</label></div>
											<div class="col-sm-6">
												<label>
													<input type="checkbox" v-model="show_expire.resume"> This document Expires
	                                            </label>
											</div>
										</div>
										<div class="col-sm-6">
											<upload-create-update type="file" :lock-type="true" :ui-selector="2" :ui-locked="true" :is-child="true" :tags="['User']" button-text="Attach" :allow-name="false" :hide-submit="true" submit-event="upload-resume" :name="'credentials-resume-'+ today + '-' + uploadCounter"></upload-create-update>
											<a @click="uploadDoc('UploadResumeForm', 'resume')" class="btn btn-primary">Attach</a>
										</div>
										<div v-if="show_expire.resume" class="col-sm-6" :class="{ 'has-error': checkForUploadDocError('UploadResumeForm', 'resumeexpires') || dateIsValid('UploadResumeForm', expires.resume)}">
											<label class="control-label">Expires</label>
											<div>
												<date-picker :has-error="checkForUploadDocError('UploadResumeForm', 'resumeexpires') || dateIsValid('UploadResumeForm', expires.resume)" :model.sync="expires.resume|moment('YYYY-MM-DD')" type="date" ></date-picker>
												<input type="datetime" class="form-control hidden" name="resumeexpires="['required']" v-model="expires.resume" id" v-validate="resumeexpires" required>
											</div>
										</div>
									</form>

							</div>
						</div>
					</div>
				</div>

				<div class="form-group text-center">
					<div class="col-xs-12">
						<a v-if="!isUpdate" href="/{{ firstUrlSegment }}/records/medical-credentials" class="btn btn-default">Cancel</a>
						<a v-if="!isUpdate" @click="submit()" class="btn btn-primary">Create</a>
						<a v-if="isUpdate" @click="back()" class="btn btn-default">Cancel</a>
						<a v-if="isUpdate" @click="update()" class="btn btn-primary">Update</a>
					</div>
				</div>
			</form>

			<modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Cost" small="true">
				<div slot="modal-body" class="modal-body text-center" v-if="selectedItem">Delete {{ selectedItem.name }}?</div>
				<div slot="modal-footer" class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
					<button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Delete</button>
				</div>
			</modal>
			<modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
				<div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
			</modal>

	</div>

</template>
<script type="text/javascript">
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
            }
        },
        data(){
            return {
                // mixin settings
                validatorHandle: 'CreateUpdateMedicalCredential',

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
                    {value: 'PHYA', name: 'Physican\'s Assistant', required: ['license', 'diploma', 'resume'], optional: []},
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
                    {value: 'PRAY', name: 'Prayer Team', required: [], optional: []}
                ],
                certified: ['MDPF','RESP','PHYA','PHAT','PHAA','PHAR','OTEC','ODOC','OAST','DIET','NUTR','LACT','NAST',
	                'NTEC','NPRC','REGN','LPNN','MEDA','LVNN','HEDU','ETEC','MDFG','MDOC','DDOC','DENT','DENH','DENA',
	                'CHRA','CHRO','RDIO','CRDO','ANES'],
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

                resumeUploads: [],
                resumeUploadIds: [],

                letterUploads: [],
                letterUploadIds: [],

                // logic vars
                selectedAvatar: null,
                today: moment().format('YYYY-MM-DD'),
                yesterday: moment().subtract(1, 'days').format('YYYY-MM-DD'),
                tomorrow: moment().add(1, 'days').format('YYYY-MM-DD'),
                resource: this.$resource('credentials/medical{/id}', { 'include': 'uploads'})
            }
        },
        computed: {
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
                handler: function (val, oldVal) {
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
                this.$http.get('users', { params: { search: search} }).then(function (response) {
                    this.usersArr = response.body.data;
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
            onTouched(){
                this.hasChanged = true;
            },
            checkboxMinimum(array){
                return !_.findWhere(array, {value: true}) && this.attemptSubmit;
            },
            checkCheckbox(id) {
                debugger;
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/'+ this.firstUrlSegment +'/records/medical-credentials/';
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.resetErrors();
//                let checkboxTest = _.findWhere(this.content, {id:'certifications'}).certifiedOptions && _.findWhere(this.content, {id:'certifications'}).allOptions;
                if (this.$CreateUpdateMedicalCredential.valid) {
                    this.resource.save(null, {
                        applicant_name: this.applicant_name,
                        holder_id: this.user_id,
                        holder_type: 'users',
                        content: this.content,
                        expired_at: moment(this.expired_at).startOf('day').format('YYYY-MM-DD HH:mm:ss'),
                        uploads: _.uniq(this.upload_ids),
                    }).then(function (resp) {
                        this.$root.$emit('showSuccess', 'Medical Credential created.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/'+ that.firstUrlSegment +'/records/medical-credentials/' + resp.data.data.id;
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to create medical release.');
                    });
                } else {
                    this.showError = true;
                    this.$root.$emit('showError', 'Please check the form.');
                }
            },
            update(){
                if ( _.isFunction(this.$validate) )
                    this.$validate(true);

                this.resetErrors();
//                let checkboxTest = _.findWhere(this.content, {id:'certifications'}).certifiedOptions && _.findWhere(this.content, {id:'certifications'}).allOptions;
                if (this.$CreateUpdateMedicalCredential.valid) {
                    this.resource.update({id:this.id, include: 'uploads'}, {
                        applicant_name: this.applicant_name,
                        holder_id: this.user_id,
                        holder_type: 'users',
                        content: this.content,
                        expired_at: moment(this.expired_at).startOf('day').format('YYYY-MM-DD HH:mm:ss'),
                        uploads: _.uniq(this.upload_ids),
                    }).then(function (resp) {
                        this.$root.$emit('showSuccess', 'Changes saved.');
                        let that = this;
                        setTimeout(function () {
                            window.location.href = '/'+ that.firstUrlSegment +'/records/medical-credentials/' + that.id;
                        }, 1000);
                    }, function (error) {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'Unable to save changes.');
                    });
                } else {
                    this.$root.$emit('showError', 'Please check the form.');
                }
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
	        uploadDoc(form, type){
                this.attemptUploadDoc = true;
	            if (this['$' + form].valid && moment(this.expires).isValid()) {
					this.$root.$emit('upload-' + type);
                    this.attemptUploadDoc = false;
	            }
	        },
            checkForUploadDocError(form, field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return _.isString(field) && this['$' + form].invalid && this.attemptUploadDoc;
            },
	        dateIsValid(date){
                return moment(date).isValid();
	        },
            syncCheckboxes() {
	            let self = this;
				this.$nextTick(function () {
                    _.each($('input[type=checkbox]'), function (checkbox) {
//	                    if (checkbox.hasAttribute('checked'))
	                        checkbox.checked = checkbox.hasAttribute('checked');
                    });
                    self.$validate('certifications', true);
                    self.$validate('participations', true);
//					debugger;
//                  let certifications = _.findWhere(this.content, { id: 'files'})
                });
            }

        },
        events:{
            'uploads-complete'(data){
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
                        break;
	                default:
	                    this.$root.$emit('showError', 'Wrong file type, please try another file...');
	                    break;
                }
                this.uploadCounter++;
            }
        },
        mounted(){
            // set user data
            // this.userId = this.holder_id = this.$root.user.id;
            // this.holder_type = 'users';
            /*let teamRolesPromise = this.$http.get('utilities/team-roles/medical').then(function (response) {
                _.each(response.body.roles, function (name, key) {
                    this.roles.push({ value: key, name: name});
                }.bind(this));
            });*/

            // this.$refs.spinner.show();
            //Promise.all([teamRolesPromise]).then(function (values) {
                if (this.isUpdate) {
                    this.resource.get({ id: this.id, include: 'holder'}).then(function (response) {
                        let credential = response.body.data;
                        this.applicant_name = credential.applicant_name;
                        // this.holder_id = credential.holder_id;
                        // this.holder_type = credential.holder_type;
                        this.content = credential.content;
                        this.syncCheckboxes();
                        this.expired_at = credential.expired_at;
                        this.userObj = credential.holder.data;
                        this.usersArr.push(this.userObj);

                        //assign fields from content data
                        //console.log(_.findWhere(credential.content, { id: 'role'}));
                        this.selectedRoleObj = _.findWhere(this.roles, { value: _.findWhere(credential.content, { id: 'role'}).a});

                        this.$activateValidator();
                        // until uploads relationship is added...
                        // gather all uploads ids
                        let ids = [];
                        let filesArr = _.findWhere(credential.content, { id: 'files'}).a;
                        _.each(filesArr, function (list, index) {
	                        _.each(list, function (obj) {
	                            obj.type = index;
		                        this.uploads.push(obj);
		                        this.upload_ids.push(obj.id);
                            }.bind(this))
                        }.bind(this));
                    });
                } else {
                    this.$activateValidator();
                }
            //}.bind(this));
        }
    }
</script>

<template>
    <div style="position:relative">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <template v-if="isUser()">
            <!--<div class="panel panel-default">-->
                <!--<div class="panel-body">-->
                    <form>
                        <div class="form-group">
                            <div class="panel panel-default panel-body">
                                <div class="row">
                                    <div class="col-sm-4">
                                        Update Fundraiser
                                        <hr class="inv divider visible-xs-block" />
                                    </div>
                                    <div class="col-sm-8 text-right">
                                        <div role="group" aria-label="...">
                                            <a class="btn btn-xs btn-default-hollow" @click="newMarkedContentToggle = !newMarkedContentToggle">
                                                <span v-show="!newMarkedContentToggle">Preview</span>
                                                <span v-show="newMarkedContentToggle"><i class="fa fa-pencil"></i> Edit</span>

                                            </a>
                                            <span class="form-group" v-if="fundraiser" v-show="description !== fundraiser.description">
                                                <button class="btn btn-xs btn-default-hollow small" type="button" @click="reset">Cancel</button>
                                                <button class="btn btn-xs btn-info" type="button" @click="saveDescription">Publish</button>
                                            </span>
                                            <a class="btn btn-xs btn-default-hollow" @click="settingsModal = true"><i class="fa fa-cog"></i> Settings</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="divider inv">
                        <textarea v-show="!newMarkedContentToggle" class="form-control" id="newStoryContent" v-model="description" minlength="1" rows="20"></textarea>
                        <div class="collapse" :class="{ 'in': newMarkedContentToggle }">
                            <div v-html="description | marked"></div>
                        </div>
                    </form>
                <!--</div>-->
            <!--</div>-->

            <modal v-if="fundraiser" title="Fundraiser Settings" :show.sync="settingsModal" effect="zoom" width="400" :callback="saveSettings">
                <div slot="modal-body" class="modal-body">
                    <validator name="FundraiserSettings">
                        <form id="FundraiserSettingsForm">
                            <div class="form-group">
                                <label for="name">Fundraiser Name</label>
                                <input type="text" class="form-control" id="name" v-model="fundraiser.name">
                            </div>
                            <div class="form-group" :class="{ 'has-error': validUrl === false, 'has-success': validUrl === true }">
                                <label for="url">Fundraiser Url</label>
                                <div class="input-group">
                                <span class="input-group-addon" id="url_addon">
                                    <i class="fa fa-spinner fa-spin" v-if="checkingUrl"></i>
                                    <i class="fa fa-check" v-if="checkingUrl === false && validUrl === true"></i>
                                    <i class="fa fa-times" v-if="checkingUrl === false && validUrl === false"></i>
                                </span>
                                    <input type="text" class="form-control" id="url" v-model="fundraiser.url" debounce="500" @change="validateUrl">
                                </div>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="fundraiser.public"> Make Public
                                </label>
                                <span class="help-block">Private fundraisers can only be accessed by you.</span>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="fundraiser.show_donors" @change="toggleDisplayDonors(fundraiser.show_donors)"> Show Donor/Donation List
                                </label>
                            </div>
                        </form>
                    </validator>
                </div>
            </modal>

            <alert :show.sync="showDescriptionSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Yay!</strong>
                <p>Description updated!</p>
            </alert>

            <alert :show.sync="showSettingsSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Yay!</strong>
                <p>Settings updated!</p>
            </alert>
        </template>

        <template v-else>
            <div v-html="fundraiser.description | marked"></div>
        </template>
    </div>
</template>
<script type="text/javascript">
    var marked = require('marked');
    export default{
        name: 'fundraisers-manager',
        props: ['id', 'sponsorId'],
        data(){
            return {
                description: '',
                showDescriptionSuccess: false,
                showSettingsSuccess: false,
                newMarkedContentToggle: true,
                fundraiser: {
                    description: ''
                },
                resource: this.$resource('fundraisers{/id}'),

                // settings vars
                settingsModal: false,
                checkingUrl: false,
                validUrl: true,
            }
        },
        watch: {
            'fundraiser': function (val, oldVal) {
                this.description = val.hasOwnProperty('description') ? val.description : '';
                // watch url value for checking
                if (val.hasOwnProperty('url') && oldVal.hasOwnProperty('url') && val.url !== oldVal.url) {
                 	debugger;
				}

                // watch url value for checking
                if (val.hasOwnProperty('show_donors') && oldVal.hasOwnProperty('show_donors') && val.show_donors !== oldVal.show_donors) {
                 	debugger;
				}
            }
        },
        filters: {
            marked: marked,
        },
        methods: {
            isUser(){
                return this.sponsorId === this.$root.user.id;
            },
            reset(){
                this.description = this.fundraiser.description;
            },
            saveDescription(){
                this.fundraiser.description = this.description;
                this.doUpdate('description');
            },
			toggleDisplayDonors(val) {
				this.$root.$emit('Fundraiser:DisplayDonors', val);
			},
            validateUrl(){
                this.checkingUrl = true;
                this.$http.get('fundraisers', { url: this.fundraiser.url }).then(function (response) {
                    if (response.data.data.length) {
                        this.validUrl = response.data.data[0].id === this.fundraiser.id;
                    } else {
                        this.validUrl = true;
                    }

                    // validat empty url string for private fundraisers
                    if (this.fundraiser.public === false && this.fundraiser.url === '') {
                        this.validUrl = true;
                    }
                    this.checkingUrl = false;
                }, function (error) {
                    console.log(error);
                });
            },
            saveSettings(){
                if (this.validUrl) {
                    this.fundraiser.description = this.description;
                    this.doUpdate('settings');
                }
            },
            doUpdate(type){
                // this.$refs.spinner.show();
                this.resource.update({id: this.id}, this.fundraiser).then(function (response) {
                    this.fundraiser = response.data.data;
                    this.newMarkedContentToggle = true;
                    if (type === 'description') {
                        this.showDescriptionSuccess = true;
                    } else {
                        this.showSettingsSuccess = true;
                        // page refresh might be necessary for updated url
                    }
                    // this.$refs.spinner.hide();
                }, function (error) {
                    // this.$refs.spinner.hide();
                    //TODO add error alert
                });
            }
        },
        ready(){
            // this.$refs.spinner.show();
            this.resource.get({id: this.id}).then(function (response) {
                this.fundraiser = response.data.data;
				this.$root.$emit('Fundraiser:DisplayDonors', this.fundraiser.show_donors);
                // this.$refs.spinner.hide();
            });
        }
    }
</script>

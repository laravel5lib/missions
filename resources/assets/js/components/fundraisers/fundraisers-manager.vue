<template>
    <div style="position:relative">
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <template v-if="isUser">
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
                                            <button class="btn btn-default-hollow btn-xs" type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                                Examples
                                            </button>

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
                            <div v-html="marked(description)"></div>
                        </div>
                    </form>
                <!--</div>-->
            <!--</div>-->

            <modal v-if="fundraiser" title="Fundraiser Settings" :value="settingsModal" @closed="settingsModal=false" effect="zoom" width="400" :callback="saveSettings">
                <div slot="modal-body" class="modal-body">

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
                                    <input type="text" class="form-control" id="url" v-model="fundraiser.url" @change="debouncedValidateUrl">
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

                </div>
            </modal>

            <alert v-model="showDescriptionSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Good job!</strong>
                <p>Description updated</p>
            </alert>

            <alert v-model="showSettingsSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Good job!</strong>
                <p>Settings updated</p>
            </alert>

            <markdown-example-modal></markdown-example-modal>

        </template>

        <template v-else>
            <div v-html="marked(fundraiser.description)"></div>
        </template>


    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore'
    var marked = require('marked');
    export default{
        name: 'fundraisers-manager',
        props: ['id', 'sponsorId', 'editable'],
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
        computed: {
            isUser(){
                if (this.editable === 1) return true;

                return this.$root.user && this.sponsorId === this.$root.user.id;
            },
        },
        watch: {
            'fundraiser'(val, oldVal) {
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
        methods: {
            marked: marked,

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
            debouncedValidateUrl: _.debounce(function () {
                this.validateUrl()
            }, 500),
            validateUrl(){
                this.checkingUrl = true;
                this.$http.get('fundraisers', { params: { url: this.fundraiser.url } }).then((response) => {
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
                }, (error) =>  {
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
                this.resource.put({id: this.id}, {
                    name: this.fundraiser.name,
                    url: this.fundraiser.url,
                    description: this.fundraiser.description,
                    public: this.fundraiser.public,
                    show_donors: this.fundraiser.show_donors
                }).then((response) => {
                    this.fundraiser = response.data.data;
                    this.newMarkedContentToggle = true;
                    if (type === 'description') {
                        this.showDescriptionSuccess = true;
                    } else {
                        this.showSettingsSuccess = true;
                        this.$dispatch('fundraiserSettingsChanged', response.data.data);
                        // page refresh might be necessary for updated url
                    }
                    // this.$refs.spinner.hide();
                }, this.$root.handleApiError);
            }
        },
        mounted(){
            // this.$refs.spinner.show();
            this.resource.get({id: this.id}).then((response) => {
                this.fundraiser = response.data.data;
				this.$root.$emit('Fundraiser:DisplayDonors', this.fundraiser.show_donors);
                // this.$refs.spinner.hide();
            });
        }
    }
</script>

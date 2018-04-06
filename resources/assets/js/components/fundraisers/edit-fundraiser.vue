<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Edit Fundraiser</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <h5>Fundraiser Details</h5>
                    <p class="text-muted">You can modify important details about your fundraiser. Be careful, some changes can have a major effect!</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group" v-error-handler="{ value: fundraiser.name, handle: 'name' }">
                        <div class="col-xs-12">
                            <label for="name">Give your Fundraiser A Name</label>
                            <input type="text"
                                   class="form-control"
                                   name="name"
                                   id="name"
                                   v-model="fundraiser.name"
                                   debounce="250"
                                   placeholder="Fundraiser Name"
                                   v-validate="'required|min:1|max:100'"
                                   maxlength="100"
                                   minlength="1"
                                   required>
                                <hr class="divider inv">
                        </div>
                    </div>
                    <div class="form-group" :class="{ 'has-error': ui.validUrl === false, 'has-success': ui.validUrl === true }">
                        <div class="col-xs-12">
                            <label for="url">Fundraiser Link</label>
                            <div class="input-group">
                                <span class="input-group-addon">
                                    missions.me/
                                </span>
                                <input type="text" class="form-control" id="url" v-model="fundraiser.url" @change="debouncedValidateUrl">
                                <span class="input-group-addon" id="url_addon">
                                    <i class="fa fa-spinner fa-spin" v-if="ui.checkingUrl"></i>
                                    <i class="fa fa-check" v-if="ui.checkingUrl === false && ui.validUrl === true"></i>
                                    <i class="fa fa-times" v-if="ui.checkingUrl === false && ui.validUrl === false"></i>
                                </span>
                            </div>
                            <span class="help-block" v-if="! ui.validUrl">This link is being used by another fundraiser. Please try a different one.</span>
                            <span class="help-block" v-else>Copy &amp; Paste to share this link inside of emails to spread the word!</span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-4">
                    <h5>Privacy Options</h5>
                    <p class="text-muted">Change who can see your fundraiser and what appears on it.</p>
                </div>
                <div class="col-sm-8">

                    <div class="form-group">
                        <div class="col-xs-12">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="fundraiser.public"> Show Fundraiser to the Public (Highly Recommended)
                                </label>
                                <span class="help-block">Private fundraisers can only be seen by you and cannot be shared.</span>
                            </div>
                            <hr class="divider inv">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="fundraiser.show_donors" @change="toggleDisplayDonors(fundraiser.show_donors)"> Show the Donor/Donation List (Recommended)
                                </label>
                                <span class="help-block">Seeing donation activity could encourage others to give.</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <hr class="divider">
            <div class="row">
                <div class="col-sm-4">
                    <h5>Tell Your Story</h5>
                    <p class="text-muted">
                        <a href="#">Click here</a> for some tips on writing a great story.
                    </p>
                    <!-- <ul class="text-muted list-unstyled">
                        <li><i class="fa fa-check-circle-o" style="padding-right: 1em"></i> Describe who will benefit</li>
                        <li><i class="fa fa-check-circle-o" style="padding-right: 1em"></i> Detail what the funds will be used for</li>
                        <li><i class="fa fa-check-circle-o" style="padding-right: 1em"></i> Explain how soon you need the funds</li>
                        <li><i class="fa fa-check-circle-o" style="padding-right: 1em"></i> Talk about what the support will mean to you</li>
                        <li><i class="fa fa-check-circle-o" style="padding-right: 1em"></i> Share how grateful you will be for help</li>
                    </ul> -->
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Short Fundraiser Description</label>
                            </div>
                            <div class="col-sm-8 text-right">
                                <div role="group" aria-label="...">
                                    <a class="btn btn-xs btn-link" @click="ui.newMarkedContentToggle = !ui.newMarkedContentToggle">
                                        <span v-show="!ui.newMarkedContentToggle">Preview</span>
                                        <span v-show="ui.newMarkedContentToggle"><i class="fa fa-pencil"></i> Edit</span>

                                    </a>
                                    <button class="btn btn-link btn-xs" type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                        <i class="fa fa-question-circle"></i> Help
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="divider inv">
                    <textarea v-show="!ui.newMarkedContentToggle"
                              class="form-control"
                              id="newStoryContent"
                              v-model="fundraiser.description"
                              v-autosize="fundraiser.description"
                              minlength="1"
                              rows="20"
                              style="resize: vertical;">
                    </textarea>
                    <div class="collapse" :class="{ 'in': ui.newMarkedContentToggle }">
                        <div v-html="marked(fundraiser.description)"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="form-group">
                <hr class="divider inv md">
                <div class="col-xs-12 text-right">
                    <a :href="'/' + fundraiser.url" class="btn btn-link">Cancel</a>
                    <a @click.prevent="submit" class="btn btn-primary">Save Changes</a>
                </div>
                <hr class="divider inv md">
            </div>
        </div>
    </div>
</template>

<script>
import errorHandler from'../error-handler.mixin';
let marked = require('marked');
export default {

    mixins: [errorHandler],

    props: {
        description: null,
        fundraiser: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            ui: {
                checkingUrl: false,
                validUrl: true,
                newMarkedContentToggle: true
            }
        };
    },

    methods: {
        marked: marked,

        debouncedValidateUrl: _.debounce(function () {
            this.validateUrl()
        }, 500),

        validateUrl(){
            this.ui.checkingUrl = true;
            this.$http.get('fundraisers', { params: { url: this.fundraiser.url } }).then((response) => {
                if (response.data.data.length) {
                    this.ui.validUrl = response.data.data[0].id === this.fundraiser.id;
                } else {
                    this.ui.validUrl = true;
                }

                // validat empty url string for private fundraisers
                if (this.fundraiser.public === false && this.fundraiser.url === '') {
                    this.ui.validUrl = true;
                }
                this.ui.checkingUrl = false;
            }, (error) =>  {
                console.log(error);
            });
        },

        reset(){
            this.description = this.fundraiser.description;
        },

        saveDescription(){
            this.fundraiser.description = this.description;
            // this.doUpdate('description');
        },
    }
};
</script>
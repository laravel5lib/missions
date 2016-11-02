<template>
    <div>
        <template v-if="isUser()">
            <!--<div class="panel panel-default">-->
                <!--<div class="panel-body">-->
                    <form>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-sm-xs-12 text-right">
                                    <div style="padding: 0;">
                                        <div role="group" aria-label="...">
                                            <a class="btn btn-xs btn-default-hollow small" @click="newMarkedContentToggle = !newMarkedContentToggle">
                                                <span v-show="!newMarkedContentToggle">Preview</span>
                                                <span v-show="newMarkedContentToggle"><i class="fa fa-pencil"></i> Edit Description</span>

                                            </a>
                                            <span class="form-group" v-if="fundraiser" v-show="description !== fundraiser.description">
                                                <button class="btn btn-xs btn-default-hollow small" type="button" @click="reset">Cancel</button>
                                                <button class="btn btn-xs btn-success-hollow small" type="button" @click="saveDescription">Publish</button>
                                            </span>
                                            <a class="btn btn-xs btn-default-hollow small" @click="settingsModal = true"><i class="fa fa-cog"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <textarea v-show="!newMarkedContentToggle" class="form-control" id="newStoryContent" v-model="description" minlength="1" rows="10"></textarea>
                            <div class="collapse" :class="{ 'in': newMarkedContentToggle }">
                                <div v-html="description | marked"></div>
                            </div>
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
                            <div class="form-group">
                                <label for="url">Fundraiser Url</label>
                                <input type="text" class="form-control" id="url" v-model="fundraiser.url">
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" v-model="fundraiser.public"> Public Fundraiser - {{fundraiser.public ? 'Yes' : 'No'}}
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
            {{ fundraiser.description }}
        </template>
    </div>
</template>
<script type="text/javascript">
    import VueStrap from 'vue-strap/dist/vue-strap.min'
    var marked = require('marked');
    export default{
        name: 'fundraisers-manager',
        components: {'modal': VueStrap.modal, 'alert': VueStrap.alert},
        props: ['id', 'sponsorId', 'authId'],
        data(){
            return {
                description: '',
                showDescriptionSuccess: false,
                showSettingsSuccess: false,
                newMarkedContentToggle: true,
                fundraiser: null,
                resource: this.$resource('fundraisers{/id}'),

                // settings vars
                settingsModal: false,
            }
        },
        watch: {
            'fundraiser' :function (val, oldVal) {
                this.description = val.hasOwnProperty('description') ? val.description : '';
            }
        },
        filters: {
            marked: marked,
        },
        methods: {
            isUser(){
                return this.sponsorId === this.authId;
            },
            reset(){
                this.description = this.fundraiser.description;
            },
            saveDescription(){
                this.fundraiser.description = this.description;
                this.doUpdate('description');
            },
            validateUrl(url){
                this.$http.get('fundraisers', {url: this.fundraiser.url})
            },
            saveSettings(){
                this.fundraiser.description = this.description;
                this.doUpdate('settings');
            },
            doUpdate(type){
                this.resource.update({id: this.id}, this.fundraiser).then(function (response) {
                    this.fundraiser = response.data.data;
                    this.newMarkedContentToggle = true;
                    if(type === 'description'){
                        this.showDescriptionSuccess = true;
                    } else {
                        this.showSettingsSuccess = true;
                        // page refresh might be necessary for updated url
                    }

                });
            }
        },
        ready(){
            this.resource.get({id: this.id}).then(function (response) {
                this.fundraiser = response.data.data;
            });
        }
    }
</script>

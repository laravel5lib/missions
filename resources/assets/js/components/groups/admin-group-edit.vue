<template >
    <div>

        <form id="UpdateGroupForm" class="form-horizontal" novalidate style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="row form-group">
                <div class="col-sm-6">
                    <label>Profile Photo (max file size:2mb)</label>
                    <h5>
                        <a href="#">
                            <img class="av-left img-circle img-md" :src="avatar + '?w=64&h=64'" :alt="name" width="64">
                        </a>
                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button>
                    </h5>
                </div>
                <hr class="divider inv visible-xs">
                <div class="col-sm-6">
                    <label>Cover Photo (max file size:2mb)</label>
                    <h5>
                        <a href="#">
                            <img class="av-left img-rounded img-md"
                                 :src="banner + '?w=64&h=64&fit=crop-center'"
                                 :alt="name" width="64">
                        </a>
                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse"><i class="fa fa-camera"></i> Cover</button>
                    </h5>
                </div><!-- end col -->
                <div class="col-sm-12">
                    <div class="collapse" id="avatarCollapse">
                        <div class="well">
                            <upload-create-update type="avatar" :name="groupId" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Group']"></upload-create-update>
                        </div>
                    </div>
                    <div class="collapse" id="bannerCollapse">
                        <div class="well">
                            <upload-create-update type="banner" :name="groupId" :lock-type="true" :ui-locked="true" :ui-selector="1" :per-page="6" :is-child="true" :tags="['Group']"></upload-create-update>
                        </div>
                    </div>
                    <hr class="divider inv" />
                </div>
            </div><!-- end row -->

            <div class="row form-group" v-error-handler="{ value: name, handle: 'name' }">
                <div class="col-sm-12">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Group Name" name="name" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="description" id="description" placeholder="Description of Group" maxlength="120"></textarea>
                    <span class="help-block">Characters left: {{120 - (description.length||0)}}</span>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-6">
                    <label for="infoAddress">Address 1</label>
                    <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
                </div>
                <div class="col-sm-6">
                    <label for="infoAddress2">Address 2</label>
                    <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
                </div>
            </div>

            <div class="row form-group col-sm-offset-2">
                <div class="col-sm-6">
                    <label for="infoCity">City</label>
                    <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                </div>
                <div class="col-sm-6">
                    <label for="infoState">State/Prov.</label>
                    <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <label for="infoZip">ZIP/Postal Code</label>
                    <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                </div>
                <div class="col-sm-8">
                    <div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                        <label for="country">Country</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="country" :value="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="" v-model="country_code" v-validate="'required'" >
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row" v-error-handler="{ value: type, handle: 'type' }">
                <div class="col-sm-6">
                    <label for="country">Type</label>
                    <select name="type" id="type" class="form-control" v-model="type" v-validate="'required'" required>
                        <option value="">-- please select --</option>
                        <option :value="option" v-for="option in typeOptions">{{ option|capitalize }}</option>
                    </select>
                </div>
                <div v-error-handler="{ value: timezone, handle: 'timezone' }">
                    <div class="col-sm-6">
                        <label for="country">Timezone</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="timezone" :value="timezone" :options="timezones"></v-select>
                        <select hidden name="timezone" id="timezone" class="" v-model="timezone" v-validate="'required'">
                            <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <label for="infoPhone">Phone 1</label>
                    <!--<input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_one" name="phone" id="infoPhone"></phone-input>
            </div>
                <div class="col-sm-6">
                    <label for="infoMobile">Phone 2</label>
                    <!--<input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_two" name="mobile" id="infoMobile"></phone-input>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email">
                </div>
            </div>

            <div class="row" v-if="isAdmin">
                <div class="col-sm-12">
                    <label for="status" class="control-label">Status</label>
                    <select class="form-control" name="status" id="status" v-model="status">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <label for="public" class="control-label">Visibility</label><br>
                    <label class="radio-inline">
                        <input type="radio" name="public" id="public" :value="true" v-model="public"> Public
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="public2" id="public2" :value="false" v-model="public"> Private
                    </label>
                </div>
            </div>

            <template v-if="!!public">
                <div class="form-group" v-error-handler="{ value: url, handle: 'url' }">
                    <div class="col-sm-12">
                        <label for="url" class="control-label">Url Slug</label>
                        <div class="input-group">
                            <span class="input-group-addon">www.missions.me/</span>
                            <input type="text" id="url" v-model="url" class="form-control" name="url" v-validate="!!public ? 'required' : ''"/>
                        </div>
                        <!--<span class="help-block" v-if="errors.url" v-text="errors.url"></span>-->
                    </div>
                </div>
            </template>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a @click="back()" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Update</a>
                </div>
            </div>

            <modal title="Save Changes" :value="showSaveAlert" @closed="showSaveAlert=false" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
            </modal>
        </form>

    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'group-edit',
        components: { vSelect, 'upload-create-update': uploadCreateUpdate},
        mixins: [errorHandler],
        props: ['groupId', 'managing'],
        data(){
            return {
                avatar: '',
                banner: '',
                name: '',
                description: '',
                type: '',
                country_code: '',
                timezone: '',
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                status: '',
                url: '',
                email: '',

                // logic variables
                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
//                attemptSubmit: false,
                resource: this.$resource('groups{/id}'),
                countries: [],
                countryCodeObj: null,
                timezones: [],
                //timezoneObj: null,
                showSuccess: false,
                showError: false,
                showSaveAlert: false,
                hasChanged: false,

                selectedAvatar: null,
                avatar_upload_id: null,
                selectedBanner: null,
                banner_upload_id: null,
//                errors: [],
                // mixin settings
                validatorHandle: 'UpdateGroup',
            }
        },
        computed: {
            country_code() {
                if (_.isObject(this.countryCodeObj)) {
                    return this.countryCodeObj.code;
                }
            },
            isAdmin(){
                return _.contains(_.pluck(this.$root.user.abilities.data, 'slug'), 'edit-groups');
            },
        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'avatar':
                        this.selectedAvatar = data;
                        this.avatar_upload_id = data.id;
                        jQuery('#avatarCollapse').collapse('hide');
                        break;
                    case 'banner':
                        this.selectedBanner = data;
                        this.banner_upload_id = data.id;
                        jQuery('#bannerCollapse').collapse('hide');
                        break;
                }
                this.submit();
            }
        },
        methods: {
            /*checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UpdateGroup[field].invalid && this.attemptSubmit;
            },*/
            onTouched(){
                this.hasChanged = true;
            },
            back(force){
                if (this.hasChanged && !force ) {
                    this.showSaveAlert = true;
                    return false;
                }
                window.location.href = '/admin/groups/' + this.groupId;
            },
            forceBack(){
                return this.back(true);
            },
            submit(){
                this.errors = [];
                this.resetErrors();
                if (this.$UpdateGroup.valid) {
                    let formData = this.data;
                    // this.$refs.spinner.show();
                    this.resource.put({id: this.groupId}, {
                        name: this.name,
                        description: this.description,
                        type: this.type,
                        country_code: this.country_code,
                        timezone: this.timezone,
                        phone_one: this.phone_one,
                        phone_two: this.phone_two,
                        address_one: this.address_one,
                        address_two: this.address_two,
                        city: this.city,
                        state: this.state,
                        zip: this.zip,
                        public: this.public,
                        status: this.status,
                        url: this.url,
                        email: this.email,
                        avatar_upload_id: this.avatar_upload_id,
                        banner_upload_id: this.banner_upload_id,
                    }).then((resp) => {
                        let group = resp.data.data;
                        this.avatar = group.avatar;
                        this.banner = group.banner;
                        this.$root.$emit('showSuccess', 'Group updated!');
                        this.hasChanged = false;
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        this.$root.$emit('showError', 'There are errors on the form.');
                    });
                } else {
                    this.showError = true;
                }
            }
        },
        mounted() {
            // this.$refs.spinner.show();
            let countriesPromise = this.$http.get('utilities/countries').then((response) => {
                this.countries = response.data.countries;
            });

            let timezonesPromise = this.$http.get('utilities/timezones').then((response) => {
                this.timezones = response.data.timezones;
            });

            //Promise.all([countriesPromise, timezonesPromise]).then((values) => {
                this.resource.get({id: this.groupId}).then((response) => {
                    let group = response.data.data;
                    this.name = group.name;
                    this.avatar = group.avatar;
                    this.avatar_upload_id = group.avatar_upload_id;
                    this.banner = group.banner;
                    this.banner_upload_id = group.banner_upload_id;
                    this.description = group.description;
                    this.type = group.type;
                    this.countryCodeObj = _.findWhere(this.countries, {code: group.country_code});
                    this.country_code = group.country_code;
                    this.timezone = group.timezone;
                    this.phone_one = group.phone_one;
                    this.phone_two = group.phone_two;
                    this.address_one = group.address_one;
                    this.address_two = group.address_two;
                    this.city = group.city;
                    this.state = group.state;
                    this.zip = group.zip;
                    this.public = group.public;
                    this.status = group.status;
                    this.url = group.url;
                    this.email = group.email;
                    // this.$refs.spinner.hide();
                }, (response) =>  {
                    console.log('Update Failed! :(');
                    console.log(response);
                    // this.$refs.spinner.hide();
                    //TODO add error alert
                });
            //});
        }
    }
</script> 
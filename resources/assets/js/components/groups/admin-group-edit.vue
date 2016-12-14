<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="UpdateGroup" @touched="onTouched">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <form id="UpdateGroupForm" class="form-horizontal" novalidate>
            <div class="row form-group" :class="{ 'has-error': checkForError('name') }">
                <div class="col-sm-12">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Group Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="description" id="description" placeholder="Description of Group"></textarea>
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

            <div class="row form-group col-sm-offset-2">
                <div class="col-sm-4">
                        <label for="infoZip">ZIP/Postal Code</label>
                        <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                </div>
                <div class="col-sm-8">
                    <div :class="{ 'has-error': checkForError('country') }">
                        <label for="country">Country</label>
                        <v-select class="form-control" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="" v-model="country_code" v-validate:country="{ required: true }" >
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                <div class="col-sm-6">
                    <label for="country">Type</label>
                    <select name="type" id="type" class="form-control" v-model="type" v-validate:type="{ required: true }" required>
                        <option value="">-- please select --</option>
                        <option :value="option" v-for="option in typeOptions">{{option|capitalize}}</option>
                    </select>
                </div>
                <div :class="{ 'has-error': checkForError('timezone') }">
                    <div class="col-sm-6">
                        <label for="country">Timezone</label>
                        <v-select class="form-control" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                        <select hidden name="timezone" id="timezone" class="" v-model="timezone" v-validate:timezone="{ required: true }">
                            <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-6">
                        <label for="infoPhone">Phone 1</label>
                        <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                </div>
                <div class="col-sm-6">
                        <label for="infoMobile">Phone 2</label>
                        <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                </div>
            </div>

            <div class="row form-group">
                <div class="col-sm-12">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email">
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <select class="form-control" name="status" id="status" v-model="status">
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="public" class="col-sm-2 control-label">Visibility</label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                        <input type="radio" name="public" id="public" :value="true" v-model="public"> Public
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="public2" id="public2" :value="false" v-model="public"> Private
                    </label>
                </div>
            </div>

            <template v-if="!!public">
                <div class="form-group" :class="{ 'has-error': checkForError('url') }">
                    <label for="url" class="col-sm-2 control-label">Url Slug</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">www.missions.me/groups/</span>
                            <input type="text" id="url" v-model="url" class="form-control" v-validate:url="{ required: !!public }"/>
                        </div>
                    </div>
                </div>
            </template>

            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a @click="submit()" class="btn btn-primary">Update</a>
                    <a @click="back()" class="btn btn-success">Done</a>
                </div>
            </div>

            <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
                <span class="icon-ok-circled alert-icon-float-left"></span>
                <strong>Well Done!</strong>
                <p>Group updated!</p>
            </alert>
            <alert :show.sync="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
                <span class="icon-info-circled alert-icon-float-left"></span>
                <strong>Oh No!</strong>
                <p>There are errors on the form.</p>
            </alert>

            <modal title="Save Changes" :show.sync="showSaveAlert" ok-text="Continue" cancel-text="Cancel" :callback="forceBack">
                <div slot="modal-body" class="modal-body">You have unsaved changes, continue anyway?</div>
            </modal>
        </form>
    </validator>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    export default{
        name: 'group-edit',
        components: { vSelect },
        props: ['groupId', 'managing'],
        data(){
            return {
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
                attemptSubmit: false,
                resource: this.$resource('groups{/id}'),
                countries: [],
                countryCodeObj: null,
                timezones: [],
                //timezoneObj: null,
                showSuccess: false,
                showError: false,
                showSaveAlert: false,
                hasChanged: false,

            }
        },
        computed: {
            country_code() {
                if (_.isObject(this.countryCodeObj)) {
                    return this.countryCodeObj.code;
                }
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$UpdateGroup[field].invalid && this.attemptSubmit;
            },
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
                this.attemptSubmit = true;
                if (this.$UpdateGroup.valid) {
                    let formData = this.data;
                    this.$refs.spinner.show();
                    this.resource.update({id: this.groupId}, {
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
                        email: this.email
                    }).then(function (resp) {
                        //TODO switch to universal alerts
                        this.showSuccess = true;
                        this.hasChanged = false;
                        this.$refs.spinner.hide();
                    }, function (error) {
                        console.log(error);
                        this.showError = true;
                        this.$refs.spinner.hide();
                        debugger;
                        //TODO add error alert
                    });
                } else {
                    this.showError = true;
                }
            }
        },
        ready() {
            this.$refs.spinner.show();
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/timezones').then(function (response) {
                this.timezones = response.data.timezones;
            });

            this.resource.get({id: this.groupId}).then(function (response) {
                let group = response.data.data;
                this.name = group.name;
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
                this.$refs.spinner.hide();
            }, function (response) {
                console.log('Update Failed! :(');
                console.log(response);
                this.$refs.spinner.hide();
                //TODO add error alert
            });
        }
    }
</script> 
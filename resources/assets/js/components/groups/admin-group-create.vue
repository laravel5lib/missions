<template >

        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <form id="CreateGroupForm" class="form-horizontal" novalidate>
            <div class="form-group" v-error-handler="{ value: name, handle: 'name' }">
                <div class="col-sm-12">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name" debounce="250"
                           placeholder="Group Name" name="name" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="description">Description</label>
                    <textarea class="form-control" v-model="description" id="description" placeholder="Description of Group" maxlength="120"></textarea>
                    <span class="help-block">Characters left: {{120 - (description.length||0)}}</span>
                </div>
            </div>
            <div class="form-group">
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
                    <div v-error-handler="{ value: country_code, client: 'country', server: 'country_code' }">
                        <label for="country">Country</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="country" :value="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate="'required'" >
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" v-error-handler="{ value: type, handle: 'type' }">
                <div class="col-sm-12">
                    <label for="type">Type</label>
                    <select name="type" id="type" class="form-control" v-model="type" v-validate="'required'" required>
                        <option value="">-- please select --</option>
                        <option :value="option" v-for="option in typeOptions">{{ option|capitalize }}</option>
                    </select>
                </div>
            </div>

            <div class="form-group" v-error-handler="{ value: timezone, handle: 'timezone' }">
                <div class="col-sm-12">
                    <label for="timezone">Timezone</label>
                    <v-select @keydown.enter.prevent=""  class="form-control" id="timezone" :value="timezone" :options="timezones"></v-select>
                    <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate="'required'">
                        <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                    </select>
                </div>
            </div>

            <div class="row form-group col-sm-offset-2">
                <div class="col-sm-6">
                    <label for="infoPhone">Phone 1</label>
                    <!--<input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_one" name="phone" id="infoPhone"></phone-input>
                <div class="col-sm-6">
                    <label for="infoMobile">Phone 2</label>
                    <!--<input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">-->
                    <phone-input v-model="phone_two" name="mobile" id="infoMobile"></phone-input>
                </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email">
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <label class="radio-inline">
                        <input type="radio" name="status" id="status" :value="true" v-model="public"> Public
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="status2" id="status2" :value="false" v-model="public"> Private
                    </label>
                </div>
            </div>
            <div class="form-group" v-if="!!public">
                <label for="url" class="col-sm-2 control-label">Url Slug</label>
                <div class="col-sm-10" v-error-handler="{ value: url, handle: 'url' }">
                    <div class="input-group">
                        <span class="input-group-addon">www.missions.me/groups/</span>
                        <input type="text" id="url" v-model="url" class="form-control" required name="" v-validate="!!public ? 'required' : ''"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a href="/admin/groups" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Create</a>
                </div>
            </div>
            <alert v-model="showError" placement="top-right" :duration="6000" type="danger" width="400px" dismissable>
                <span class="icon-info-circled alert-icon-float-left"></span>
                <strong>Oh No!</strong>
                <p>There are errors on the form.</p>
            </alert>

        </form>

</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'group-create',
        components: {vSelect},
        mixins: [errorHandler],
        data(){
            return {
                name: '',
                description: '',
                type: '',
                country_code: null,
                timezone: null,
                phone_one: '',
                phone_two: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                public: false,
                url: '',
                email: '',

                // logic variables
                typeOptions: ['church', 'business', 'nonprofit', 'youth', 'other'],
//                attemptSubmit: false,
                showError: false,
                countries: [],
                countryCodeObj: null,
                timezones: [],
                //timezoneObj: null,
                // mixin settings
                validatorHandle: 'CreateGroup',
            }
        },
        watch: {
            'name'(val, oldVal) {
                if (typeof val === 'string') {
                    // pre-populate slug
                    this.$http.get('utilities/make-slug/' + val, { params: { hideLoader: true } }).then((response) => {
                        this.url = response.data.slug;
                    });
                }
            }
        },
        computed: {
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            }
        },
        methods: {
            /*checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateGroup[field].invalid && this.attemptSubmit;
            },*/
            submit(){
                this.resetErrors();
                if (this.$CreateGroup.valid) {
                    let resource = this.$resource('groups');

                    let formData = this.data
                    // this.$refs.spinner.show();
                    resource.post(null, {
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
                        url: this.url,
                        email: this.email
                    }).then((resp) => {
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                        // this.$refs.spinner.hide();
                    }, (error) =>  {
                        this.errors = error.data.errors;
                        console.log(error);
                        this.showError = true;
                        // this.$refs.spinner.hide();
                        //TODO add error alert
//                        debugger;
                    });
                } else {
                    this.showError = true;
                }
            }
        },
        mounted(){
            // this.$refs.spinner.show();
            this.$http.get('utilities/countries').then((response) => {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/timezones').then((response) => {
                this.timezones = response.data.timezones;
                // this.$refs.spinner.hide();
            });
            //TODO use promises defers here
        }
    }
</script> 
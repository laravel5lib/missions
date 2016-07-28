<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <validator name="CreateGroup">
        <form id="CreateGroupForm" class="form-horizontal" novalidate>
            <div class="form-group" :class="{ 'has-error': checkForError('name') }">
                <label for="name" class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="Group Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="description">Description</label>
                <div class="col-sm-10">
                    <textarea class="form-control" v-model="description" id="description" placeholder="Description of Group"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="infoAddress">Address 1</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="infoAddress2">Address 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoCity">City</label>
                        <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoState">State/Prov.</label>
                        <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                    </div>
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="infoZip">ZIP/Postal Code</label>
                        <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                    </div>
                </div>
                <div class="col-sm-8">
                    <div class="form-group" :class="{ 'has-error': checkForError('country') }">

                        <label for="country">Country</label>
                        <v-select class="form-controls" id="country" :value.sync="countryCodeObj" :options="countries" label="name"></v-select>
                        <select hidden name="country" id="country" class="hidden" v-model="country_code" v-validate:country="{ required: true }" >
                            <option :value="country.code" v-for="country in countries">{{country.name}}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('type') }">
                <label for="type" class="col-sm-2 control-label">Type</label>
                <div class="col-sm-10">
                    <select name="type" id="type" class="form-control" v-model="type" v-validate:type="{ required: true }" required>
                        <option value="">-- please select --</option>
                        <option :value="option" v-for="option in typeOptions">{{option|capitalize}}</option>
                    </select>
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('timezone') }">
                <label for="timezone" class="col-sm-2 control-label">Timezone</label>

                <div class="col-sm-10">
                    <v-select class="form-controls" id="timezone" :value.sync="timezone" :options="timezones"></v-select>
                    <select hidden name="timezone" id="timezone" class="hidden" v-model="timezone" v-validate:timezone="{ required: true }">
                        <option :value="timezone" v-for="timezone in timezones">{{ timezone }}</option>
                    </select>
                </div>
            </div>

            <div class="row col-sm-offset-2">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoPhone">Phone 1</label>
                        <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="infoMobile">Phone 2</label>
                        <input type="text" class="form-control" v-model="phone_two | phone" id="infoMobile" placeholder="123-456-7890">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="name" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-10">
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
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">www.missions.me/groups/</span>
                        <input type="text" id="url" v-model="url" class="form-control" required v-validate:="{ required: !!public }"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <a href="/admin/groups" class="btn btn-default">Cancel</a>
                    <a @click="submit()" class="btn btn-primary">Create</a>
                </div>
            </div>
        </form>
    </validator>
</template>
<script>
    import vSelect from "vue-select";
    export default{
        name: 'group-create',
        components: {vSelect},
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
                attemptSubmit: false,
                countries: [],
                countryCodeObj: null,
                timezones: [],
                //timezoneObj: null,
            }
        },
        computed: {
            country_code() {
                return _.isObject(this.countryCodeObj) ? this.countryCodeObj.code : null;
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateGroup[field].invalid && this.attemptSubmit;
            },
            submit(){
                this.attemptSubmit = true;
                if (this.$CreateGroup.valid) {
                    var resource = this.$resource('groups');

                    var formData = this.data
                    resource.save(null, {
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
                    }).then(function (resp) {
                        window.location.href = '/admin' + resp.data.data.links[0].uri;
                    }, function (error) {
                        console.log(error);
                        debugger;
                    });
                }
            }
        },
        ready(){
            this.$http.get('utilities/countries').then(function (response) {
                this.countries = response.data.countries;
            });

            this.$http.get('utilities/timezones').then(function (response) {
                this.timezones = response.data.timezones;
            });
        }
    }
</script> 
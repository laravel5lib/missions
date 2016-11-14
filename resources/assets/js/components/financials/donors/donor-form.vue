<template>
    <div class="panel panel-default">
        <validator name="validation" :classes="{ invalid: 'has-error' }">
            <spinner v-ref:donorspinner size="xl" :fixed="false" text="Saving..."></spinner>
            <div class="panel-heading">
                <h5>Personal Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6" v-validate-class>
                        <label>Name</label>
                        <input type="text"
                               class="form-control"
                               v-model="donor.name"
                               initial="off"
                               v-validate:name="{required: true}">
                    </div>
                    <div class="col-md-6">
                        <label>Company</label>
                        <input type="text" class="form-control" v-model="donor.company">
                    </div>
                </div>
            </div>
            <div class="panel-heading">
                <h5>Contact Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6" v-validate-class>
                        <label>Email</label>
                        <input type="text"
                               class="form-control"
                               v-model="donor.email"
                               initial="off"
                               v-validate:email="{required: true, email: true}">
                    </div>
                    <div class="col-md-6">
                        <label>Phone</label>
                        <input type="text" class="form-control" v-model="donor.phone">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label>Address</label>
                        <input type="text" class="form-control" v-model="donor.address">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label>City</label>
                        <input type="text" class="form-control" v-model="donor.city">
                    </div>
                    <div class="col-md-6">
                        <label>State/Providence</label>
                        <input type="text" class="form-control" v-model="donor.state">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6" v-validate-class>
                        <label>Zip/Postal Code</label>
                        <input type="text" class="form-control" v-model="donor.zip" initial="off"
                               v-validate:zip="{required: true}">
                    </div>
                    <div class="col-md-6" v-validate-class>
                        <label>Country</label>
                        <v-select class="form-control" id="country" :debounce="250"
                                  :value.sync="countryCodeObj" :options="countries" label="name"
                                  placeholder="Select a country" initial="off"
                                  v-validate:country_code="{required: true}"></v-select>
                    </div>
                </div>
            </div>
            <div class="panel-heading">
                <h5>Account Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Account Type</label>
                        <select class="form-control" v-model="donor.account_type" @change="donor.account_id = null" initial="off">
                            <option :value="null">Guest</option>
                            <option value="users">Member</option>
                            <option value="groups">Group</option>
                        </select>
                    </div>
                    <div class="col-md-6" v-if="donor.account_type" :class="{'has-error' : accountError}">
                        <label>Account Holder</label>
                        <v-select class="form-control" id="accountHolder" :debounce="250" :on-search="getUsers"
                                  :value.sync="userObj" :options="users" label="name"
                                  placeholder="Select a user" v-if="donor.account_type == 'users'"></v-select>
                        <v-select class="form-control" id="accountHolder" :debounce="250" :on-search="getGroups"
                                  :value.sync="groupObj" :options="groups" label="name"
                                  placeholder="Select a group" v-if="donor.account_type == 'groups'"></v-select>
                        <span v-if="accountError" class="text-danger">Account already in use.</span>
                    </div>
                    <div class="col-md-6">
                        <label>Stripe Customer ID</label>
                        <input type="text" class="form-control" v-model="donor.customer_id">
                        <span class="help-block">Optional. Generated automatically.</span>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-center">
                <button class="btn btn-default" @click="cancel">Cancel</button>
                <button class="btn btn-primary" v-if="!isUpdate" @click="create">Create</button>
                <button class="btn btn-primary" v-if="isUpdate" @click="update">Save</button>
            </div>
        </validator>

        <alert :show.sync="showSuccess"
               placement="top-right"
               :duration="3000"
               type="success"
               width="400px"
               dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{ message }}</p>
        </alert>

        <alert :show.sync="showError"
               placement="top-right"
               :duration="6000"
               type="danger"
               width="400px"
               dismissable>
            <span class="icon-info-circled alert-icon-float-left"></span>
            <strong>Oh No!</strong>
            <p>{{ message }}</p>
        </alert>
    </div>
</template>
<script>
    import vSelect from "vue-select";
    export default{
        name: 'donor-form',
        components: { vSelect },
        props: {
            'isUpdate': {
                required: false,
                default: false
            },
            'donorId': {
                type: String,
                required: false
            }
        },
        data() {
            return {
                donor: {
                    name: null,
                    company: null,
                    email: null,
                    phone: null,
                    address: null,
                    city: null,
                    state: null,
                    zip: null,
                    country_code: null,
                    account_type: null,
                    account_id: null,
                    customer_id: null
                },
                countries: [],
                users: [],
                groups: [],
                message: '',
                showSuccess: false,
                showError: false,
                countryCodeObj: null,
                userObj: null,
                groupObj: null,
                accountError: ''
            }
        },
        watch: {
            'countryCodeObj': function (val) {
                 _.isObject(val) ? this.donor.country_code = val.code : this.donor.country_code  = null;
            },
            'userObj': function (val) {
                 _.isObject(val) ? this.donor.account_id = val.id : this.donor.account_id  = null;
            },
            'groupObj': function (val) {
                 _.isObject(val) ? this.donor.account_id = val.id : this.donor.account_id  = null;
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$Donor[field].invalid;
            },
            fetch() {
                this.$http.get('donors/' + this.donorId).then(function (response) {
                    this.donor.name = response.data.data.name;
                    this.donor.company = response.data.data.company;
                    this.donor.email = response.data.data.email;
                    this.donor.phone = response.data.data.phone;
                    this.donor.address = response.data.data.address;
                    this.donor.city = response.data.data.city;
                    this.donor.state = response.data.data.state;
                    this.donor.zip = response.data.data.zip;
                    this.donor.country_code = response.data.data.country.code;
                    this.countryCodeObj = response.data.data.country;
                    this.donor.account_type = response.data.data.account_type;
                    this.donor.account_id = response.data.data.account_id;
                    this.donor.customer_id = response.data.data.customer_id;

                    if(this.donor.account_type == 'users') {
                        this.userObj = {'id': response.data.data.account_id, 'name': response.data.data.account_name}
                    } else {
                        this.groupObj = {'id': response.data.data.account_id, 'name': response.data.data.account_name}
                    }

                });
            },
            create() {
                this.$refs.donorspinner.show();
                // validate manually
                var self = this
                this.$validate(true, function () {
                    if (self.$validation.invalid) {
                    }
                })
                this.$http.post('donors', this.donor).then(function (response) {
                    this.$refs.donorspinner.hide();
                    this.message = 'Donor created successfully.';
                    this.showSuccess = true;
                    this.$dispatch('donor-created', response.data.data.id);
                }).error(function (response) {
                    this.$refs.donorspinner.hide();
                    console.log(response);
                    this.message = 'There are errors on the form.';
                    this.showError = true;
                });
            },
            update() {
                this.accountError = false;
                this.$http.put('donors/' + this.donorId, this.donor).then(function (response) {
                    this.message = 'Donor updated successfully.';
                    this.showSuccess = true;
                }).error(function (response) {
                    if(_.contains(_.keys(response.errors), 'account_id')) {
                        this.accountError = true;
                    }
                    this.message = 'There are errors on the form.';
                    this.showError = true;
                });
            },
            cancel() {
                if (this.$parent != this.$root) {
                    console.log('cancelled');
				    this.$dispatch('cancel');
			    } else {
			        return window.location.href = '/admin/donors'
			    }
            },
            getCountries() {
                this.$http.get('utilities/countries').then(function (response) {
                    this.countries = response.data.countries;
                });
            },
            getUsers(search) {
                this.$http.get('users?per_page=10', {search: search}).then(function (response) {
                    this.users = response.data.data;
                });
            },
            getGroups(search) {
                this.$http.get('groups?per_page=10', {search: search}).then(function (response) {
                    this.groups = response.data.data;
                });
            }
        },
        ready() {
            if (this.isUpdate) {
                this.fetch();
            }

            this.getCountries();
        }
    }
</script>
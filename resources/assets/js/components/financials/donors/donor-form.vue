<template>
    <div class="panel panel-default">

            <spinner ref="donorspinner" size="xl" :fixed="false" text="Saving..."></spinner>
            <spinner ref="spinner" size="xl" :fixed="false" text="Loading..."></spinner>
            <div class="panel-heading">
                <h5>Personal Details</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" v-model="donor.name" name="name" v-validate="'required'">
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
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="text" class="form-control" v-model="donor.email" name="email" v-validate="'email'">
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
                    <div class="col-md-6">
                        <label>Zip/Postal Code</label>
                        <input type="text" class="form-control" v-model="donor.zip"
                               name="zip" v-validate="'required'">
                    </div>
                    <div class="col-md-6">
                        <label>Country</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="country" :debounce="250"
                                  v-model="countryCodeObj" :options="countries" label="name"
                                  placeholder="Select a country"
                                  name="country_code" v-validate="'required'"></v-select>
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
                        <select class="form-control" v-model="donor.account_type" @change="donor.account_id = null" >
                            <option :value="null">Guest</option>
                            <option value="users">Member</option>
                            <option value="groups">Group</option>
                        </select>
                    </div>
                    <div class="col-md-6" v-if="donor.account_type" :class="{'has-error' : accountError}">
                        <label>Account Holder</label>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="accountHolder" :debounce="250" :on-search="getUsers"
                                  v-model="userObj" :options="users" label="name"
                                  placeholder="Select a user" v-if="donor.account_type == 'users'"></v-select>
                        <v-select @keydown.enter.prevent=""  class="form-control" id="accountHolder" :debounce="250" :on-search="getGroups"
                                  v-model="groupObj" :options="groups" label="name"
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
            <div class="panel-body text-center">
                <button class="btn btn-default" @click="cancel">Back</button>
                <button class="btn btn-primary" v-if="!isUpdate" @click="create">Create</button>
                <button class="btn btn-primary" v-if="isUpdate" @click="update">Save</button>
            </div>

    </div>
</template>
<script type="text/javascript">
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
                countryCodeObj: null,
                userObj: null,
                groupObj: null,
                accountError: ''
            }
        },
        watch: {
            'countryCodeObj'(val, oldVal) {
                 _.isObject(val) ? this.donor.country_code = val.code : this.donor.country_code  = null;
            },
            'userObj'(val, oldVal) {
                 _.isObject(val) ? this.donor.account_id = val.id : this.donor.account_id  = null;
            },
            'groupObj'(val, oldVal) {
                 _.isObject(val) ? this.donor.account_id = val.id : this.donor.account_id  = null;
            }
        },
        methods: {
            fetch() {
                this.$http.get('donors/' + this.donorId).then((response) => {
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
                this.$validator.validateAll().then(result =>  {
                    if (result) {
                        this.$http.post('donors', this.donor).then((response) => {
                            this.$refs.donorspinner.hide();
                            this.reset();
                            this.$root.$emit('showSuccess', 'Donor created successfully.');
                            this.$emit('donor-created', response.data.data.id);
                        },(response) =>  {
                            this.$refs.donorspinner.hide();
                            this.$root.$emit('showError', 'There are errors on the form');
                        });
                    }
                })
            },
            update() {
                this.accountError = false;
                // this.$refs.spinner.show();
                this.$http.put('donors/' + this.donorId, this.donor).then((response) => {
                    this.$root.$emit('showSuccess', 'Donor updated successfully.');
                    // this.$refs.spinner.hide();
                },(response) =>  {
                    if(_.contains(_.keys(response.errors), 'account_id')) {
                        this.accountError = true;
                    }
                    this.$root.$emit('showError', 'There are errors on the form.');
                    // this.$refs.spinner.hide();
                });
            },
            cancel() {
                this.reset();
                if (this.$parent != this.$root) {
				    this.$emit('cancel');
			    } else {
			        if( this.isUpdate) {
			            return window.location.href = '/admin/donors/' + this.donorId;
			        }
			        return window.location.href = '/admin/donors';
			    }
            },
            reset() {
                this.donor.name = null;
                this.donor.company = null;
                this.donor.email = null;
                this.donor.phone = null;
                this.donor.address = null;
                this.donor.city = null;
                this.donor.state = null;
                this.donor.zip = null;
                this.donor.country_code = null;
                this.donor.account_type = null;
                this.donor.account_id = null;
                this.donor.customer_id =  null;
            },
            getCountries() {
                // this.$refs.spinner.show();
                this.$http.get('utilities/countries').then((response) => {
                    this.countries = response.data.countries;
                    // this.$refs.spinner.hide();
                });
            },
            getUsers(search) {
                // this.$refs.spinner.show();
                this.$http.get('users?per_page=10', { params: {search: search} }).then((response) => {
                    this.users = response.data.data;
                    // this.$refs.spinner.hide();
                });
            },
            getGroups(search) {
                // this.$refs.spinner.show();
                this.$http.get('groups?per_page=10', { params: {search: search} }).then((response) => {
                    this.groups = response.data.data;
                    // this.$refs.spinner.hide();
                });
            }
        },
        mounted() {
            if (this.isUpdate) {
                this.fetch();
            }

            this.getCountries();
        }
    }
</script>
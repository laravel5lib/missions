<template>
<div>
    <ajax-form 
        :horizontal="true" 
        :method="method" 
        :action="action"
        :initial="initialData"
    >
        <div slot-scope="{form}">
           <div class="panel panel-default" style="border-top: 5px solid #f6323e">

                <div class="panel-heading">
                    <h5>Account Settings</h5>
                </div>

                <div class="panel-body">
                    
                    <input-text name="first_name" v-model="form.first_name" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">First Name</label>
                    </input-text>

                    <input-text name="last_name" v-model="form.last_name" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Last Name</label>
                    </input-text>

                    <input-date name="birthday" v-model="form.birthday" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Birthday</label>
                    </input-date>

                    <input-radio-group 
                        name="gender" 
                        v-model="form.gender" 
                        :horizontal="true" 
                        classes="col-md-8" 
                        :options="[{label: 'Male', value: 'male'}, {label: 'Female', value: 'female'}]"
                    >
                        <label slot="label" class="control-label col-md-4">Gender</label>
                    </input-radio-group>

                    <input-radio-group 
                        name="status" 
                        v-model="form.status" 
                        :horizontal="true" 
                        classes="col-md-8" 
                        :options="[
                            {label: 'Single', value: 'single'}, 
                            {label: 'Married', value: 'married'},
                            {label: 'Engaged', value: 'engaged'},
                            {label: 'Widowed', value: 'widowed'},
                            {label: 'Divorced', value: 'divorced'}
                        ]"
                    >
                        <label slot="label" class="control-label col-md-4">Marital Status</label>
                    </input-radio-group>

                    <input-text name="email" v-model="form.email" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Email</label>
                        <span slot="help-text" class="help-block">This email is used to log into the account.</span>
                    </input-text>

                    <input-text name="alt_email" v-model="form.alt_email" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Alternate Email</label>
                        <span slot="help-text" class="help-block">Another email for contact purposes. Users cannot use this for login.</span>
                    </input-text>

                    <input-password name="password" v-model="form.password" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Password</label>
                    </input-password>

                    <input-password name="password_confirmation" v-model="form.password_confirmation" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Password Confirmation</label>
                    </input-password>

                    <select-timezone name="timezone" v-model="form.timezone" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Timezone</label>
                    </select-timezone>
                </div>
            </div>

            <div class="panel panel-default" style="border-top: 5px solid #f6323e">

                <div class="panel-heading">
                    <h5>Contact Information</h5>
                </div>

                <div class="panel-body">

                    <input-phone name="phone_one" v-model="form.phone_one" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Primary Phone</label>
                    </input-phone>

                    <input-phone name="phone_two" v-model="form.phone_two" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Secondary Phone</label>
                    </input-phone>

                    <input-text name="address" v-model="form.address" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Mailing Address</label>
                    </input-text>

                    <input-text name="city" v-model="form.city" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">City</label>
                    </input-text>

                    <input-text name="state" v-model="form.state" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">State/Providence</label>
                    </input-text>

                    <input-text name="zip" v-model="form.zip" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Zip/Postal Code</label>
                    </input-text>

                    <select-country name="country_code" v-model="form.country_code" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Country</label>
                    </select-country>

                </div>

            </div>

            <div class="panel panel-default" style="border-top: 5px solid #f6323e">

                <div class="panel-heading">
                    <h5>Profile Settings</h5>
                </div>

                <div class="panel-body">

                    <div class="alert alert-warning" v-if="method == 'post'">
                        Profile and cover photos can be added after the user is created.
                    </div>

                    <input-radio-group 
                        name="public" 
                        v-model="form.public" 
                        :horizontal="true" 
                        classes="col-md-8" 
                        :options="[{label: 'Public', value: true}, {label: 'Private', value: false}]"
                    >
                        <label slot="label" class="control-label col-md-4">Visibility</label>
                    </input-radio-group>

                    <input-text name="url" v-model="form.url" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Page URL</label>
                        <span slot="prefix" class="input-group-addon">missions.me/</span>
                    </input-text>

                    <input-textarea name="bio" v-model="form.bio" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Bio</label>
                    </input-textarea>
                    
                    <template v-for="link in initialData.links">
                        <input-text :name="link.name" v-model="link.url" :horizontal="true" classes="col-md-8">
                            <label slot="label" class="control-label col-md-4">{{ link.name }}</label>
                        </input-text>
                    </template>

                </div>

            </div>

            <div class="row">
                <div class="col-xs-12 text-right">
                    <hr class="divider sm inv">
                    <a :href="cancelURL" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary" v-text="submitButtonLabel"></button>
                    <hr class="divider xlg inv">
                </div>
            </div>
        </div>
    </ajax-form>

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success :redirect="redirectURL">
        <template slot="title">Nice Work!</template>
        <template slot="message">{{ successMessage }}</template>
        <template slot="cancel">{{ cancelButton }}</template>
        <template slot="confirm">Continue</template>
    </alert-success>
</div>
</template>

<script>
export default {

    name: 'UserForm',

    props: {
        user: {
            type: Object,
            default: function() {
                return {
                    first_name: null,
                    last_name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    alt_email: null,
                    birthday: null,
                    gender: null,
                    status: null,
                    timezone: 'America/Los_Angeles',
                    phone_one: null,
                    phone_two: null,
                    address: null,
                    city: null,
                    state: null,
                    zip: null,
                    country_code: 'us',
                    public: false,
                    url: null,
                    bio: null,
                }
            }
        }
    },

    computed: {
        method() {
            return this.user.id ? 'put' : 'post';
        },

        action() {
            return this.user.id ? `/users/${this.user.id}` : '/users';
        },

        submitButtonLabel() {
            return this.user.id ? 'Save Changes' : 'Create User';
        },

        cancelURL() {
            if (this.firstUrlSegment == 'admin') {
                return this.method == 'post' ? '/admin/users' : `/admin/users/${this.user.id}`;
            }

            return '/dashboard';
        },

        redirectURL() {
            if (this.firstUrlSegment == 'admin') {
                return '/admin/users/';
            }

            return '/dashboard';
        },

        cancelButton() {
            return this.method == 'post' ? 'Create Another' : 'Keep Editing';
        },

        successMessage() {
            return this.method == 'post' ? 'A new user was created.' : 'Your changes have been saved.';
        },

        initialData() {
            let defaultLinks = [
                {
                    name: 'facebook',
                    url: _.findWhere(this.user.links, {'name': 'facebook'}) ? _.findWhere(this.user.links, {'name': 'facebook'}).url : null
                },
                {
                    name: 'instragram',
                    url: _.findWhere(this.user.links, {'name': 'instagram'}) ? _.findWhere(this.user.links, {'name': 'instagram'}).url : null
                },
                {
                    name: 'twitter',
                    url: _.findWhere(this.user.links, {'name': 'twitter'}) ? _.findWhere(this.user.links, {'name': 'twitter'}).url : null
                },
                {
                    name: 'linkedin',
                    url: _.findWhere(this.user.links, {'name': 'linkedin'}) ? _.findWhere(this.user.links, {'name': 'linkedin'}).url : null
                },
                {
                    name: 'pinterest',
                    url: _.findWhere(this.user.links, {'name': 'pinterest'}) ? _.findWhere(this.user.links, {'name': 'pinterest'}).url : null
                },
                {
                    name: 'google',
                    url: _.findWhere(this.user.links, {'name': 'google'}) ? _.findWhere(this.user.links, {'name': 'google'}).url : null
                },
                {
                    name: 'vimeo',
                    url: _.findWhere(this.user.links, {'name': 'vimeo'}) ? _.findWhere(this.user.links, {'name': 'vimeo'}).url : null
                },
                {
                    name: 'youtube',
                    url: _.findWhere(this.user.links, {'name': 'youtube'}) ? _.findWhere(this.user.links, {'name': 'youtube'}).url : null
                },
                {
                    name: 'website',
                    url: _.findWhere(this.user.links, {'name': 'website'}) ? _.findWhere(this.user.links, {'name': 'website'}).url : null
                }
            ];

            this.user.links = defaultLinks;
            this.user.password = null;
            this.user.password_confirmation = null;
            return this.user;
        }
    }
}
</script>
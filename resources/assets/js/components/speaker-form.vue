<template>

        <form class="form-horizontal" @submit.prevent="submit" novalidate style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="form-group">
                <div class="col-sm-6" v-error-handler="{ value: name, handle: 'name' }">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="John Smith" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="1" required>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: organization, handle: 'organization' }">
                    <label for="name">Your Church/Organization</label>
                    <input type="text" class="form-control" name="organization" id="organization" v-model="organization"
                           placeholder="Church Name" v-validate="'required|min:1|max:100'"
                           maxlength="100" minlength="1" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <phone-input label="Phone 1" v-model="phone_one"></phone-input>
                </div>
                <div class="col-sm-6" v-error-handler="{ value: email, handle: 'email' }">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate="'required|email'">
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
                <div class="col-sm-4" v-error-handler="{ value: city, handle: 'city' }">
                    <label for="infoCity">City</label>
                    <input type="text" class="form-control" v-model="city" name="city" id="infoCity" placeholder="City">
                </div>
                <div class="col-sm-4" v-error-handler="{ value: state, handle: 'state' }">
                    <label for="infoState">State/Prov.</label>
                    <input type="text" class="form-control" v-model="state" name="state" id="infoState" placeholder="State/Province">
                </div>
                <div class="col-sm-4" v-error-handler="{ value: zip, handle: 'zip' }">
                    <label for="infoZip">ZIP/Postal Code</label>
                    <input type="text" class="form-control" v-model="zip" name="zip" id="infoZip" placeholder="12345">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12" v-error-handler="{ value: comments, handle: 'comments' }">
                    <label for="name">Questions, Comments, or Ideas</label>
                    <textarea class="form-control" name="comments" id="comments" v-model="comments" v-validate="'required'" rows=10 autosize></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </div>
        </form>

</template>
<script type="text/javascript">
    import $ from 'jquery';
    import errorHandler from './error-handler.mixin'

    export default{
    	name: 'speaker-form',
        mixins: [errorHandler],
        data(){
            return{
                name:'',
                organization:'',
                phone_one: '',
                email: '',
                address_one: '',
                address_two: '',
                city: '',
                state: '',
                zip: '',
                comments: '',
            }
        },
        methods:{
            reset(){
                $.extend(this, {
                    name:'',
                    organization:'',
                    phone_one: '',
                    email: '',
                    address_one: '',
                    address_two: '',
                    city: '',
                    state: '',
                    zip: '',
                    comments: '',
                });
            },
            submit() {
                let data = {
                    name: this.name,
                    organization: this.organization,
                    phone_one: this.phone_one,
                    email: this.email,
                    address_one: this.address_one,
                    address_two: this.address_two,
                    city: this.city,
                    state: this.state,
                    zip: this.zip,
                    comments: this.comments,
                };

                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check that the form is complete');
                        return false;
                    }

                    this.$http.post('speaker', data).then((response) => {
                        console.log(response);
                        this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
                        this.reset();
                    }, (error) =>  {
                        console.log(error);
                        this.$root.$emit('showError', 'Something went wrong...');
                    });
                })
            }
        },
        mounted(){
            
        }
    }
</script>
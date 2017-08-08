<template>
    <validator name="SpeakerForm">
        <form class="form-horizontal" novalidate style="position:relative;">
            <spinner ref="spinner" size="sm" text="Loading"></spinner>
            <div class="form-group">
                <div class="col-sm-6" :class="{ 'has-error': errors.has('name') }">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="John Smith" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
                <div class="col-sm-6" :class="{ 'has-error': errors.has('organization') }">
                    <label for="name">Your Church/Organization</label>
                    <input type="text" class="form-control" name="organization" id="organization" v-model="organization"
                           placeholder="Church Name" v-validate:organization="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-6">
                    <label for="infoPhone">Phone 1</label>
                    <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
                </div>
                <div class="col-sm-6" :class="{ 'has-error': errors.has('email') }">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate:email="['required', 'email']">
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
                <div class="col-sm-4">
                    <label for="infoCity">City</label>
                    <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
                </div>
                <div class="col-sm-4">
                    <label for="infoState">State/Prov.</label>
                    <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
                </div>
                <div class="col-sm-4">
                    <label for="infoZip">ZIP/Postal Code</label>
                    <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12" :class="{ 'has-error': errors.has('comments') }">
                    <label for="name">Questions, Comments, or Ideas</label>
                    <textarea type="text" class="form-control" name="comments" id="comments" v-model="comments" v-validate:comments="{required: true}" rows=10 autosize></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a @click="submit()" class="btn btn-primary">Send Request</a>
                </div>
            </div>
        </form>
    </validator>
</template>
<script type="text/javascript">
    export default{
    	name: 'speaker-form',
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
                attemptSubmit:false,
            }
        },
        methods:{
            errors.has(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$SpeakerForm[field].invalid && this.attemptSubmit;
            },
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
                    attemptSubmit:false,
                });
                this.attemptSubmit = false;
            },
            submit(){
                this.attemptSubmit = true;
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

                if (this.$SpeakerForm.valid) {
                    this.$http.post('speaker', data).then(function (response) {
                        console.log(response);
                        this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
                        this.reset();
                    }, function (error) {
                        console.log(error);
                        this.$root.$emit('showError', 'Something went wrong...');
                    });
                } else {
                    this.$root.$emit('showError', 'Please check that the form is complete');
                }
            }
        },
        mounted(){
            
        }
    }
</script>
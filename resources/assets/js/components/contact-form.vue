<template>
    <validator name="CreateGroup">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <form class="form-horizontal" novalidate>
            <div class="form-group">
                <div class="col-sm-6" :class="{ 'has-error': checkForError('name') }">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" id="name" v-model="name"
                           placeholder="John Smith" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
                           maxlength="100" minlength="1" required>
                </div>
                <div class="col-sm-6">
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
                <div class="col-sm-6" :class="{ 'has-error': checkForError('email') }">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate:email="['required', 'email']">
                </div>
            </div>

            <div class="form-group" :class="{ 'has-error': checkForError('comments') }">
                <div class="col-sm-12">
                    <label for="name">Questions, Comments, or Ideas</label>
                    <textarea type="text" class="form-control" name="comments" id="comments" v-model="comments" v-validate:comments="{required: true}"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <a @click="submit()" class="btn btn-primary">Submit</a>
                </div>
            </div>
        </form>
    </validator>
</template>
<style scoped>
    body{
        background-color:#ff0000;
    }
</style>
<script type="text/javascript">
    export default{
        name: 'contact-form',
        data(){
            return {
                name: '',
                organization: '',
                phone_one: '',
                email: '',
                comments: '',
                attemptSubmit: false,
            }
        },
        methods: {
            checkForError(field){
                // if user clicked submit button while the field is invalid trigger error styles 
                return this.$CreateGroup[field].invalid && this.attemptSubmit;
            },
            reset(){
                $.extend(this, {
                    name: '',
                    organization: '',
                    phone_one: '',
                    email: '',
                    comments: '',
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
                    comments: this.comments,
                };

                if (this.$CreateGroup.valid) {
                    // this.$refs.spinner.show();
                    this.$http.post('contact', data).then(function (response) {
                        console.log(response);
                        // this.$refs.spinner.hide();
                        this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
                        this.reset();
                    }, function (error) {
                        console.log(error);
                        this.$root.$emit('showError', 'Something went wrong...');
                        // this.$refs.spinner.hide();
                    });
                } else {
                    this.$root.$emit('showError', 'Please check that the form is complete');
                }
            }
        },
        ready(){

        }
    }
</script>
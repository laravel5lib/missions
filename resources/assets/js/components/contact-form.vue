<template>
    <validator name="CreateGroup">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <form class="form-horizontal" novalidate>
            <div class="form-group">
                <div class="col-sm-6">
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
                <div class="col-sm-6">
                    <label for="name">Email</label>
                    <input type="text" class="form-control" name="email" id="email" v-model="email" v-validate:email="['email']">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="name">Questions, Comments, or Ideas</label>
                    <textarea type="text" class="form-control" name="comments" id="comments" v-model="comments"></textarea>
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
            return{
                name:'',
                organization:'',
                phone_one:'',
                email:'',
                comments:'',
            }
        },
        methods: {
            reset(){
                $.extend(this, {
                    name:'',
                    organization:'',
                    phone_one:'',
                    email:'',
                    comments:'',
                });
            },
            submit(){
                let data = {
                    name: this.name,
                    organization: this.organization,
                    phone_one: this.phone_one,
                    email: this.email,
                    comments: this.comments,
                };

                this.$refs.spinner.show();
                this.$http.post('contact', data).then(function (response) {
                    console.log(response);
                    this.$refs.spinner.hide();
                    this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
                    this.reset();
                }, function (error) {
                    console.log(error);
                    this.$root.$emit('showError', 'Something went wrong...');
                    this.$refs.spinner.hide();
                })
            }
        },
        ready(){
            
        }
    }
</script>
<template>
	<form name="CreateGroup" class="form-horizontal" novalidate style="position:relative;" @submit.prevent="submit">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="form-group">
			<div class="col-sm-6" :class="{ 'has-error': errors.has('name') }">
				<label for="name">Name</label>
				<input type="text" class="form-control" id="name" v-model="name"
				       placeholder="John Smith" name="name" v-validate="'required|alpha_spaces|min:1|max:100'"
				       maxlength="100" minlength="1" required>
			</div>
			<div class="col-sm-6">
				<label for="name">Your Church/Organization</label>
				<input type="text" class="form-control" name="organization" id="organization" v-model="organization"
				       placeholder="Church Name"
				       maxlength="100" minlength="1">
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-6">
				<phone-input label="Phone 1" v-model="phone_one"></phone-input>
			</div>
			<div class="col-sm-6" :class="{ 'has-error': errors.has('email') }">
				<label for="name">Email</label>
				<input type="text" class="form-control" name="email" id="email" v-model="email"
				       v-validate="'required|email'">
			</div>
		</div>

		<div class="form-group" :class="{ 'has-error': errors.has('comments') }">
			<div class="col-sm-12">
				<label for="name">Questions, Comments, or Ideas</label>
				<textarea type="text" class="form-control" name="comments" id="comments" v-model="comments" rows=10 maxlength="500" v-validate="'required'" autosize></textarea>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12 text-center">
				<button type="submit" class="btn btn-primary">Submit</button>
			</div>
		</div>
	</form>
</template>
<script type="text/javascript">
  import $ from 'jquery';

  export default {
    name: 'contact-form',
    data() {
      return {
        name: '',
        organization: '',
        phone_one: '',
        email: '',
        comments: '',
      }
    },
    methods: {
      reset() {
        $.extend(this, {
          name: '',
          organization: '',
          phone_one: '',
          email: '',
          comments: '',
        });
        this.$validator.reset();
      },
      submit() {
        let data = {
          name: this.name,
          organization: this.organization,
          phone_one: this.phone_one,
          email: this.email,
          comments: this.comments,
        };

        this.$validator.validateAll().then(result => {
          if (!result) {
            this.$root.$emit('showError', 'Please check that the form is complete');
            return false;
          }

          // this.$refs.spinner.show();
          this.$http.post('contact', data).then((response) => {
            console.log(response);
            // this.$refs.spinner.hide();
            this.$root.$emit('showSuccess', 'Message Sent. Thank you for contacting us!');
            this.reset();
          }, (error) => {
            console.log(error);
            this.$root.$emit('showError', 'Something went wrong...');
            // this.$refs.spinner.hide();
          });
        });
      }
    },
    mounted() {

    }
  }
</script>

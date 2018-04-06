<template>
<form @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
  <h3>Sharing Options</h3>
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="row">
				<div class="col-sm-4">
					<h5>Fundraiser Link</h5>
					<p class="text-muted">
						A web link that others can use to view your fundraiser. We recommend keeping it short and memorable.</p>
				</div>
				<div class="col-sm-8">
					<div class="form-group">
						<div class="col-xs-12">
							<label>Shareable Link</label>
							<pre style="overflow: scroll">https://missions.me/{{ fundraiser.url }}</pre>
							<span class="help-block">Copy &amp; Paste to share this link inside of emails to spread the word!</span>
						</div>
					</div>
					<div class="form-group" :class="{'has-error' : form.errors.has('url')}">
						<div class="col-xs-12">
							<label for="url">Change Link</label>
							<div class="input-group">
                <span class="input-group-addon">
                    missions.me/
                </span>
								<input type="text" class="form-control" id="url" v-model="form.url" required>
							</div>
              <span class="help-block" v-text="form.errors.get('url')" v-if="form.errors.has('url')"></span>
						</div>
					</div>
				</div>
			</div>
			<hr class="divider">
			<div class="row">
				<div class="col-sm-4">
					<h5>Privacy Settings</h5>
					<p class="text-muted">Change who can see your fundraiser.</p>
				</div>
				<div class="col-sm-8">

					<div class="form-group">
						<div class="col-xs-12">
							<div class="checkbox">
								<label>
									<input type="checkbox" v-model="form.public" :disabled="!fundraiser.description">
									Show Fundraiser to the Public (Highly Recommended)
								</label>
                <span class="help-block text-danger" v-if="!fundraiser.description">Your fundraiser must have content before you can make it public.</span>
								<span class="help-block" v-else>Private fundraisers can only be seen by you and cannot be shared.</span>
							</div>
						</div>
					</div>

                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <div class="form-group">
                <hr class="divider inv md">
                <div class="col-xs-12 text-right">
                    <a :href="'/' + fundraiser.url" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                <hr class="divider inv md">
            </div>
        </div>
    </div></div>
</form>
</template>

<script>
  export default {

    props: {
      fundraiser: {
        type: Object,
        required: true
      }
    },

    data() {
      return {
        ui: {
          checkingUrl: false,
          validUrl: true
        },
        form: new Form({
          url: '',
          public: false
        })
      };
    },

    methods: {
      onSubmit() {
        this.form.submit('put', '/fundraisers/'+this.fundraiser.uuid)
          .then(data => {
            swal("Good job!", "Your fundraiser has been updated.", "success", {
              button: true,
              timer: 3000
            });
            this.$emit('fundraiserSettingsChanged', data.data);
          });
      }
    },

    mounted() {
      this.form.url = this.fundraiser.url;
      this.form.public = this.fundraiser.public;
    }

  };
</script>
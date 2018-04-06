<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3>Start a Fundraiser</h3>
        </div>
        <div class="panel-body">
            <div class="form-group" v-error-handler="{ value: fundraiser.name, handle: 'name' }">
                <div class="col-xs-12">
                    <label for="name">Title</label>
                    <span class="help-block">Give your fundraiser a name.</span>
                    <input type="text"
                            class="form-control"
                            name="name"
                            id="name"
                            v-model="fundraiser.name"
                            debounce="250"
                            placeholder="Fundraiser Name"
                            v-validate="'required|min:1|max:100'"
                            maxlength="100"
                            minlength="1"
                            required>
                        <hr class="divider inv">
                </div>
            </div>
            <div class="form-group"
                 :class="{ 'has-error': ui.validUrl === false, 'has-success': ui.validUrl === true }">
                <div class="col-xs-12">
                    <label for="url">Page Link</label>
                    <span class="help-block">Provide a link to share access to your fundraising page.</span>
                    <div class="input-group">
                        <span class="input-group-addon">
                            missions.me/
                        </span>
                        <input type="text" class="form-control" id="url" v-model="fundraiser.url"
                                @change="debouncedValidateUrl" required>
                        <span class="input-group-addon" id="url_addon">
                            <i class="fa fa-spinner fa-spin" v-if="ui.checkingUrl"></i>
                            <i class="fa fa-check" v-if="ui.checkingUrl === false && ui.validUrl === true"></i>
                            <i class="fa fa-times" v-if="ui.checkingUrl === false && ui.validUrl === false"></i>
                        </span>
                    </div>
                    <span class="help-block" v-if="! ui.validUrl">This link is being used by another fundraiser. Please try a different one.</span>
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <hr class="divider inv">
            <a href="/dashboard" class="btn btn-link">Cancel</a>
            <button class="btn btn-primary" @click="create()">Create</button>
            <hr class="divider inv">
        </div>
    </div>
</template>

<script type="text/javascrip">
import errorHandler from '../error-handler.mixin';

export default {

    mixins: [errorHandler],

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
          validUrl: true,
        }
      };
    },

    methods: {
        debouncedValidateUrl: _.debounce(function () {
            this.validateUrl()
        }, 500),

        validateUrl() {
            this.ui.checkingUrl = true;
            this.$http.get('fundraisers', {params: {url: this.fundraiser.url}}).then((response) => {
            if (response.data.data.length) {
                this.ui.validUrl = response.data.data[0].id === this.fundraiser.id;
            } else {
                this.ui.validUrl = true;
            }

            // validat empty url string for private fundraisers
            if (this.fundraiser.public === false && this.fundraiser.url === '') {
                this.ui.validUrl = true;
            }
            this.ui.checkingUrl = false;
            }, (error) => {
            console.log(error);
            });
        },

        create() { 
            this.$http.post('fundraisers', this.fundraiser)
                .then((response) => {
                    swal("Good job!", "Your fundraiser was created.", "success", {
                        closeOnClickOutside: false
                    })
                    .then((value) => {
                        if (value) {
                            window.location.href = '/' + this.firstUrlSegment + '/fundraisers/'+ response.data.data.id +'/edit';
                        }
                    });
                }, this.$root.handleApiError);
        }
    }
}
</script>

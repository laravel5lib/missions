<template>
<div>
  <a :class="class" @click="confirm = true">
    <i :class="icon" v-if="icon"></i> {{ label }}
  </a>
  <modal :title="label" :show.sync="confirm" effect="fade" small="true" ok-text="Send" :callback="fire">
    <div slot="modal-body" class="modal-body text-center">
      <h5>Send Email?</h5>
      <hr class="divider inv">
      <label>Recipient Email Address</label>
      <input type="text" class="form-control" v-model="parameters.email" />
    </div>
  </modal>
  <hr class="divider inv sm">
</div>
</template>
<script type="text/javascript">
  export default{
    name: 'send-email',
    props: {
      label: {
          type: String,
          default: 'Send Email'
      },
      command: {
          type: String,
          required: true
      },
      parameters: {
        type: Object,
        required: true
      },
      icon: {
        type: String,
        default: null
      },
      class: {
        type: String,
        default: null
      }
    },
    data() {
      return {
        confirm: false
      }
    },
    methods: {
      fire() {
        this.confirm = false;
        this.$http.post('commands', {command: this.command, parameters: this.parameters})
            .then(function(response) {
              console.log(response);
              this.$dispatch('showSuccess', 'Your request was processed successfully.');
            }, function (error) {
              console.log(error);
              this.$dispatch('showError', 'Unable to process your request.');
            });
      }
    }
  }
</script>
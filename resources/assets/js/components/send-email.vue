<template>
<div>
  <a :class="classes" @click="confirm = true">
    <i :class="icon" v-if="icon"></i> {{ label }}
  </a>
  <modal :title="label" :value="confirm" @closed="confirm=false" effect="fade" :small="true" ok-text="Send" :callback="fire">
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
      classes: {
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
            .then((response) => {
              console.log(response);
              this.$root.$emit('showSuccess', 'Your request was processed successfully.');
            }, (error) =>  {
              console.log(error);
              this.$root.$emit('showError', 'Unable to process your request.');
            });
      }
    }
  }
</script>
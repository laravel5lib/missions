<template>
	<span>{{displayedText}}</span>
</template>
<script type="text/javascript">
  export default {
    name: 'listen-text',
    props: {
      text: {
        type: String,
        default: ''
      },
      event: {
        type: String,
        required: true
      }
    },
    data() {
      return {
        displayedText: '',
      }
    },
    watch: {
      text(val) {
        this.displayedText = val;
      }
    },
    mounted() {
      this.displayedText = this.text;
      this.$root.$on(this.event, (val) => {
        this.displayedText = val;
      });

    }
  }
</script>

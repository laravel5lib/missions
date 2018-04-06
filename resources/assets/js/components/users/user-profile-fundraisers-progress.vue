<template>
	<div class="clearfix">
		<p class="small" style="margin: 0; padding: 0;">{{ displayNow + '%' }} <span
				class="pull-right">{{ goal }}</span></p>
		<div class="col-xs-12 clearfix">
			<div class="row" style="background: #f5f5f5; border-radius: 10px;">
				<progressbar :now="displayNow" type="success"
				             style="max-width: 100%;border-radius: 10px; height: 10px;"></progressbar>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
  export default {
    name: 'user-profile-fundraisers-progress',
    props: {
      now: {
        type: Number,
        default: 0
      },
      goal: {
        type: String,
        default: '$0'
      }
    },
    data() {
      return {
        displayNow: 0,
      }
    },
    watch: {
      now(val) {
        this.displayNow = Number(val);
      }
    },
    mounted() {
      this.displayNow = this.now;
      this.$root.$on('Fundraiser::percent', (val) => {
        this.$emit('update:now', Number(val));
        this.displayNow = Number(val);
      });
    }
  }
</script>

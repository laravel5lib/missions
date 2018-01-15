<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Rooming and Additional Trip Options</h4>
			<hr class="divider"/>
			<hr class="divider inv sm"/>

			<form novalidate name="AdditionalOptionsForm">
				<div class="" v-for="(option, index) in optionalCosts" :key="option.id">
					<label style="display:block" :for="'option' + index">
						<input type="radio" :id="'option' + index" v-model="selectedOptions" :value="option.id" name="additional" v-validate="index === 0 ? 'required' : ''">
						{{option.name}}
						<span class="pull-right">{{currency(option.amount)}}</span>
					</label>
					<span class="help-block">{{option.description}}</span>
					<hr class="divider lg">
				</div>
				<h5 v-if="optionalCosts.length==0">
					No additional options available
				</h5>
			</form>

		</div>
	</div>
</template>
<script type="text/javascript">
  import _ from 'underscore';
  import errorHandler from '../../error-handler.mixin'

  export default {
    name: 'additional-trip-options',
    mixins: [errorHandler],
    data() {
      return {
        title: 'Additional Trip Options',
        selectedOptions: null
      }
    },
    computed: {
      optionalCosts() {
        let arr = this.$parent.tripCosts.optional || [];

        return _.sortBy(arr, 'name');
      }
    },
    watch: {
      optionalCosts(val) {
        if (_.isArray(val) && val.length > 0) {
          this.selectedOptions = val[0].id;
          this.$parent.selectedOptions = _.where(this.optionalCosts, { id: this.selectedOptions})[0];
        }
      },
      selectedOptions(val, oldVal) {
        this.$parent.selectedOptions = _.where(this.optionalCosts, { id: val})[0];
        if (val) {
          this.$nextTick(() => {
            this.$emit('step-completion', true);
          });
        }
      },
      isFormDirty() {
        this.$emit('step-completion', true);
      },
    },
    methods: {},
    mounted() {},
    activated() {
      $('html, body').animate({scrollTop: 200}, 300);
    }
  }
</script>

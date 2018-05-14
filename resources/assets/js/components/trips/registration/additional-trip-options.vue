<template>
	<div class="row">
		<div class="col-sm-12">
			<form novalidate name="AdditionalOptionsForm">
        <h5 v-if="optionalCosts.length==0">
          <hr class="divider inv">
					No rooming options available
				</h5>
        <table class="table" v-else>
          <thead>
            <tr class="active">
              <th>Room Type</th>
              <th class="text-right">Cost</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(option, index) in optionalCosts" :key="option.id">
              <td>
                <label>
                <input type="radio" :id="'option' + index" 
                       v-model="selectedOptions" 
                       :value="option.id" 
                       name="additional" 
                       v-validate="index === 0 ? 'required' : ''"
                > {{option.cost.name}}
                </label>
                <span class="help-block">{{option.cost.description}}</span>
              </td>
              <td class="text-right">
                <strong>{{currency(option.amount)}}</strong>
              </td>
            </tr>
          </tbody>
        </table>
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
        title: 'Rooming Options',
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
      $('html, body').animate({scrollTop: 0}, 300);
    }
  }
</script>

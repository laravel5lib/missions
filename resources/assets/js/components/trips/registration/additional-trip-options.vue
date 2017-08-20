<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Rooming and Additional Trip Options</h4>
            <hr class="divider" />
            <hr class="divider inv sm" />

				<form novalidate name="AdditionalOptionsForm">
					<div class="" v-for="option in optionalCosts">
						<label style="display:block" :for="'option' + $index">
							<input type="radio" :id="'option' + $index" v-model="selectedOptions" :value="option" name="additional" v-validate="$index === 0 ? 'required' : ''">
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

	export default{
		name: 'additional-trip-options',
		data(){
			return {
				title: 'Additional Trip Options',
				atoComplete: true,
//				optionalCosts: [],
				selectedOptions: null
			}
		},
		computed:{
			optionalCosts(){
			    let arr = this.$parent.tripCosts.optional || [];
			    if (!arr.length) {
                    this.$emit('ato-complete', true);
			    }
				return _.sortBy(arr, 'name');
			}
		},
		watch:{
			'atoComplete'(val, oldVal) {
				this.$emit('ato-complete', val)
			},
			'selectedOptions'(val, oldVal) {
				this.$parent.selectedOptions = val;
			}
		},
		methods: {
            onValid(){
                this.$emit('ato-complete', true);
            },

        },
		mounted(){

		},
		activate(done){
			$('html, body').animate({scrollTop : 200},300);
			done();
		}
	}
</script>

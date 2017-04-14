<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Rooming and Additional Trip Options</h4>
            <hr class="divider" />
            <hr class="divider inv sm" />
			<validator name="AdditionalOptions" @valid="onValid">
				<form novalidate name="AdditionalOptionsForm">
					<div class="" v-for="option in optionalCosts | orderBy 'name'">
						<label style="display:block" for="option{{$index}}">
							<input type="radio" id="option{{$index}}" v-model="selectedOptions" :value="option" v-validate:additional="$index === 0 ? ['required'] : ''">
							{{option.name}}
							<span class="pull-right">{{option.amount | currency}}</span>
						</label>
						<span class="help-block">{{option.description}}</span>
						<hr class="divider lg">
					</div>
					<h5 v-if="optionalCosts.length==0">
						No additional options available
					</h5>
				</form>
			</validator>
        </div>
    </div>
</template>
<script type="text/javascript">
	export default{
		name: 'additional-trip-options',
		data(){
			return {
				title: 'Additional Trip Options',
				atoComplete: true,
				optionalCosts: [],
				selectedOptions: null
			}
		},
		computed:{
			optionalCosts(){
			    let arr = this.$parent.tripCosts.optional;
			    if (!arr.length) {
                    this.$dispatch('ato-complete', true);
			    }
				return arr;
			}
		},
		watch:{
			'atoComplete'(val, oldVal) {
				this.$dispatch('ato-complete', val)
			},
			'selectedOptions'(val, oldVal) {
				this.$parent.selectedOptions = val;
			}
		},
		methods: {
            onValid(){
                this.$dispatch('ato-complete', true);
            },

        },
		ready(){

		},
		activate(done){
			$('html, body').animate({scrollTop : 200},300);
			done();
		}
	}
</script>

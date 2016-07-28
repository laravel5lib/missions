<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Additional Trip Options</h4>
            <hr class="divider" />
            <hr class="divider inv sm" />
			<validator name="AdditionalOptions">
				<form>
					<div class="checkbox" v-for="option in optionalCosts | orderBy 'name'">
						<label style="display:block" for="option{{$index}}">
							<input type="checkbox" id="option{{$index}}" v-model="selectedOptions" :value="option">
							{{option.name}}
							<span class="pull-right">{{option.amount | currency}}</span>
						</label>
					</div>
					<h5 v-if="optionalCosts.length==0">
						No additional options available
					</h5>
				</form>
			</validator>
        </div>
    </div>
</template>
<script>
	export default{
		name: 'additional-trip-options',
		data(){
			return {
				title: 'Additional Trip Options',
				atoComplete: true,
				optionalCosts: [],
				selectedOptions: []
			}
		},
		computed:{
			optionalCosts(){
				return this.$parent.tripCosts.optional;
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
		ready(){
		    this.$dispatch('ato-complete', true);
		}
	}
</script>

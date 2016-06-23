<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Additional Trip Options</h4>
			<validator name="AdditionalOptions">
				<form>
					<div class="checkbox" v-for="op in options | orderBy 'name'">
						<label style="display:block" for="option{{$index}}">
							<input type="checkbox" id="option{{$index}}" v-model="selectedOptions" :value="op">
							{{op.name}}
							<span class="pull-right">{{op.amount | currency}}</span>
						</label>
					</div>
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
				options: [],
				selectedOptions: []
			}
		},
		computed:{
			options(){
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

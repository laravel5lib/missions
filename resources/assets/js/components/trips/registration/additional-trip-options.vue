<template>
    <div class="row">
        <div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
            <h4>Additional Trip Options</h4>

        </div>
        <div class="col-sm-12">
            <hr>
        </div>
        <div class="col-sm-12">

        </div>
    </div>
</template>
<script>
	export default{
		name: 'additional-trip-options',
		data(){
			return {
				title: 'Additional Trip Options',
				atoComplete: true
			}
		},
		watch:{
			'atoComplete'(val, oldVal) {
				this.$dispatch('ato-complete', val)
			}
		},
		ready(){
		    // until process is added
		    this.$dispatch('ato-complete', true)
		}

	}
</script>

<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Review</h4>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sit amet convallis nisl. Aenean
				varius non enim tincidunt sagittis. Ut quis cursus orci, nec posuere sem. Fusce dignissim porttitor
				tristique. Etiam in orci metus. Donec facilisis vel erat quis rhoncus. Etiam non pulvinar est, a
				efficitur massa. Curabitur feugiat ornare quam, at aliquet urna auctor eget. Proin id porta odio.
				Suspendisse tincidunt, mi et faucibus suscipit, ex enim pretium nisi, in sagittis lacus ipsum gravida
				diam. Nullam lacinia nunc nec ante tincidunt egestas. Integer id arcu in turpis pretium convallis a sit
				amet purus.
			</p>

		</div>
		<div class="col-sm-12">
			<hr>
		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="review">
					I have reviewed and verified that all information provided is correct.
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'review',
		data(){
			return {
				title: 'Review',
				review: false
			}
		},
		watch:{
			'review'(val, oldVal) {
				this.$dispatch('review', val)
			}
		}

	}
</script>

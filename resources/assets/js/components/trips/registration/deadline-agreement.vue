<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Deadline Agreement</h4>
			<p>
				Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse sit amet convallis nisl. Aenean
				varius non enim tincidunt sagittis. Ut quis cursus orci, nec posuere sem. Fusce dignissim porttitor
				tristique. Etiam in orci metus. Donec facilisis vel erat quis rhoncus. Etiam non pulvinar est, a
				efficitur massa. Curabitur feugiat ornare quam, at aliquet urna auctor eget. Proin id porta odio.
				Suspendisse tincidunt, mi et faucibus suscipit, ex enim pretium nisi, in sagittis lacus ipsum gravida
				diam. Nullam lacinia nunc nec ante tincidunt egestas. Integer id arcu in turpis pretium convallis a sit
				amet purus.
			</p>
			<p>
				Sed nisi velit, venenatis et laoreet at, cursus at diam. Fusce eu sodales mi. Etiam orci urna, congue
				placerat erat sed, tincidunt mattis diam. In vel mauris blandit, iaculis mauris sed, faucibus magna.
				Fusce non mattis nisi. Morbi faucibus, nisl id maximus scelerisque, tellus ipsum placerat felis, quis
				pellentesque metus est in leo. Nullam enim urna, luctus ac erat tincidunt, finibus vestibulum arcu.
				Vestibulum sed maximus lorem. Proin blandit nibh sapien, ac rhoncus neque dapibus vitae. Suspendisse
				volutpat mi turpis, non tincidunt nisl vulputate et. Vestibulum mollis elit at ex tempus, ut tristique
				lorem malesuada. Vivamus arcu velit, porttitor ac facilisis quis, elementum ut nisl.
			</p>
			<p>
				Sed eros turpis, tempor sed bibendum quis, imperdiet ultrices arcu. Maecenas lobortis urna eu urna
				efficitur rutrum. Maecenas nec ultricies lorem. Curabitur fermentum risus diam, in suscipit nibh
				ultricies eget. Vestibulum porttitor tincidunt pharetra. Nullam mollis lorem nibh, at dictum ligula
				condimentum in. Cras quis diam eget purus blandit egestas et nec risus. Nullam id arcu lectus.
			</p>
			<p>
				Mauris iaculis scelerisque risus, nec consectetur sapien imperdiet ac. Vestibulum vestibulum et lorem ac
				viverra. Sed a lacus a tortor aliquam tempus eu vel neque. Praesent feugiat leo odio, nec pulvinar felis
				euismod sed. Maecenas molestie dolor eget vestibulum ultricies. Nam sed ipsum sed nisi euismod maximus.
				Aliquam sem metus, euismod cursus neque id, volutpat bibendum metus. Maecenas blandit elit vitae nisi
				consequat commodo.
			</p>
			<p>
				Maecenas ullamcorper in nisi vitae commodo. Aliquam porttitor turpis nulla, a ornare felis ultricies sit
				amet. Praesent tempor diam vel magna tincidunt semper. Sed auctor libero eu libero rhoncus dictum tempus
				in nunc. Suspendisse dignissim massa sed leo feugiat interdum. Mauris ornare lacus nec cursus cursus.
				Cras ac ligula mi. Maecenas tempus nec augue non commodo. Curabitur at risus turpis. Maecenas mollis
				lorem nec congue euismod.
			</p>
			<p>
				Suspendisse sit amet volutpat diam. Nunc varius a metus vel vestibulum. Nulla accumsan orci eget magna
				posuere, non aliquam tortor dictum. Integer ultrices gravida leo, a iaculis elit pulvinar porttitor.
				Morbi et convallis purus. Suspendisse vel dolor dolor. Donec vitae neque justo. Nullam interdum tellus
				sed lorem accumsan, id sollicitudin sem tristique. In quis facilisis ligula, imperdiet interdum sem.
				Duis dignissim imperdiet imperdiet. Maecenas et bibendum purus, in vulputate lorem.
			</p>
		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="deadlineAgree">
					I have read and agree to the Deadline Agreement.
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'deadline-agreement',
		data(){
			return {
				title: 'Deadline Agreement',
				deadlineAgree: false
			}
		},
		watch:{
			'deadlineAgree'(val, oldVal) {
				this.$dispatch('deadline-agree', val)
			}
		}

	}
</script>

<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 300px;overflow-y: auto;">
			<p>
				Ut in consequat sapien. Morbi gravida sapien in posuere volutpat. Integer vel enim luctus, luctus massa
				vel, ullamcorper sapien. Duis scelerisque laoreet lectus, quis auctor ex mollis vel. Curabitur quis
				blandit tellus. Suspendisse ipsum est, fringilla at feugiat quis, consectetur aliquam lectus. Morbi id
				consectetur felis. Praesent feugiat, eros eu interdum suscipit, sem quam lobortis arcu, varius bibendum
				ante nunc quis turpis. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia
				Curae; Mauris finibus consequat nisl, vel dictum ante interdum vel. Cum sociis natoque penatibus et
				magnis dis parturient montes, nascetur ridiculus mus. Nulla pharetra dignissim ante, et pharetra nunc
				condimentum quis. Donec vulputate viverra metus, eu accumsan dolor accumsan eu. Aenean dignissim lectus
				non fermentum aliquam. Donec blandit turpis nec tellus convallis congue.
			</p><p>
				Cras eu mauris et sapien tincidunt pretium. Vestibulum ornare placerat nunc, ac porta orci ullamcorper
				faucibus. Duis vitae placerat augue, sed condimentum lacus. Vestibulum sit amet ultricies mauris. Mauris
				nulla neque, suscipit quis mi molestie, tempus consequat leo. Donec vestibulum eget lectus non auctor.
				Sed sed risus vel ex ullamcorper fermentum. Mauris vitae rhoncus ex. Vivamus luctus lacus sed lacus
				gravida varius. Donec eget metus feugiat mi aliquet cursus ac sit amet ligula. Morbi non interdum orci,
				sit amet volutpat orci. Praesent rutrum libero et scelerisque interdum. Fusce sed fringilla diam, sit
				amet consectetur odio. Quisque dolor metus, blandit ut accumsan sed, consectetur ut tortor. Vestibulum
				ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae;
		</p><p>
				Sed nec est vitae turpis dictum porta eu sed sapien. Fusce ac vulputate dolor. Duis lacus tellus,
				sagittis et nisl vitae, facilisis varius risus. Cras metus enim, tempor consectetur sollicitudin eget,
				blandit in purus. Proin rutrum odio vehicula, pellentesque nunc sit amet, aliquet lorem. Aenean tellus
				orci, commodo malesuada pellentesque at, placerat sed purus. Duis purus arcu, euismod eget nisl a,
				eleifend laoreet dolor.
		</p><p>
				Donec lectus tellus, fermentum sed quam vitae, facilisis posuere lectus. In hendrerit fringilla diam, a
				commodo turpis rhoncus non. Integer laoreet auctor sem in congue. Fusce pretium quam ac dapibus
				placerat. In et ipsum nec quam molestie volutpat ut nec sem. Maecenas sed ullamcorper ipsum. Aenean
				nulla urna, ultrices vel odio sed, rhoncus molestie quam. Ut volutpat est vel facilisis dignissim.
				Maecenas eleifend vestibulum sem vel venenatis. Nullam id quam dignissim, aliquet lectus et, ultrices
				odio. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
				Vestibulum volutpat, justo quis hendrerit mollis, metus ligula lacinia felis, non sollicitudin eros urna
				ac massa. Cras eu diam ac nisl euismod congue. Phasellus aliquet id quam in fermentum. Etiam vel
				consectetur mi.
		</p><p>
				Nam et magna in dolor feugiat porta sit amet et est. Vestibulum ante ipsum primis in faucibus orci
				luctus et ultrices posuere cubilia Curae; Duis dapibus sem ac lacus bibendum rutrum. Nunc pharetra orci
				vel erat vestibulum, at pulvinar nulla consequat. Nulla eget neque sodales, lacinia elit vitae, luctus
				tellus. Cras imperdiet, justo sed aliquam viverra, nisi dui gravida neque, at maximus elit risus et
				metus. Etiam commodo tincidunt elit, ut luctus justo vulputate et. In nunc lacus, convallis a ultricies
				non, interdum et metus. Duis scelerisque nulla ut gravida volutpat. Sed ex nisl, mattis finibus urna ac,
				dapibus mattis mauris. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus
				mus. Sed commodo dictum ex, fringilla convallis odio facilisis et. Donec a turpis nec lorem ullamcorper
				commodo vel ut nisi.
		</p><p>
				Ut consequat neque velit, eu porta nisi dictum eu. Donec lacinia dictum turpis, sed eleifend dolor
				finibus sit amet. Etiam eget vehicula lacus. Vestibulum non nulla mollis, iaculis est in, interdum sem.
				Sed tincidunt eu lorem nec scelerisque. Proin quam lacus, eleifend quis arcu sit amet, facilisis
				interdum nisl. Sed posuere ac lectus a rhoncus. Cras augue mi, pretium eget maximus non, blandit vel
				ipsum. Phasellus pretium purus quis euismod vulputate. Pellentesque feugiat nec nisl sollicitudin
				aliquam. Donec eu eros augue.
			</p>
		</div>
		<div class="col-sm-12">
			<hr>
		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="rocaAgree">
					I have read and agree to the Rules of Conduct Agreement.
				</label>
			</div>
		</div>
	</div>
</template>
<script>
	export default{
		name: 'roca',
		data(){
			return {
				title: 'Rules of Conduct Agreement',
				rocaAgree: false
			}
		},
		watch:{
			'rocaAgree'(val, oldVal) {
				this.$dispatch('roca-agree', val)
			}
		}
	}
</script>

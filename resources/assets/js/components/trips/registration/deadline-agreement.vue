<template>
	<div class="row">
		<div class="col-sm-12">
			<p>The following items must be completed in order to travel. Your trip representative will contact you soon regarding these necessary documents.</p>
			<div class="list-group">
				<div class="list-group-item" v-for="requirement in requirementsOrdered">
					<h5 class="list-group-item-heading">
						{{requirement.name}}
					</h5>
					<p class="list-group-item-text">
						{{requirement.short_desc}}<br />
						<small class="text-danger">
							Due before {{ toDate(requirement.due_at) }}.
						</small>
					</p>
				</div>
			</div>
			<hr>
			<template v-if="deadlines.length">
				<h4>Other Deadlines</h4>
				<div class="list-group">
					<div class="list-group-item" v-for="deadline in deadlines">
						<h5 class="list-group-item-heading">
							{{deadline.name}}
						</h5>
						<p class="list-group-item-text">
							Due before {{ toDate(deadline.date) }}.
						</p>
					</div>
				</div>
			</template>

		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="deadlineAgree">
					I have read and agree to provide the information above before the given due dates or risk being dropped from the trip.
				</label>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	import $ from 'jquery';
	import _ from 'underscore';

	export default{
		name: 'deadline-agreement',
		data(){
			return {
				title: 'Trip Requirements',
				deadlineAgree: false,
			}
		},
		computed:{
            requirementsOrdered() {
                return _.sortBy(this.requirements, 'due_at');
            },
			deadlines(){
				return this.$parent.deadlines || [];
			},
			requirements(){
				return this.$parent.requirements;
			},
			costs(){
				return this.$parent.tripCosts || [];
			},
			selectedOptionalCosts(){
				return [this.$parent.selectedOptions] || [];
			}
		},
		methods:{
			toDate(date){
				return moment(date).format('LL');
			}
		},
		watch:{
			deadlineAgree(val, oldVal) {
                this.$emit('step-completion', val);
            }
		},
		activated(){
			$('html, body').animate({scrollTop : 0},300);
		}

	}
</script>

<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Travel Requirements</h4>
			<p>The following items must be completed in order to travel. Your trip representative will contact you soon regarding these necessary documents. These are NOT required to register or to begin fundraising.</p>
			<div class="list-group">
				<div class="list-group-item" v-for="requirement in requirementsOrdered">
					<h4 class="list-group-item-heading">
						{{requirement.name}} <br />
						<small>{{requirement.short_desc}}</small>
					</h4>
					<p class="list-group-item-text">
						This {{requirement.enforced ? 'must' : 'should'}} be completed by {{ toDate(requirement.due_at) }}.
					</p>
				</div>
			</div>
			<hr>
			<template v-if="deadlines.length">
				<h4>Other Deadlines</h4>
				<div class="list-group">
					<div class="list-group-item" v-for="deadline in deadlines">
						<h4 class="list-group-item-heading">
							{{deadline.name}}
						</h4>
						<p class="list-group-item-text">
							This {{deadline.enforced ? 'must' : 'should'}} be completed by {{ toDate(deadline.date) }}.
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
				title: 'Requirements',
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
			$('html, body').animate({scrollTop : 200},300);
		}

	}
</script>

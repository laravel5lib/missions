<template>
	<div class="row">
		<div class="col-sm-12" style="max-height: 500px;overflow-y: auto;">
			<h4>Requirements</h4>
			<div class="list-group">
				<a href="#" class="list-group-item" v-for="requirement in requirements">
					<h4 class="list-group-item-heading">
						{{requirement.item}}
					</h4>
					<p class="list-group-item-text">
						This {{requirement.enforced ? 'must' : 'should'}} be completed by {{ toDate(requirement.date) }}.
					</p>
				</a>
			</div>
			<hr>
			<h4>Cost Deadlines</h4>
			<div class="panel panel-default" v-for="cost in costs.static">
				<!-- Default panel contents -->
				<div class="panel-heading">
					{{cost.name}}
					<span class="pull-right">{{cost.amount | currency}}</span>
				</div>
				<div class="panel-body">
					<p>This cost is applied to registrants after {{ toDate(cost.activate_at) }}</p>
					<div class="list-group">
						<a href="#" class="list-group-item" v-for="payment in cost.payments.data">
							<h4 class="list-group-item-heading">
								Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
							</h4>
							<p class="list-group-item-text">
								The amount of <b>{{payment.amount_owed|currency}}</b>, {{payment.percent_owed}}&percnt; of the total amount, is due.
								If this amount is not received by deadline, the next is applied.
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="panel panel-default" v-for="cost in costs.incremental">
				<!-- Default panel contents -->
				<div class="panel-heading">
					{{cost.name}}
					<span class="pull-right">{{cost.amount | currency}}</span>
				</div>
				<div class="panel-body">
					<p>This cost is applied to registrants after {{ toDate(cost.activate_at) }}</p>
					<div class="list-group">
						<a href="#" class="list-group-item" v-for="payment in cost.payments.data">
							<h4 class="list-group-item-heading">
								Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
							</h4>
							<p class="list-group-item-text">
								The amount of <b>{{payment.amount_owed|currency}}</b>, {{payment.percent_owed}}&percnt; of the total amount is due.
								If this amount is not received by the deadline, the next is applied.
							</p>
						</a>
					</div>
				</div>
			</div>
			<div class="panel panel-default" v-for="cost in selectedOptionalCosts">
				<!-- Default panel contents -->
				<div class="panel-heading">
					{{cost.name}}
					<span class="pull-right">{{cost.amount | currency}}</span>
				</div>
				<div class="panel-body">
					<p>This cost is applied to registrants after {{ toDate(cost.activate_at) }}</p>
					<div class="list-group">
						<a href="#" class="list-group-item" v-for="payment in cost.payments.data">
							<h4 class="list-group-item-heading">
								Deadline: {{ payment.upfront ? 'Immediately' : toDate(payment.due_at) }}
							</h4>
							<p class="list-group-item-text">
								The amount of <b>{{payment.amount_owed|currency}}</b>, {{payment.percent_owed}}&percnt; of the total amount is due.
								If this amount is not received by the deadline, the next is applied.
							</p>
						</a>
					</div>
				</div>
			</div>
			<hr>
			<h4>Other Deadlines</h4>
			<div class="list-group">
				<a href="#" class="list-group-item" v-for="deadline in deadlines">
					<h4 class="list-group-item-heading">
						{{deadline.name}}
					</h4>
					<p class="list-group-item-text">
						This {{deadline.enforced ? 'must' : 'should'}} be completed by {{ toDate(deadline.date) }}.
					</p>
				</a>
			</div>

		</div>
		<div class="col-sm-12">
			<div class="checkbox">
				<label>
					<input type="checkbox" v-model="deadlineAgree">
					I have read and agree to the Deadlines listed.
				</label>
			</div>
		</div>
	</div>
</template>
<script type="text/javascript">
	export default{
		name: 'deadline-agreement',
		data(){
			return {
				title: 'Deadline Agreement',
				deadlineAgree: false,
				deadlines:[],
				costs: [],
				selectedOptionalCosts: [],

			}
		},
		computed:{
			deadlines(){
				return this.$parent.deadlines;
			},
			requirements(){
				return this.$parent.requirements;
			},
			costs(){
				return this.$parent.tripCosts;
			},
			selectedOptionalCosts(){
				return this.$parent.selectedOptions;
			}
		},
		methods:{
			toDate(date){
				return moment(date).format('LL');
			}
		},
		watch:{
			'deadlineAgree'(val, oldVal) {
				this.$dispatch('deadline-agree', val)
			}
		}

	}
</script>

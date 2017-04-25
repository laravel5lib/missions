<template>
	<div>
		<validator name="TravelActivity">
			<form id="TravelActivityForm" novalidate>
				<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: activity.name, client: 'name' }">
					<label for="">Name</label>
					<input type="text" class="form-control" v-model="activity.name" v-validate:name="['required']" v-if="editMode">
					<p v-else>{{ activity.name | uppercase }}</p>
				</div>
				<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: activity.description, client: 'description' }">
					<label for="">Description</label>
					<input type="text" class="form-control" v-model="activity.description" v-validate:description="['required']" v-if="editMode">
					<p v-else>{{ activity.description | uppercase }}</p>
				</div>
				<div class="form-group" v-error-handler="{ value: activity.occured_at, client: 'occured' }">
					<label for="">Date &amp; Time</label>
					<date-picker :model.sync="activity.occurred_at|moment 'YYYY-MM-DD HH:mm:ss'" v-if="editMode"></date-picker>
					<p v-else>{{ activity.occurred_at|moment 'LLL' }}</p>
					<input type="datetime" class="form-control hidden" v-model="activity.occurred_at | moment 'LLLL'"
					       id="occurred_at" v-validate:occured="['required']">
				</div>
				<template v-if="isUpdate && editMode">
					<button class="btn btn-xs btn-primary" type="button" @click="update">Update Arrival</button>
				</template>
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'travel-activity',
        mixins: [errorHandler],
	    props: {
            activity: {
                type: Object,
	            default: {
                    name: '',
                    description: '',
                    occurred_at: '',
	            }
            },
            editMode: {
                type: Boolean,
                default: true
            }
	    },
        data(){
            return {
                validatorHandle: 'TravelActivity',


            }
        },
        computed: {
            'isUpdate': function() {
                return this && this.activity.hasOwnProperty('id') && _.isString(this.activity.id);
            },
            isReady(){
			    return this && _.isObject(this.activity);
			}
        },
        methods: {
            update(){
				this.$http.put('activities/' + this.activity.id, this.activity).then(function (response) {
					this.$emit('showSuccess', 'Itinerary Arrival Date/Time Updated');
                });
            }
        },
        ready(){
			this.attemptSubmit = true;
        }
    }
</script>
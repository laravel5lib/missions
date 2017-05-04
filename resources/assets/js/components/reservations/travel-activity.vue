<template>
	<div>
		<validator name="TravelActivity" v-if="activity">
			<form id="TravelActivityForm" novalidate>
				<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: activity.name, client: 'name' }">
					<label for="">Name</label>
					<template v-if="editMode">
						<input type="text" class="form-control" v-model="activity.name" v-validate:name="['required']">
					</template>
					<p v-else>{{ activity.name | uppercase }}</p>
				</div>
				<div v-if="isAdminRoute" class="form-group">
					<label for="">Description</label>
					<template v-if="editMode">
						<input type="text" class="form-control" v-model="activity.description">
					</template>
					<p v-else>{{ activity.description | uppercase }}</p>
				</div>
				<div v-if="!transportDomestic" class="form-group" v-error-handler="{ value: activity.description, client: 'description', messages: { req: 'Please provide an explanation.'} }">
					<label for="">Please explain why you don't need Missions.Me to arrange transportation.</label>
					<template v-if="editMode">
						<textarea type="text" class="form-control" v-model="activity.description" v-validate:description="['required']"></textarea>
					</template>
					<p v-else>{{ activity.description | uppercase }}</p>
				</div>
				<div class="form-group" v-if="transportDomestic" v-error-handler="{ value: activity.occurred_at, client: 'occurred', messages: {req: 'Please set a date and time', datetime: 'Please set a date and time'} }">
					<label for="" v-text="LABELS.dateTime"></label>
					<date-picker :model.sync="activity.occurred_at | moment 'YYYY-MM-DD HH:mm:ss'" v-if="editMode"></date-picker>
					<p v-else>{{ activity.occurred_at | moment 'LLLL' }}</p>
					<input type="text" class="form-control hidden" v-model="activity.occurred_at"
					       id="occurred_at" v-validate:occurred="['required', 'datetime']">
				</div>
				<!--<template v-if="isUpdate && editMode">
					<button class="btn btn-xs btn-primary" type="button" @click="update">Update Arrival</button>
				</template>-->
			</form>
		</validator>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import errorHandler from'../error-handler.mixin';
    export default{
        name: 'travel-activity',
        mixins: [errorHandler],
	    props: {
            activity: {
                type: Object,
	            default: {
                    name: '',
                    activity_type_id: '',
                    description: '',
                    occurred_at: '',
	            }
            },
            editMode: {
                type: Boolean,
                default: true
            },
		    transportDomestic: {
                type: Boolean
		    },
		    activityTypes: {
                type: Array
		    },
		    activityType: {
                type: String
		    }
	    },
        data(){
            return {
                validatorHandle: 'TravelActivity',

				LABELS: {
                    dateTime: 'Date &amp; Time'
				}
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
	    events: {
            'validate-itinerary'() {
                this.resetErrors();
            }
	    },
        methods: {
            update(){
				this.$http.put('activities/' + this.activity.id, this.activity).then(function (response) {
					this.$emit('showSuccess', 'Itinerary Arrival Date/Time Updated');
                },
                    function (response) {
                        console.log(response);
                    });
            }
        },
        ready(){
	        let activityType = _.findWhere(this.activityTypes, { id: this.activityType});
	        switch (activityType.name) {
		        case 'arrival':
		            this.LABELS.dateTime = 'Arriving at Date & Time';
		            break;
		        case 'departure':
                    this.LABELS.dateTime = 'Departing at Date & Time';
                    break;
		        case 'connection':
                    this.LABELS.dateTime = 'Connection Departs at Date & Time';
                    break;
	        }
        }
    }
</script>
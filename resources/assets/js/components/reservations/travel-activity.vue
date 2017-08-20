<template>
	<div>
		<div v-if="activity">
			<form id="TravelActivityForm" novalidate>
				<div v-if="isAdminRoute" class="form-group" v-error-handler="{ value: thisActivity.name, client: 'name' }">
					<label for="">Name</label>
					<template v-if="editMode">
						<input type="text" class="form-control" v-model="thisActivity.name" name="name" v-validate="'required'">
					</template>
					<p v-else>{{ thisActivity.name.toUpperCase() }}</p>
				</div>
				<div v-if="isAdminRoute" class="form-group">
					<label for="">Description</label>
					<template v-if="editMode">
						<input type="text" class="form-control" v-model="thisActivity.description">
					</template>
					<p v-else>{{ thisActivity.description.toUpperCase() }}</p>
				</div>
				<div v-if="!transportDomestic" class="form-group" v-error-handler="{ value: thisActivity.description, client: 'description', messages: { req: 'Please provide an explanation.'} }">
					<label for="">Please explain why you don't need Missions.Me to arrange transportation.</label>
					<template v-if="editMode">
						<textarea type="text" class="form-control" v-model="thisActivity.description" name="description" v-validate="'required'"></textarea>
					</template>
					<p v-else>{{ thisActivity.description.toUpperCase() }}</p>
				</div>
				<div class="form-group" v-if="transportDomestic" v-error-handler="{ value: thisActivity.occurred_at, client: 'occurred', messages: {req: 'Please set a date and time', datetime: 'Please set a date and time'} }">
					<label for="" v-text="LABELS.dateTime"></label>
					<date-picker :model="thisActivity.occurred_at | moment('YYYY-MM-DD HH:mm:ss', false, true)" v-if="editMode"
					             v-validate="'required'" data-vv-name="occurred" data-vv-value-path="model"></date-picker>
					<p v-else>{{ thisActivity.occurred_at | moment('LLLL', false, true) }}</p>
					<!--<datetime-input v-model="thisActivity.occurred_at" no-local validation="required"></datetime-input>-->
					<!--<input type="text" class="form-control hidden" v-model="thisActivity.occurred_at | moment('YYYY-MM-DD HH:mm:ss', false, true)"
					       id="occurred_at" name="occurred" v-validate="'required'">-->
				</div>
				<!--<template v-if="isUpdate && editMode">
					<button class="btn btn-xs btn-primary" type="button" @click="update">Update Arrival</button>
				</template>-->
			</form>
		</div>
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
                type: Object
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
//                validatorHandle: 'TravelActivity',
				thisActivity: {
                    name: '',
                    activity_type_id: '',
                    description: '',
                    occurred_at: '',
                },
				LABELS: {
                    dateTime: 'Date &amp; Time'
				}
            }
        },
        computed: {
            isUpdate() {
                return this && this.activity.hasOwnProperty('id') && _.isString(this.activity.id);
            },
            isReady() {
			    return this && _.isObject(this.activity);
			}
        },
	    watch: {
            activityType() {
                this.handleLabels();
            },
	    },
	    events: {
            'validate-itinerary'() {

            }
	    },
        methods: {
            update(){
				this.$http.put('activities/' + this.activity.id, this.thisActivity).then((response) => {
				    this.emit('updated', this.thisActivity)
					this.$emit('showSuccess', 'Itinerary Arrival Date/Time Updated');
                }).catch(this.$root.handleApiError);
            },
	        handleLabels(){
                let activityType = _.findWhere(this.activityTypes, { id: this.activityType});
                if (activityType) {
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
	        },
        },
        mounted(){
            if (_.isObject(this.activity)) {
                this.thisActivity = this.activity;
            }
	        this.handleLabels();
        }
    }
</script>
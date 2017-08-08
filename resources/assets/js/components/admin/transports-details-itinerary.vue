<template>
	<div>
		<aside :show.sync="showActivityFilters" placement="left" header="Activity Filters" :width="375">
			<hr class="divider inv sm">
			<form class="col-sm-12">
				<div class="form-group">
					<label>Activity Type</label>
					<select class="form-control" v-model="activityFilters.type">
						<option value="">Any Type</option>
						<option :value="type.id" v-for="type in UTILITIES.activityTypes" v-text="type.name|capitalize"></option>
					</select>
				</div>
			</form>
		</aside>
		<form class="form-inline">
			<div class="input-group input-group-sm">
				<input type="text" class="form-control" v-model="activityFilters.search" debounce="300" placeholder="Search">
				<span class="input-group-addon"><i class="fa fa-search"></i></span>
			</div>
			<button class="btn btn-default btn-sm" @click="showActivityFilters = true;">Filters</button>
			<date-picker :has-error="" :model.sync="activityFilters.starts|moment('YYYY-MM-DD', false, true)" type="date" placeholder="Start DateTime" input-sm></date-picker>
			<date-picker :has-error="" :model.sync="activityFilters.ends|moment('YYYY-MM-DD', false, true)" type="date" placeholder="End DateTime" input-sm></date-picker>

			<button type="button" class="btn btn-primary btn-sm" @click="newActivity();">Add Activity</button>
		</form>
		<hr class="divider">

		<!-- Activities List -->
		<template v-if="activities.length">

			<div class="container">
				<div class="row">
					<div class="timeline-centered">

						<article class="timeline-entry" v-for="activity in activities">

							<div class="timeline-entry-inner">
								<time class="timeline-time" :datetime=" activity.occurred_at ">
									<span>{{ activity.occurred_at | moment 'h:mm A zz' }}</span>
									<span>{{ activity.occurred_at | moment 'ddd, ll' }}</span>
								</time>

								<div class="timeline-icon bg-success" v-if="activity.type.name == 'departure'">
									<i class="fa fa-arrow-right"></i>
								</div>

								<div class="timeline-icon bg-warning" v-if="activity.type.name == 'connection'">
									<i class="fa fa-arrow-arrows"></i>
								</div>

								<div class="timeline-icon bg-danger" v-if="activity.type.name == 'arrival'">
									<i class="fa fa-arrow-left"></i>
								</div>

								<div class="timeline-label">
									<h2>
										<a href="#">{{ activity.name | capitalize }}</a>
										<span class="label label-default" v-text="activity.type.name|capitalize"></span>
										<br />
										<small><i class="fa fa-clock-o"></i> {{ activity.occurred_at|moment 'dddd, MMMM D, YYYY zz' }}</small>
									</h2>

									<p>{{ activity.description }}</p>

									<hr class="divider">

									<p class="text-right">
										<a @click="confirmDeleteActivity(activity)" class="btn btn-xs btn-default-hollow pull-right">
											<i class="fa fa-trash"></i> Delete
										</a>
										<a @click="editActivity(activity)" class="btn btn-xs btn-default-hollow pull-right">
											<i class="fa fa-pencil"></i> Edit
										</a>
									<p>

									<ul class="nav nav-tabs" role="tablist">
										<li role="presentation" class="active">
											<a href="#hubs" aria-controls="hubs" role="tab" data-toggle="tab">
												<i class="fa fa-map-marker"></i> Hubs
											</a>
										</li>
										<li role="presentation">
											<a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">
												<i class="fa fa-sticky-note-o"></i> Notes
											</a>
										</li>
									</ul>

									<!-- Tab panes -->
									<div class="tab-content">
										<div role="tabpanel" class="tab-pane active" id="hubs">
											<div class="text-right">
												<button class="btn btn-sm btn-primary" type="button" @click="newHub(activity)">Add Hub</button>
											</div>
											<hr class="divider inv">
											<div class="list-group">
												<div class="list-group-item" v-for="hub in activity.hubs.data">
													<h5 class="list-group-item-heading">
														{{hub.name | capitalize}} <span v-if="hub.call_sign">({{hub.call_sign}})</span>

														<a @click="confirmDeleteHub(hub)" class="btn btn-xs btn-default-hollow pull-right">
															<i class="fa fa-trash"></i> Delete
														</a>

														<a @click="editHub(hub)" class="btn btn-xs btn-default-hollow pull-right">
															<i class="fa fa-pencil"></i> Edit
														</a>
													</h5>
													<p class="list-group-item-text">
														{{hub.address}}
														{{hub.city}} {{hub.state}} {{hub.zip}}
														{{hub.country_code | uppercase}}
													</p>
												</div>
											</div>
										</div>

										<div role="tabpanel" class="tab-pane" id="notes">
										</div>
									</div>

								</div>
							</div>

						</article>
						<article class="timeline-entry begin">

							<div class="timeline-entry-inner">

								<div class="timeline-icon">
									<i class="fa fa-ban"></i>
								</div>

							</div>

						</article>

					</div>
				</div>
			</div>
				<div class="col-xs-12 text-center">
					<pagination :pagination.sync="activitiesPagination" :callback="getActivities"></pagination>
				</div>
		</template>
		<!-- Activities List Empty State -->
		<template v-else>
			<hr class="divider inv">
			<p class="text-center text-muted"><em>No activities</em></p>
		</template>

		<modal :title="editMode?'Edit Activity':'Create Activity'" :ok-text="editMode?'Update':'Create'" :callback="saveActivity" :show.sync="activityModal">
			<div slot="modal-body" class="modal-body" v-if="selectedActivity">
				<div class="form-group">
					<label>Activity Type</label>
					<select class="form-control" v-model="selectedActivity.activity_type_id">
						<option value="">Any Type</option>
						<option :value="type.id" v-for="type in UTILITIES.activityTypes" v-text="type.name|capitalize"></option>
					</select>
				</div>
				<travel-activity ref="activity" :activity="selectedActivity" :activity-types="UTILITIES.activityTypes" :activity-type="selectedActivity.activity_type_id" transport-domestic></travel-activity>
			</div>
		</modal>
		<modal :title="editMode?'Edit Hub':'Create Hub'" :ok-text="editMode?'Update':'Create'" :callback="saveHub" :show.sync="hubModal">
			<div slot="modal-body" class="modal-body" v-if="selectedHub">
				<travel-hub ref="hub" :hub.sync="selectedHub" :activity-types="activityTypes"></travel-hub>
			</div>
		</modal>
		<modal title="Delete Hub" small ok-text="Delete" :callback="deleteHub" :show.sync="showHubDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedHub">
					Are you sure you want to delete {{selectedHub.name}} ?
				</p>
			</div>
		</modal>
		<modal title="Delete Activity" small ok-text="Delete" :callback="deleteActivity" :show.sync="showActivityDeleteModal">
			<div slot="modal-body" class="modal-body">
				<p v-if="selectedActivity">
					Are you sure you want to delete {{selectedActivity.name}} ?
				</p>
			</div>
		</modal>

	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import errorHandler from '../error-handler.mixin';
    import utilities from '../utilities.mixin';
    import travelActivity from '../reservations/travel-activity.vue';
    import travelHub from '../reservations/travel-hub.vue';
    export default{
        name: 'transports-details-itinerary',
        mixins: [/*errorHandler,*/ utilities],
	    components: {vSelect, travelActivity, travelHub},
	    props: ['transport', 'campaignId'],
        data(){
            return {
                showActivityFilters: false,
                showActivityDeleteModal: false,
                showHubDeleteModal: false,
                activities: [],
                activitiesPagination: { current_page: 1 },
	            activityFilters: {
                    type: '',
                    search: '',
		            starts: null,
		            ends: null,
	            },
	            selectedActivity: null,
	            selectedHub: null,
                activityModal: false,
                hubModal: false,
                editMode: false,

	            ActivityResource: this.$resource('activities{/activity}'),
	            HubResource: this.$resource('hubs{/hub}')
            }
        },
	    watch: {
            activityFilters: {
                handler(val) {
                    this.getActivities();
                },
	            deep: true
            },
		    hubModal(val){
                if (!val)
                    this.selectedHub = null;

		    },
		    activityModal(val){
                if (!val)
                    this.selectedActivity = null;
		    },
	    },
        methods: {
            ActivityFactory() {
                return {
                    name: '',
                    activity_type_id: '',
                    description: '',
                    occurred_at: '',
                };
            },
            HubFactory() {
                return {
                    name: '',
                    address: '',
                    call_sign: '',
                    city: '',
                    state: '',
                    zip: '',
                    country_code: '',
                }
            },
            getActivities() {
                let params = _.extend({
	                page: this.activitiesPagination.current_page,
                    include: 'hubs'
                }, this.activityFilters);

                return this.ActivityResource.get(params).then(function (response) {
                    this.activitiesPagination = response.body.meta.pagination;
                    return this.activities = response.body.data;
                }, this.handleApiError);
            },
            newActivity() {
				this.selectedActivity = _.extend({}, this.ActivityFactory());
				this.activityModal = true;
            },
            newHub(activity) {
				this.selectedHub = _.extend({ activity_id: activity.id }, this.HubFactory());
				this.hubModal = true;
            },
            editActivity(activity) {
				this.selectedActivity = activity;
				this.selectedActivity.activity_type_id = activity.type.id;
				this.editMode = true;
				this.activityModal = true;
            },
            editHub(hub) {
				this.selectedHub = hub;
                this.editMode = true;
                this.hubModal = true;
            },
	        confirmDeleteHub(hub){
                this.selectedHub = hub;
                this.showHubDeleteModal = true;
            },
	        confirmDeleteActivity(activity){
	            this.selectedActivity = activity;
	            this.showActivityDeleteModal = true;
	        },
	        saveHub(){
                let data = _.extend({

                }, this.selectedHub);
                let promise;

                // trigger validation styles
                this.$broadcast('validate-itinerary');

	            if (data.id) {
	                promise = this.HubResource.update({ hub: data.id}, data).then(function (response) {
                        this.editMode = false;
                        this.getActivities();
                    }, this.handleApiError);
	            } else {
                    data.campaign_id = null;
                    promise = this.HubResource.save(data).then(function (response) {
                        this.getActivities();

                    }, this.handleApiError);
	            }

	            promise.then(function () {
		            this.hubModal = false;
                });
	        },
	        saveActivity(){
                let data = _.extend({
                    participant_type: 'campaign',
	                participant_id: this.campaignId,
                    transport_id: this.transport.id
                }, this.selectedActivity);
                let promise;

                // trigger validation styles
                this.$broadcast('validate-itinerary');

                if (data.id) {
                    promise = this.ActivityResource.update({ activity: data.id}, data).then(function (response) {
                        this.editMode = false;
                        this.getActivities();
                    }, this.handleApiError);
                } else {
                    data.campaign_id = null;
                    promise = this.ActivityResource.save(data).then(function (response) {
                        this.getActivities();

                    }, this.handleApiError);
                }

                promise.then(function () {
                    this.activityModal = false;
                });

            },
	        deleteHub(){
                this.HubResource.delete({ activity: this.selectedHub.id }).then(function () {
                    this.getActivities();
                    this.showHubDeleteModal = false;
                }, this.handleApiError);
	        },
	        deleteActivity(){
	            this.ActivityResource.delete({ activity: this.selectedActivity.id }).then(function () {
	                this.getActivities();
                    this.showActivityDeleteModal = false;
                }, this.handleApiError);
	        },
        },
        mounted(){
            this.getActivityTypes();
			this.getActivities();
        }
    }
</script>
<style scoped>

img
{
	vertical-align: middle;
}
.img-responsive
{
	display: block;
	height: auto;
	max-width: 100%;
}
.img-rounded
{
	border-radius: 3px;
}
.img-thumbnail
{
	background-color: #fff;
	border: 1px solid #ededf0;
	border-radius: 3px;
	display: inline-block;
	height: auto;
	line-height: 1.428571429;
	max-width: 100%;
	moz-transition: all .2s ease-in-out;
	o-transition: all .2s ease-in-out;
	padding: 2px;
	transition: all .2s ease-in-out;
	webkit-transition: all .2s ease-in-out;
}
.img-circle
{
	border-radius: 50%;
}
.timeline-centered {
    position: relative;
    margin-bottom: 30px;
}

    .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
    }

    .timeline-centered:after {
        clear: both;
    }

    .timeline-centered:before, .timeline-centered:after {
        content: " ";
        display: table;
    }

    .timeline-centered:after {
        clear: both;
    }

    .timeline-centered:before {
        content: '';
        position: absolute;
        display: block;
        width: 4px;
        background: #666;
        left: 20%;
        top: 20px;
        bottom: 20px;
        margin-left: -4px;
    }

    .timeline-centered .timeline-entry {
        position: relative;
        width: 80%;
        float: right;
        margin-bottom: 70px;
        clear: both;
    }

        .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry:after {
            clear: both;
        }

        .timeline-centered .timeline-entry:before, .timeline-centered .timeline-entry:after {
            content: " ";
            display: table;
        }

        .timeline-centered .timeline-entry:after {
            clear: both;
        }

        .timeline-centered .timeline-entry.begin {
            margin-bottom: 0;
        }

        .timeline-centered .timeline-entry.left-aligned {
            float: left;
        }

            .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner {
                margin-left: 0;
                margin-right: -18px;
            }

                .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-time {
                    left: auto;
                    right: -100px;
                    text-align: left;
                }

                .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-icon {
                    float: right;
                }

                .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label {
                    margin-left: 0;
                    margin-right: 70px;
                }

                    .timeline-centered .timeline-entry.left-aligned .timeline-entry-inner .timeline-label:after {
                        left: auto;
                        right: 0;
                        margin-left: 0;
                        margin-right: -9px;
                        -moz-transform: rotate(180deg);
                        -o-transform: rotate(180deg);
                        -webkit-transform: rotate(180deg);
                        -ms-transform: rotate(180deg);
                        transform: rotate(180deg);
                    }

        .timeline-centered .timeline-entry .timeline-entry-inner {
            position: relative;
            margin-left: -22px;
        }

            .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
                content: " ";
                display: table;
            }

            .timeline-centered .timeline-entry .timeline-entry-inner:after {
                clear: both;
            }

            .timeline-centered .timeline-entry .timeline-entry-inner:before, .timeline-centered .timeline-entry .timeline-entry-inner:after {
                content: " ";
                display: table;
            }

            .timeline-centered .timeline-entry .timeline-entry-inner:after {
                clear: both;
            }

            .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time {
                position: absolute;
                left: -100px;
                text-align: right;
                padding: 10px;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
            }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span {
                    display: block;
                }

                    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:first-child {
                        font-size: 15px;
                        font-weight: bold;
                    }

                    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-time > span:last-child {
                        font-size: 12px;
                    }

            .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon {
                background: #fff;
                color: #737881;
                display: block;
                width: 40px;
                height: 40px;
                -webkit-background-clip: padding-box;
                -moz-background-clip: padding;
                background-clip: padding-box;
                -webkit-border-radius: 20px;
                -moz-border-radius: 20px;
                border-radius: 20px;
                text-align: center;
                -moz-box-shadow: 0 0 0 5px #f5f5f6;
                -webkit-box-shadow: 0 0 0 5px #f5f5f6;
                box-shadow: 0 0 0 5px #f5f5f6;
                line-height: 40px;
                font-size: 15px;
                float: left;
            }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-primary {
                    background-color: #303641;
                    color: #fff;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-secondary {
                    background-color: #ee4749;
                    color: #fff;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-success {
                    background-color: #00a651;
                    color: #fff;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-info {
                    background-color: #21a9e1;
                    color: #fff;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-warning {
                    background-color: #fad839;
                    color: #fff;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-icon.bg-danger {
                    background-color: #cc2424;
                    color: #fff;
                }

            .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label {
                position: relative;
                background: #fff;
                padding: 1.7em;
                margin-left: 70px;
                -webkit-background-clip: padding-box;
                -moz-background-clip: padding;
                background-clip: padding-box;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label:after {
                    content: '';
                    display: block;
                    position: absolute;
                    width: 0;
                    height: 0;
                    border-style: solid;
                    border-width: 9px 9px 9px 0;
                    border-color: transparent #f5f5f6 transparent transparent;
                    left: 0;
                    top: 10px;
                    margin-left: -9px;
                }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2, .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p {
                    color: #737881;
                    font-family: "Noto Sans",sans-serif;
                    font-size: 12px;
                    margin: 0;
                    line-height: 1.428571429;
                }

                    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label p + p {
                        margin-top: 15px;
                    }

                .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 {
                    font-size: 16px;
                    margin-bottom: 10px;
                }

                    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 a {
                        color: #303641;
                    }

                    .timeline-centered .timeline-entry .timeline-entry-inner .timeline-label h2 span {
                        -webkit-opacity: .6;
                        -moz-opacity: .6;
                        opacity: .6;
                        -ms-filter: alpha(opacity=60);
                        filter: alpha(opacity=60);
                    }
</style>
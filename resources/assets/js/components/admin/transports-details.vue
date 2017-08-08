<template>
	<div>
		<div class="panel panel-default" v-if="transport">
			<div class="panel-heading">
				<h3>
					<a :href="'/admin/campaigns/' + campaignId + '/transports'" class="btn btn-xs btn-primary pull-right"><i class="fa fa-chevron-left"></i> Back to Transports</a>
					{{ transport.name }} <small>&middot; {{ transport.vessel_no }}</small>
					<br />
					<small><i class="fa" :class="{ 'fa-bus': transport.type === 'bus', 'fa-plane': transport.type === 'flight', 'fa-car': transport.type === 'vehicle', 'fa-train': transport.type === 'train'}"></i>
					{{ transport.type | capitalize }}
					<span class="label label-info" v-text="transport.domestic ? 'Domestic' : 'International'"></span>
					<span class="label label-primary" v-text="transport.designation | capitalize"></span>
					</small>
				</h3>
				<p>
					<strong>{{ transport.depart_at | moment 'MMM dd, hh:mm a' false true }}</strong>
					{{ transport.departureHub.data.call_sign }}
					<i class="fa fa-long-arrow-right" aria-hidden="true"></i>
					<strong>{{ transport.arrive_at | moment 'hh:mm a' false true }}</strong>
					{{ transport.arrivalHub.data.call_sign }}
				</p>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4">
                        <label>Capacity</label>
                        <h4>{{transport.capacity}}</h4>
                    </div>
                    <div class="col-sm-4">
                        <label>Total Passengers</label>
						<h4 :class="{ 'text-success': passengersCount > 0}">
							{{passengersCount}}
						</h4>
					</div>
					<div class="col-sm-4">
                        <label>Seats Left</label>
						<h4 :class="{ 'text-success': seatsLeft > 0, 'text-danger': seatsLeft < 0}">
							{{seatsLeft}}
						</h4>
					</div>
				</div>
			</div>
		</div>
		<tabs v-if="transport">
			<tab header="Passengers">
				<transports-details-passengers ref="passengers" :transport="transport" :campaign-id="campaignId"></transports-details-passengers>
			</tab>
			<tab header="Details">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Passengers by Region</h4>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(key, value) in transport.passengers.regions">
                                    {{key}} <span class="badge">{{value}}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Passengers by Designation</h4>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(key, value) in transport.passengers.designations">
                                    {{key}} <span class="badge">{{value}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Passengers by Group</h4>
                            </div>
                            <ul class="list-group">
                                <li class="list-group-item" v-for="(key, value) in transport.passengers.groups">
                                    {{key}} <span class="badge">{{value}}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <hr class="divider inv">
                        <h4 class="text-left">
                            Total Passengers: <span class="text-primary">{{ transport.passengers.total }}</span>
                            <button class="pull-right btn btn-xs btn-primary" @click="getTransport()">
                                Refresh Passenger Count <i class="fa fa-refresh"></i>
                            </button>
                        </h4>
                    </div>
                </div>
				<div class="row">
                    <hr class="divider">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4>Departure</h4>
                                <small><i class="fa fa-clock-o"></i> {{ transport.depart_at | moment 'h:mm A zz' false true }} | {{ transport.depart_at|moment 'dddd, MMMM D, YYYY zz' false true }}</small>
                            </div>
                            <div class="panel-body">
                                <p>
                                    {{transport.departureHub.data.name | capitalize}} <span v-if="transport.departureHub.data.call_sign">({{transport.departureHub.data.call_sign}})</span>
                                    <span v-if="transport.departureHub.data.address">{{transport.departureHub.data.address}}</span><br>
                                    <span v-if="transport.departureHub.data.city">{{transport.departureHub.data.city}}</span> <span v-if="transport.departureHub.data.state">{{transport.departureHub.data.state}}</span> <span v-if="transport.departureHub.data.zip">{{transport.departureHub.data.zip}}</span><br>
                                    <span v-if="transport.departureHub.data.country_code">{{transport.departureHub.data.country_code | uppercase}}</span>
                                </p>
                            </div>
                        </div>
                    </div>
					<div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
						        <h4>Arrival</h4>
                                <small><i class="fa fa-clock-o"></i> {{ transport.arrive_at | moment 'h:mm A zz' false true }} | {{ transport.arrive_at|moment 'dddd, MMMM D, YYYY zz' false true }}</small>
                            </div>
                            <div class="panel-body">
                                <p>
                                    {{transport.arrivalHub.data.name | capitalize}} <span v-if="transport.arrivalHub.data.call_sign">({{transport.arrivalHub.data.call_sign}})</span>
                                    <span v-if="transport.arrivalHub.data.address">{{transport.arrivalHub.data.address}}</span><br>
                                    <span v-if="transport.arrivalHub.data.city">{{transport.arrivalHub.data.city}}</span> <span v-if="transport.arrivalHub.data.state">{{transport.arrivalHub.data.state}}</span> <span v-if="transport.arrivalHub.data.zip">{{transport.arrivalHub.data.zip}}</span><br>
                                    <span v-if="transport.arrivalHub.data.country_code">{{transport.arrivalHub.data.country_code | uppercase}}</span>
                                </p>
                            </div>
                        </div>
					</div>
				</div>
			</tab>
			<tab header="Notes">
				<notes type="campaign_transports"
				       :id="transport.id"
				       :user_id="$root.userId"
				       :per_page="10"
				       :can-modify="1">
				</notes>
			</tab>
		</tabs>

	</div>
</template>
<script type="text/javascript">
    import notes from '../notes.vue';
    import transportsDetailsPassengers from './transports-details-passengers.vue';
    export default{
        name: 'transports-details',
        components: {transportsDetailsPassengers, notes},
        props: {
            campaignId: {
                type: String,
                required: true
            },
            transportId: {
                type: String,
                required: true
            },
        },
        data(){
            return {
                transport: null,
                TransportsResource: this.$resource('campaigns{/campaign}/transports{/transport}', { campaign: this.campaignId}),
	            passengersCount: 0,
            }
        },
		computed: {
            'seatsLeft': function() {
				return this.transport.capacity - this.passengersCount;
			}
		},
        methods: {
            getTransport() {
                let params = {
                    transport: this.transportId,
                    include: 'departureHub,arrivalHub'
                };
                this.TransportsResource.get(params).then(function (response) {
	                this.transport = response.body.data;
                }, this.$root.handleApiError);
            }
        },
        mounted(){
			this.getTransport();
        },
        events: {
            'updatePassengersCount': function (passengers) {
                this.passengersCount = passengers;
            }
        }
    }
</script>
<style></style>

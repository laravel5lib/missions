<template>
	<div>
		<div class="panel panel-default" v-if="transport">
			<div class="panel-heading">
				<h3>
					<a :href="'/admin/campaigns/' + campaignId + '/transports'" class="btn btn-xs btn-primary pull-right"><i class="fa fa-chevron-left"></i> Back to Transports</a>
					{{ transport.name }}
					<br />
					<small><i class="fa" :class="{ 'fa-bus': transport.type === 'bus', 'fa-plane': transport.type === 'flight', 'fa-car': transport.type === 'vehicle', 'fa-train': transport.type === 'train'}"></i>
					{{ transport.type | capitalize }}
					<span class="label label-default" v-text="transport.domestic ? 'Domestic' : 'International'"></span>
					</small>
				</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-sm-4"><label>Vessel No.</label> {{transport.vessel_no}}</div>
					<div class="col-sm-4"><label>Call Sign</label> {{transport.call_sign}}</div>
					<div class="col-sm-4"><label>Capacity</label> {{transport.capacity}}</div>
				</div>
			</div>
		</div>
		<tabs v-if="transport">
			<tab header="Details">
				<div class="row">
					<div class="col-sm-6">
						<h4>Arrival</h4>
						<small><i class="fa fa-clock-o"></i> {{ transport.arrive_at | moment 'h:mm A zz' }} | {{ transport.arrive_at|moment 'dddd, MMMM D, YYYY zz' }}</small>

						<p class="">
							{{transport.arrivalHub.data.name | capitalize}} <span v-if="transport.arrivalHub.data.call_sign">({{transport.arrivalHub.data.call_sign}})</span>
							<span v-if="transport.arrivalHub.data.address">{{transport.arrivalHub.data.address}}</span><br>
							<span v-if="transport.arrivalHub.data.city">{{transport.arrivalHub.data.city}}</span> <span v-if="transport.arrivalHub.data.state">{{transport.arrivalHub.data.state}}</span> <span v-if="transport.arrivalHub.data.zip">{{transport.arrivalHub.data.zip}}</span><br>
							<span v-if="transport.arrivalHub.data.country_code">{{transport.arrivalHub.data.country_code | uppercase}}</span>
						</p>
					</div>
					<div class="col-sm-6">
						<h4>Departure</h4>
						<small><i class="fa fa-clock-o"></i> {{ transport.depart_at | moment 'h:mm A zz' }} | {{ transport.depart_at|moment 'dddd, MMMM D, YYYY zz' }}</small>

						<p class="">
							{{transport.departureHub.data.name | capitalize}} <span v-if="transport.departureHub.data.call_sign">({{transport.departureHub.data.call_sign}})</span>
							<span v-if="transport.departureHub.data.address">{{transport.departureHub.data.address}}</span><br>
							<span v-if="transport.departureHub.data.city">{{transport.departureHub.data.city}}</span> <span v-if="transport.departureHub.data.state">{{transport.departureHub.data.state}}</span> <span v-if="transport.departureHub.data.zip">{{transport.departureHub.data.zip}}</span><br>
							<span v-if="transport.departureHub.data.country_code">{{transport.departureHub.data.country_code | uppercase}}</span>
						</p>
					</div>
				</div>
			</tab>
			<tab header="Passengers">
				<transports-details-passengers v-ref:passengers :transport="transport" :campaign-id="campaignId"></transports-details-passengers>
			</tab>
			<tab header="Notes">
				<!--<notes type="transports"-->
				       <!--:id="transport.id"-->
				       <!--:user_id="userId"-->
				       <!--:per_page="5"-->
				       <!--:can-modify="isAdminRoute ? 1 : 0">-->
				<!--</notes>-->
			</tab>
		</tabs>

	</div>
</template>
<script type="text/javascript">
    import transportsDetailsPassengers from './transports-details-passengers.vue';
    export default{
        name: 'transports-details',
        components: {transportsDetailsPassengers},
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
        ready(){
			this.getTransport();
        }
    }
</script>
<style></style>

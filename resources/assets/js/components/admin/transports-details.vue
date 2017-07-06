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
			<tab header="Itinerary">
				<transports-details-itinerary :transport="transport" :campaign-id="campaignId"></transports-details-itinerary>
			</tab>
			<tab header="Passengers">
				<transports-details-passengers :transport="transport" :campaign-id="campaignId"></transports-details-passengers>
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
<style></style>
<script type="text/javascript">
    import transportsDetailsItinerary from './transports-details-itinerary.vue';
    import transportsDetailsPassengers from './transports-details-passengers.vue';
    export default{
        name: 'transports-details',
        components: {transportsDetailsItinerary, transportsDetailsPassengers},
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
                validatorHandle: 'TransportsDetailsModal',
                transport: null,

                TransportsResource: this.$resource('transports{/transport}'),
	            passengersCount: 0,
            }
        },
        methods: {
            getTransport() {
                this.TransportsResource.get({ transport: this.transportId }).then(function (response) {
	                this.transport = response.body.data;
                });
            }
        },
        ready(){
			this.getTransport();
        }
    }
</script>
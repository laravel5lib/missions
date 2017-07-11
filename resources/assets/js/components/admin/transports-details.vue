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

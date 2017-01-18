<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="panel panel-default" data-aos="fade-up">
            <div class="panel-heading">
				<div class="row">
					<div class="col-xs-8">
						<h5>Trip History</h5>
					</div>
					<div class="col-xs-4 text-right" v-if="isUser()">
						<h5><a class="text-muted" @click="manageModal = true"><i class="fa fa-edit"></i></a></h5>
					</div>
				</div>
            </div><!-- end panel-heading -->
            <div class="panel-body">
                <p style="display:inline-block;margin-bottom:3px;" v-for="accolade in accolades.items">
                    <span class="label label-default">
                        <i class="fa fa-map-marker" style="margin-right:3px;"></i> {{ accolade }}
                    </span>
                </p>
				<p class="text-muted text-center small" v-if="! accolades.items || accolades.items.length < 1"><em>Add trips you've visited or sign up for a trip to get started!</em></p>
            </div><!-- end panel-body -->
        </div><!-- end panel -->

        <modal class="text-center" v-if="isUser()" :show.sync="manageModal" title="Manage Trips" width="800" :callback="updateAccolades">
            <div slot="modal-body" class="modal-body text-center">
				<validator name="AddTrip">
					<form class="for" novalidate>
						<div class="form-group" :class="">
							<label class="control-label">Trips</label>
							<v-select class="form-control" multiple :value.sync="selectedTrips" :options="availableTrips"
									  label="name"></v-select>
							<select hidden="" v-model="selectedCodes" multiple v-validate:code="{ required: true }">
								<option :value="trip" v-for="trip in availableTrips">{{trip.name}}</option>
							</select>
						</div>
					</form>
				</validator>

				<ul class="list-group">
					<li class="list-group-item" v-for="accolade in accolades.items">
						<div class="row">
							<div class="col-xs-8 text-left">
								<i class="fa fa-map-marker"></i> {{ accolade }}
							</div>
							<div class="col-xs-4 text-right">
								<button class="btn btn-default-hollow btn-xs" @click="removeAccolade(accolade)"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</modal>

        <modal class="text-center" v-if="isUser()" :show.sync="deleteModal" title="Remove Trip Visited" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to remove {{ selectedTripRemove.name|capitalize }} from your list?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,doRemove(selectedTripRemove)'>Confirm</button>
            </div>
        </modal>

	</div>
</template>
<script type="text/javascript">
	import vSelect from 'vue-select';
    export default{
        name: 'user-profile-trip-history',
        components: {vSelect},
        props:['id'],
        data(){
            return{
                accolades: { items: [] },
                trips: [],
                availableTrips: [],
                selectedTrips: null,
                selectedCodes: null,
                selectedTripRemove: { name: null},
                manageModal: false,
                deleteModal: false,
                resource: this.$resource('users{/id}/accolades{/name}')
            }
        },
        methods:{
            isUser(){
                return this.id === this.$root.user.id;
            },
            removeAccolade(trip){
				this.deleteModal = true;
				this.selectedTripRemove = trip;
            },
            doRemove(trip){
            	this.accolades.items = _.reject(this.accolades.items, function(accolade) {
            		return accolade === trip
            	});
            },
            addAccolade(trip){
				this.addModal = true;
            },
            updateAccolades(){
				let allCodes = _.union(this.accolades.items, this.selectedTrips);

				// save to API
				this.resource.save({id: this.id}, {
					name: "trip_history",
					display_name: "Trip History",
					items: allCodes
				}).then(function(response) {
				    this.$root.$emit('showSuccess', 'Trips History Updated!');
                    this.accolades = response.data.data;
                    this.selectedTrips = [];
                    this.filterAccolades();
				});
            },
            getAccolades(){
                this.resource.get({id: this.id, name: 'trip_history'}).then(function (response) {
                    this.accolades = response.data.data[0] || { items: [] };
					if (this.isUser()) {
   						this.filterAccolades();
					}
                });
            },
            filterAccolades(){
            	// If isUser() filter only trips not already included in accolades
				let accolades = this.accolades.items;

				this.availableTrips = _.filter(this.trips, function(trip) {
					return !_.contains(accolades, trip);
				});
			},
            searchTrips() {
				this.$http.get('utilities/past-trips').then(function(response) {
					this.trips = response.data;
				});
            }
        },
        ready(){
			if (this.isUser()) {
				this.searchTrips();
			}

			this.getAccolades();

        }
    }
</script>

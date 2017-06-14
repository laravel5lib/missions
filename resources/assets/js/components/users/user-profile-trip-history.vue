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
						<h5>
                        <a class="text-muted" @click="manageModal = true">
                            <i class="fa fa-plus" v-if="! accolades.items || accolades.items.length < 1"></i>
                            <i class="fa fa-cog" v-else></i>
                        </a>
                        </h5>
					</div>
				</div>
            </div><!-- end panel-heading -->
            <div class="panel-body">
                        <template v-if="accolades.items && accolades.items.length">
                            <p style="display:block;margin-bottom:3px;" v-for="accolade in accolades.items">
                                <span class="label label-default" style="display:inline-block;text-align:left;padding:0.5em 0.6em;width:100%;">
                                    <i class="fa fa-map-marker" style="margin-right:3px;"></i> {{ accolade }}
                                </span>
                            </p>
                        </template>
                        <template v-else>
                            <template v-if="isUser()">
                                <p class="small text-muted text-center"><em>Which Missions.Me trips have you been on? Add by clicking the <i style="margin-left:2px;" class="fa fa-plus"></i> icon.</em></p>
                            </template>
                            <template v-else>
                                <p class="small text-muted text-center"><em>This person hasn't added any trips to their profile yet.</em></p>
                            </template>
                        </template>
                </div><!-- end panel-body -->
            </div><!-- end panel -->

        <modal class="text-center" v-if="isUser()" :show.sync="manageModal" title="Manage Trips" width="800" :callback="updateAccolades">
            <div slot="modal-body" class="modal-body text-center">
				<validator name="AddTrip">
					<form class="for" @submit.prevent="" novalidate>
						<div class="form-group" :class="">
							<label class="control-label">Trips</label>
							<v-select @keydown.enter.prevent="" class="form-control" multiple :value.sync="selectedTrips" :options="availableTrips"
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
            <div slot="modal-body" class="modal-body text-center">Remove {{ selectedTripRemove.name|capitalize }} from your list?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,doRemove(selectedTripRemove)'>Delete</button>
            </div>
        </modal>

	</div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
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
            // TODO Refactor: use as computed prop
            isUser(){
                return this.$root.user && this.id === this.$root.user.id;
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
                    this.accolades = response.body.data;
                    this.selectedTrips = [];
                    this.filterAccolades();
				}, function (response) {
                    console.log(response);
                });
            },
            getAccolades(){
                this.resource.get({id: this.id, name: 'trip_history'}).then(function (response) {
                    this.accolades = response.body.data[0] || { items: [] };
					if (this.isUser()) {
   						this.filterAccolades();
					}
					return this.accolades;
                }, function (response) {
                    return response;
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
				return this.$http.get('utilities/past-trips').then(function(response) {
					return this.trips = response.body;
				}, function (response) {
                    return response;
                });
            }
        },
        ready(){
			if (this.isUser()) {
				this.searchTrips().then(function () {
                    this.getAccolades();
                });
			} else {
                this.getAccolades();
            }

        }
    }
</script>

<template>
    <div style="position:relative;">
		<spinner ref="spinner" global size="sm" text="Loading"></spinner>
		<div class="panel panel-default">
            <div class="panel-heading">
				<div class="row">
					<div class="col-xs-8">
						<h5>Trip History</h5>
					</div>
					<div class="col-xs-4 text-right" v-if="isUser">
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
                    <template v-if="isUser">
                        <p class="small text-muted text-center"><em>Which Missions.Me trips have you been on? Add by clicking the <i style="margin-left:2px;" class="fa fa-plus"></i> icon.</em></p>
                    </template>
                    <template v-else>
                        <p class="small text-muted text-center"><em>This person hasn't added any trips to their profile yet.</em></p>
                    </template>
                </template>
            </div><!-- end panel-body -->
        </div><!-- end panel -->

        <modal class="text-center" v-if="isUser" :value="manageModal" @closed="manageModal=false" title="Manage Trips" width="800" :callback="updateAccolades">
            <div slot="modal-body" class="modal-body text-center">
				<form name="AddTrip" class="for" @submit.prevent="" novalidate>
					<div class="form-group" :class="">
						<label class="control-label">Trips</label>
						<v-select @keydown.enter.prevent="" class="form-control" multiple v-model="selectedTrips" :options="availableTrips"
								  label="name"></v-select>
					</div>
				</form>

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

        <modal class="text-center" v-if="isUser" :value="deleteModal" @closed="deleteModal=false" title="Remove Trip Visited" :small="true">
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
    import utilities from '../utilities.mixin';
    export default{
        name: 'user-profile-trip-history',
        components: {vSelect},
        mixins: [utilities],
        props:['id'],
        data(){
            return{
                accolades: { items: [] },
                trips: [],
                availableTrips: [],
                selectedTrips: [],
                selectedCodes: [],
                selectedTripRemove: { name: null},
                manageModal: false,
                deleteModal: false,
            }
        },
	    computed: {
            isUser(){
                return this.$root.user && this.id === this.$root.user.id;
            },
	    },
	    watch: {
            selectedTrips(val) {
                this.selectedCodes = val;
            }
	    },
        methods:{
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
				this.$http.post(`users/${this.id}/accolades`, {
					name: "trip_history",
					display_name: "Trip History",
					items: allCodes
				}).then((response) => {
				    this.$root.$emit('showSuccess', 'Trips History Updated!');
                    this.accolades = response.data.data;
                    this.selectedTrips = [];
                    this.filterAccolades();
				}).catch(this.$root.handleApiError);
            },
            getAccolades(){
                this.$http.get(`users/${this.id}/accolades/trip_history`).then((response) => {
                    this.accolades = response.data.data[0] || { items: [] };
					if (this.isUser) {
   						this.filterAccolades();
					}
					return this.accolades;
                }).catch(this.$root.handleApiError);
            },
            filterAccolades(){
            	// If isUser filter only trips not already included in accolades
				let accolades = this.accolades.items;

				this.availableTrips = _.filter(this.UTILITIES.trips, function(trip) {
					return !_.contains(accolades, trip);
				});
			}
        },
        mounted(){
			if (this.isUser) {
				this.getTrips().then(() => {
                    this.getAccolades();
                });
			} else {
                this.getAccolades();
            }

        }
    }
</script>

<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div style="position:relative;">
		<spinner v-ref:spinner size="sm" text="Loading"></spinner>
		<div class="panel panel-default" data-aos="fade-up">
            <div class="panel-heading">
				<div class="row">
					<div class="col-xs-8">
						<h5>Countries Visited</h5>
					</div>
					<div class="col-xs-4 text-right" v-if="isUser()">
						<h5><a class="text-muted" @click="manageModal = true"><i class="fa fa-edit"></i></a></h5>
					</div>
				</div>
            </div><!-- end panel-heading -->
            <div class="panel-body">
                <p style="display:inline-block;margin-bottom:3px;" v-for="accolade in accolades.items">
                    <span class="label label-default">
                        <i class="fa fa-map-marker" style="margin-right:3px;"></i> {{ accolade.name }}
                    </span>
                </p>
				<p class="text-muted text-center small" v-if="! accolades.items || accolades.items.length < 1"><em>Add countries you've visited or sign up for a trip to get started!</em></p>
            </div><!-- end panel-body -->
        </div><!-- end panel -->

        <modal class="text-center" v-if="isUser()" :show.sync="manageModal" title="Manage Countries" width="800" :callback="updateAccolades">
            <div slot="modal-body" class="modal-body text-center">
				<validator name="AddCountry">
					<form class="for" novalidate>
						<div class="form-group" :class="">
							<label class="control-label">Countries</label>
							<v-select class="form-control" multiple :value.sync="selectedCountries" :options="availableCountries"
									  label="name"></v-select>
							<select hidden="" v-model="selectedCodes" multiple v-validate:code="{ required: true }">
								<option :value="country.code" v-for="country in availableCountries">{{country.name}}</option>
							</select>
						</div>
					</form>
				</validator>

				<ul class="list-group">
					<li class="list-group-item" v-for="accolade in accolades.items">
						<div class="row">
							<div class="col-xs-8 text-left">
								<i class="fa fa-map-marker"></i> {{ accolade.name }}
							</div>
							<div class="col-xs-4 text-right">
								<button class="btn btn-default-hollow btn-xs" @click="removeAccolade(accolade)"><i class="fa fa-trash"></i></button>
							</div>
						</div>
					</li>
				</ul>
			</div>
		</modal>

        <modal class="text-center" v-if="isUser()" :show.sync="deleteModal" title="Remove Country Visited" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to remove {{ selectedCountryRemove.name|capitalize }} from your list?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,doRemove(selectedCountryRemove)'>Confirm</button>
            </div>
        </modal>
	</div>
</template>
<script type="text/javascript">
	import vSelect from 'vue-select';
    export default{
        name: 'user-profile-countries',
        components: {vSelect},
        props:['id'],
        data(){
            return{
                accolades: [],
                countries: [],
                availableCountries: [],
                selectedCountries: null,
                selectedCodes: null,
                selectedCountryRemove: { name: null},
                manageModal: false,
                deleteModal: false,
                showSuccess: false,
                resource: this.$resource('users{/id}/accolades{/name}')
            }
        },
        methods:{
            isUser(){
                return this.id === this.$root.user.id;
            },
            removeAccolade(country){
				this.deleteModal = true;
				this.selectedCountryRemove = country;
            },
            doRemove(country){
            	this.accolades.items = _.reject(this.accolades.items, function(accolade) {
            		return accolade.code === country.code
            	});
            },
            addAccolade(country){
				this.addModal = true;
            },
            updateAccolades(){
				let allCodes = _.union(_.pluck(this.accolades.items, 'code'), _.pluck(this.selectedCountries, 'code'));

				// Uppercase all codes for consistency
				_.each(allCodes, function(code, index) {
					allCodes[index] = code.toUpperCase();
				});

				// save to API
				this.resource.save({id: this.id}, {
					name: "countries_visited",
					display_name: "Countries Visited",
					items: allCodes
				}).then(function(response) {
					this.showSuccess = true;
					this.$root.$emit('showSuccess', 'Countries Visited Updated!');
					this.accolades = response.data.data;
                    this.selectedCountries = [];
                    this.filterAccolades();
				});
            },
            getAccolades(){
                this.resource.get({id: this.id, name: 'countries_visited'}).then(function (response) {
                    this.accolades = response.data.data[0];
					if (this.isUser()) {
   						this.filterAccolades();
					}
                });
            },
            filterAccolades(){
            	// If isUser() filter only countries not already included in accolades
				let accolades = _.pluck(this.accolades.items, 'code');

				this.availableCountries = _.filter(this.countries, function(country) {
					return !_.contains(accolades, country.code.toUpperCase());
				});
			},
            searchCountries() {
				this.$http.get('utilities/countries').then(function(response) {
					this.countries = response.data.countries;
				});
            }
        },
        ready(){
			if (this.isUser()) {
				this.searchCountries();
			}

			this.getAccolades();

        }
    }
</script>

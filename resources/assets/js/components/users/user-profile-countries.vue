<template>
    <div style="position:relative;">
		<spinner ref="spinner" size="sm" text="Loading"></spinner>
		<div class="panel panel-default" data-aos="fade-up">
            <div class="panel-heading">
				<div class="row">
					<div class="col-xs-8">
						<h5>Countries Visited</h5>
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
                                    <i class="fa fa-map-marker" style="margin-right:3px;"></i> {{ accolade.name }}
                                </span>
                            </p>
                        </template>
                        <template v-else>
                            <template v-if="isUser">
                                <p class="small text-muted text-center"><em>Just for fun, add countries you've visited by clicking the <i style="margin-left:2px;" class="fa fa-plus"></i> icon.</em></p>
                            </template>
                            <template v-else>
                                <p class="small text-muted text-center"><em>This person hasn't added any countries to their visited list.</em></p>
                            </template>
                        </template>
                </div><!-- end panel-body -->
            </div><!-- end panel -->

        <modal class="text-center" v-if="isUser" :value="manageModal" @closed="manageModal=false" title="Manage Countries" width="800" :callback="updateAccolades">
            <div slot="modal-body" class="modal-body text-center">
				<form name="AddCountry" class="for" @submit.prevent="" novalidate>
					<div class="form-group" :class="">
						<label class="control-label">Countries</label>
						<v-select @keydown.enter.prevent="" class="form-control" multiple :value.sync="selectedCountries" :options="availableCountries"
								  label="name"></v-select>
						<select hidden v-model="selectedCodes" multiple>
							<option :value="country.code" v-for="country in availableCountries">{{country.name}}</option>
						</select>
					</div>
				</form>

				<ul class="list-group" v-if="accolades">
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

        <modal class="text-center" v-if="isUser" :value="deleteModal" @closed="deleteModal=false" title="Remove Country Visited" :small="true">
            <div slot="modal-body" class="modal-body text-center">Remove {{ selectedCountryRemove.name ? selectedCountryRemove.name[0].toUpperCase() + selectedCountryRemove.name.slice(1) : '' }} from your list?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,doRemove(selectedCountryRemove)'>Delete</button>
            </div>
        </modal>
	</div>
</template>
<script type="text/javascript">
	import _ from 'underscore';
	import vSelect from 'vue-select';
	import utilities from '../utilities.mixin';
    export default{
        name: 'user-profile-countries',
        components: {vSelect},
        mixins: [utilities],
        props:['id'],
        data(){
            return{
                accolades: { items: [] },
                countries: [],
                availableCountries: [],
                selectedCountries: [],
                selectedCodes: [],
                selectedCountryRemove: { name: null},
                manageModal: false,
                deleteModal: false,
                showSuccess: false,
                resource: this.$resource('users{/id}/accolades{/name}')
            }
        },
	    computed: {
            isUser(){
                return this.$root.user && this.id === this.$root.user.id;
            },
	    },
        watch: {
            selectedCountries(val) {
                this.selectedCodes = _.pluck(val, 'code');
            }
        },
        methods:{
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
                this.$validator.validateAll().then(result => {
                    if (!result) {
                        this.$root.$emit('showError', 'Please check the form.');
                        return false;
                    }

                    let allCodes = _.union(_.pluck(this.accolades.items, 'code'), this.selectedCodes);

                    // Uppercase all codes for consistency
                    _.each(allCodes, function (code, index) {
                        allCodes[index] = code.toUpperCase();
                    });

                    // save to API
                    this.resource.save({id: this.id}, {
                        name: "countries_visited",
                        display_name: "Countries Visited",
                        items: allCodes
                    }).then(function (response) {
                        this.showSuccess = true;
                        this.$root.$emit('showSuccess', 'Countries Visited Updated!');
                        this.accolades = response.body.data;
                        this.selectedCountries = [];
                        this.filterAccolades();
                    }, this.$root.handleApiError);
                });
            },
            getAccolades(){
                this.resource.get({id: this.id, name: 'countries_visited'}).then(function (response) {
                    this.accolades = response.body.data[0] || { items: [] };
					if (this.isUser) {
   						this.filterAccolades();
					}
                }, this.$root.handleApiError);
            },
            filterAccolades() {
                // If isUser filter only countries not already included in accolades
                let accolades = _.pluck(this.accolades.items, 'code');

                this.availableCountries = _.filter(this.UTILITIES.countries, function (country) {
                    return !_.contains(accolades, country.code.toUpperCase());
                });
            }
        },
        mounted(){
			if (this.isUser) {
				this.getCountries().then(function () {
                    this.getAccolades();
                });
			} else {
                this.getAccolades();
            }

        }
    }
</script>

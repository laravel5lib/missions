<template>
	<div>
		<div>this is template body</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    import utilities from'../utilities.mixin';
    export default{
        name: 'accommodations',
        mixins: [utilities],
        components: {vSelect},
        props: {
            campaignId: {
                type: String,
	            required: true,
            }
	    },
        data(){
            return {
                showRegionsFilters: false,

                regionsSearch: '',
                accommodationsSearch: '',

                regionsFilters: {
                    country: null
                },
                accommodationsFilters: {},

                regions: [],
                regionsPagination: { current_page: 1 },
                accommodations: [],
                accommodationsPagination: { current_page: 1 },
                currentRegion: null,
                currentAccommodation: null,
                RegionsResource: this.$resource('campaigns{/campaign}/regions{/region}'),
                AccommodationsResource: this.$resource('regions{/region}/accommodations{/accommodation}'),
            }
        },
	    watch: {
            regionsSearch() {
                this.regionsPagination.current_page = 1;
                this.getRegions();
            },
            regionsFilters: {
                handler(){
                    this.regionsPagination.current_page = 1;
                    this.getRegions();
                },
	            deep: true
            },
	    },
        methods: {
            resetRegionFilter(){
                this.regionsFilters = {
                    country: null,
                };
            },
            getAccommodations(){
                let params = {
                    region: this.currentRegion.id,

                };

                return this.AccommodationsResource.get(params).then(function (response) {

                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
            getRegions(){
                let params = {
                    campaign: this.campaignId,
                    include: '',
                    page: this.regionsPagination.current_page,
                    search: this.regionsSearch,
	                country: _.isObject(this.regionsFilters.country) ? this.regionsFilters.country.code :    undefined
                };

                //params.country = this.regionsFilters.country;

                return this.RegionsResource.get(params).then(function (response) {
                    this.regionsPagination = response.body.meta.pagination;
                    return this.regions = response.body.data;
                }, function (response) {
                    console.log(response);
                    return response.body.data;
                });
            },
        },
        ready() {
            let promises = [];
            promises.push(this.getCountries());
            promises.push(this.getRegions());
            Promise.all(promises).then(function (values) {

            });
        }
    }
</script>
<template>
	<div>
		<hr class="divider inv sm">
		<form class="col-sm-12">
			<div class="form-group" v-if="propertyExists('groups')">
				<label>Travel Group</label>
				<v-select @keydown.enter.prevent="" multiple class="form-control" id="groupFilter"  :debounce="250" :on-search="getGroups"
				          :value.sync="groupsArr" :options="groupsOptions" label="name"
				          placeholder="Filter Groups"></v-select>
			</div>

			<div class="form-group" v-if="propertyExists('designation')">
				<label>Arrival Designation</label>
				<select  class="form-control input-sm" v-model="filters.designation">
					<option value="">Any</option>
					<option value="eastern">Eastern</option>
					<option value="western">Western</option>
					<option value="international">International</option>
					<option value="weekend">Weekend</option>
					<option value="other">Other</option>
					<option value="none">None</option>
				</select>
			</div>

			<div class="form-group" v-if="propertyExists('type')">
				<label>Trip Type</label>
				<select  class="form-control input-sm" v-model="filters.type">
					<option value="">Any Type</option>
					<option value="ministry">Ministry</option>
					<option value="family">Family</option>
					<option value="international">International</option>
					<option value="media">Media</option>
					<option value="medical">Medical</option>
					<option value="leader">Leader</option>
				</select>
			</div>

			<div class="form-group" v-if="propertyExists('min_passengers') && propertyExists('max_passengers')">
				<div class="row">
					<div class="col-xs-12">
						<label>Age Range</label>
					</div>
					<div class="col-xs-6">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">Age Min</span>
							<input type="number" class="form-control" number v-model="filters.min_passengers" debounce="250" min="0">
						</div>
					</div>
					<div class="col-xs-6">
						<div class="input-group input-group-sm">
							<span class="input-group-addon">Max</span>
							<input type="number" class="form-control" number v-model="filters.max_passengers" debounce="250" :min="filters.min_passengers" max="9999">
						</div>
					</div>
				</div>
			</div>

			<hr class="divider inv sm">
			<button class="btn btn-default btn-sm btn-block" type="button" @click="resetCallback"><i class="fa fa-times"></i> Reset Filters</button>
		</form>
	</div>
</template>
<style></style>
<script type="text/javascript">
	import _ from 'underscore';
    import vSelect from 'vue-select';
    export default{
        name: 'transports-filters',
        components: {vSelect},
        props: {
            // Main object that contains all filters used by the parent component for API calls
            filters: {
                type: Object,
                required: true,
                default: null
            },
            // Reset function used to restore defaults of variables that are needed for filters,
            // but are not in the filters object
            resetCallback: {
                type: Function,
                required: true
            },
            // Pagination of the reservations list that the filters variable influences
            pagination: {
                type: Object,
                required: true
            },
            // This is references the function that would/should be run when filters variable is updated
            callback: {
                type: Function,
                required: true
            },
            // This variabe is the name of the localStorage property that holds the config of the parent component
            storage: {
                type: String,
                required: false
            },	    },
        data(){
            return {
                groupsOptions: [],
                groupsArr: [],
            }
        },
	    watch: {
            'filters': {
                handler: function (val) {
//                    if (this.starter)
//                        return;
//                    // console.log(val);
                    this.pagination.current_page = 1;
                    this.callback();
                },
                deep: true
            },
		    'groupsArr'(val) {
                this.filters.groups = _.pluck(val, 'id') || [];
		    }
        },
        methods: {
            propertyExists(property) {
                // Instead of concerning the filters component about where it is
                // It is may be best to behave based on the filter object passed in
                // We can ho this be checking whether a property exists in it
                return this.filters && this.filters.hasOwnProperty(property);
            },
            getGroups(search, loading){
                loading ? loading(true) : void 0;
                let promise = this.$http.get('groups', { params: {search: search} }).then(function (response) {
                    this.groupsOptions = response.body.data;
                    if (loading) {
                        loading(false);
                    } else {
                        return promise;
                    }
                }, this.$root.handleApiError);
            },
        },
        ready(){
            if (this.propertyExists('groups'))
			this.getGroups();
        }
    }
</script>
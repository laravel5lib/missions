<template>
	<div>
		<hr class="divider sm">
		<div>
			<label>Active Filters</label>
			<!-- Reservations Filters -->
			<span v-if="propertyExists('type')" style="margin-right:2px;" class="label label-default" v-show="filters.type != ''" @click="filters.type = ''" >
				Trip Type
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('tags')" style="margin-right:2px;" class="label label-default" v-show="filters.tags.length" @click="filters.tags = [],syncFilters()" >
				Tags
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('user')" style="margin-right:2px;" class="label label-default" v-show="filters.user.length" @click="filters.user = [],syncFilters()" >
				Users
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('groups')" style="margin-right:2px;" class="label label-default" v-show="filters.groups.length" @click="filters.groups = [],syncFilters()" >
				Travel Group(s)
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('campaign')" style="margin-right:2px;" class="label label-default" v-show="filters.campaign != null" @click="filters.campaign = null,syncFilters()" >
				Campaign
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('role')" style="margin-right:2px;" class="label label-default" v-show="filters.role !== ''" @click="filters.role = ''" >
				Role
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('gender')" style="margin-right:2px;" class="label label-default" v-show="filters.gender != ''" @click="filters.gender = '',syncFilters()" >
				Gender
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('status')" style="margin-right:2px;" class="label label-default" v-show="filters.status != ''" @click="filters.status = '',syncFilters()" >
				Status
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('rep')" style="margin-right:2px;" class="label label-default" v-show="filters.rep != ''" @click="filters.rep = '',syncFilters()" >
				Trip Rep.
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('shirtSize')" style="margin-right:2px;" class="label label-default" v-show="filters.shirtSize != ''" @click="filters.shirtSize = '',syncFilters()" >
				Shirt Size
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('hasCompanions')" style="margin-right:2px;" class="label label-default" v-show="filters.hasCompanions !== null" @click="filters.hasCompanions = null,syncFilters()" >
				Companions
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('todoName')" style="margin-right:2px;" class="label label-default" v-show="filters.todoName != ''" @click="filters.todoName = '', filters.todoStatus = null,syncFilters()" >
				{{ todo }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('designation')" style="margin-right:2px;" class="label label-default" v-show="filters.designation != ''" @click="filters.designation = '',syncFilters()" >
				Designation
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('region')" style="margin-right:2px;" class="label label-default" v-show="filters.region != ''" @click="filters.region = '',syncFilters()" >
				Region
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('requirementName')" style="margin-right:2px;" class="label label-default" v-show="filters.requirementName != ''" @click="filters.requirementName = '', filters.requirementStatus = '',syncFilters()" >
				{{ requirement }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('dueName')" style="margin-right:2px;" class="label label-default" v-show="filters.dueName != ''" @click="filters.dueName = '', filters.dueStatus = '',syncFilters()" >
				{{ due }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('age')" style="margin-right:2px;" class="label label-default" v-show="filters.age[0] != 0" @click="filters.age[0] = 0" >
				Min. Age
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('age')" style="margin-right:2px;" class="label label-default" v-show="filters.age[1] != 120" @click="filters.age[1] = 120" >
				Max. Age
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('minAmountRaised')" style="margin-right:2px;" class="label label-default" v-show="filters.minAmountRaised != null" @click="filters.minAmountRaised = null" >
				Min. $ Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('maxAmountRaised')" style="margin-right:2px;" class="label label-default" v-show="filters.maxAmountRaised != null" @click="filters.maxAmountRaised = null" >
				Max. $ Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('minPercentRaised')" style="margin-right:2px;" class="label label-default" v-show="filters.minPercentRaised != null" @click="filters.minPercentRaised = null" >
				Min. % Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('maxPercentRaised')" style="margin-right:2px;" class="label label-default" v-show="filters.maxPercentRaised != null" @click="filters.maxPercentRaised = null" >
				Max. % Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('hasRoom')" style="margin-right:2px;" class="label label-default" v-show="filters.hasRoom !== null" @click="filters.hasRoom = null,syncFilters()" >
				Has Room
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('noRoom')" style="margin-right:2px;" class="label label-default" v-show="filters.noRoom !== null" @click="filters.noRoom = null,syncFilters()" >
				No Room
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('inTransport')" style="margin-right:2px;" class="label label-default" v-show="filters.inTransport !== null" @click="filters.inTransport = null,syncFilters()" >
				Has Transportation
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('notInTransport')" style="margin-right:2px;" class="label label-default" v-show="filters.notInTransport !== null" @click="filters.notInTransport = null,syncFilters()" >
				No Transportation
				<i class="fa fa-close"></i>
			</span>
            <span v-if="propertyExists('traveling')" style="margin-right:2px;" class="label label-default" v-show="filters.traveling !== null" @click="filters.traveling = null,syncFilters()" >
				Travel Designation
				<i class="fa fa-close"></i>
			</span>
            <span v-if="propertyExists('notTraveling')" style="margin-right:2px;" class="label label-default" v-show="filters.notTraveling !== null" @click="filters.notTraveling = null,syncFilters()" >
				Travel Designation
				<i class="fa fa-close"></i>
			</span>
		</div>
	</div>
</template>
<style></style>
<script type="text/javascript">
    export default{
        name: 'filters-indicator',
        props: {
            // Main object that contains all filters used by the parent component for API calls
            filters: {
                type: Object,
                required: true,
                default: null
            }
        },
        data(){
            return {

            }
        },
        computed: {
            todo() {
                if (this.filters.todoStatus && this.filters.todoName) {
                    return this.filters.todoName + '|' + this.filters.todoStatus;
                } else {
                    return this.filters.todoName;
                }
            },
            requirement() {
                if (this.filters.requirementName && this.filters.requirementStatus)
                    return this.filters.requirementName + '|' + this.filters.requirementStatus;

                return this.filters.requirementName;
            },
            due() {
                if (this.filters.dueName && this.filters.dueStatus)
                    return this.filters.dueName + '|' + this.filters.dueStatus;

                return this.filters.dueName;
            }
        },
	    watch:{
            requirement(){
                if (this.filters.requirementName) {
                    if (this.filters.requirementName && this.filters.requirementStatus)
                        return this.filters.requirementName + '|' + this.filters.requirementStatus;

                    return this.filters.requirementName;
                }

                return ''
            },
            due(){
                if (this.filters.dueName) {
                    if (this.filters.dueStatus)
                        return this.filters.dueName + '|' + this.filters.dueStatus;

                    return this.filters.dueName;
                }

                return ''
            }
	    },
        methods: {
            propertyExists(property) {
                // Instead of concerning the filters component about where it is
                // It is may be best to behave based on the filter object passed in
                // We can ho this be checking whether a property exists in it
                return this.filters.hasOwnProperty(property);
            },
	        syncFilters() {
                // this.$root.$emit('reservations-filters:update:filter', this.filters);
            }

        },
        mounted(){

        }
    }
</script>
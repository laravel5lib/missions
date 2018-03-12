<template>
	<div>
		<hr class="divider sm">
		<div class="filter-indicators">
			<label>Active Filters</label>
			<!-- Reservations Filters -->
			<span v-if="propertyExists('type')" class="label label-default" v-show="filterData.type != ''" @click="filterData.type = ''" >
				Trip Type
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('tags')" class="label label-default" v-show="filterData.tags.length" @click="filterData.tags = [],syncFilters()" >
				Tags
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('user')" class="label label-default" v-show="filterData.user.length" @click="filterData.user = [],syncFilters()" >
				Users
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('groups')" class="label label-default" v-show="filterData.groups.length" @click="filterData.groups = [],syncFilters()" >
				Travel Group(s)
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('campaign')" class="label label-default" v-show="filterData.campaign != null" @click="filterData.campaign = null,syncFilters()" >
				Campaign
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('role')" class="label label-default" v-show="filterData.role !== ''" @click="filterData.role = ''" >
				Role
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('gender')" class="label label-default" v-show="filterData.gender != ''" @click="filterData.gender = '',syncFilters()" >
				Gender
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('status')" class="label label-default" v-show="filterData.status != ''" @click="filterData.status = '',syncFilters()" >
				Status
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('rep')" class="label label-default" v-show="filterData.rep != ''" @click="filterData.rep = '',syncFilters()" >
				Trip Rep.
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('shirtSize')" class="label label-default" v-show="filterData.shirtSize != ''" @click="filterData.shirtSize = '',syncFilters()" >
				Shirt Size
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('hasCompanions')" class="label label-default" v-show="filterData.hasCompanions !== null" @click="filterData.hasCompanions = null,syncFilters()" >
				Companions
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('todoName')" class="label label-default" v-show="filterData.todoName != ''" @click="filterData.todoName = '', filterData.todoStatus = null,syncFilters()" >
				{{ todo }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('designation')" class="label label-default" v-show="filterData.designation != ''" @click="filterData.designation = '',syncFilters()" >
				Designation
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('region')" class="label label-default" v-show="filterData.region != ''" @click="filterData.region = '',syncFilters()" >
				Region
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('requirementName')" class="label label-default" v-show="filterData.requirementName != ''" @click="filterData.requirementName = '', filterData.requirementStatus = '',syncFilters()" >
				{{ requirement }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('dueName')" class="label label-default" v-show="filterData.dueName != ''" @click="filterData.dueName = '', filterData.dueStatus = '',syncFilters()" >
				{{ due }}
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('age')" class="label label-default" v-show="filterData.age[0] != 0" @click="filterData.age[0] = 0" >
				Min. Age
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('age')" class="label label-default" v-show="filterData.age[1] != 120" @click="filterData.age[1] = 120" >
				Max. Age
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('minAmountRaised')" class="label label-default" v-show="filterData.minAmountRaised != null" @click="filterData.minAmountRaised = null" >
				Min. $ Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('maxAmountRaised')" class="label label-default" v-show="filterData.maxAmountRaised != null" @click="filterData.maxAmountRaised = null" >
				Max. $ Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('minPercentRaised')" class="label label-default" v-show="filterData.minPercentRaised != null" @click="filterData.minPercentRaised = null" >
				Min. % Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('maxPercentRaised')" class="label label-default" v-show="filterData.maxPercentRaised != null" @click="filterData.maxPercentRaised = null" >
				Max. % Raised
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('hasRoom')" class="label label-default" v-show="filterData.hasRoom !== null" @click="filterData.hasRoom = null,syncFilters()" >
				Has Room
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('noRoom')" class="label label-default" v-show="filterData.noRoom !== null" @click="filterData.noRoom = null,syncFilters()" >
				No Room
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('inTransport')" class="label label-default" v-show="filterData.inTransport !== null" @click="filterData.inTransport = null,syncFilters()" >
				Has Transportation
				<i class="fa fa-close"></i>
			</span>
			<span v-if="propertyExists('notInTransport')" class="label label-default" v-show="filterData.notInTransport !== null" @click="filterData.notInTransport = null,syncFilters()" >
				No Transportation
				<i class="fa fa-close"></i>
			</span>
            <span v-if="propertyExists('traveling')" class="label label-default" v-show="filterData.traveling !== null" @click="filterData.traveling = null,syncFilters()" >
				Travel Designation
				<i class="fa fa-close"></i>
			</span>
            <span v-if="propertyExists('notTraveling')" class="label label-default" v-show="filterData.notTraveling !== null" @click="filterData.notTraveling = null,syncFilters()" >
				Travel Designation
				<i class="fa fa-close"></i>
			</span>
		</div>
	</div>
</template>
<style scoped>
	.filter-indicators span.label {
		margin-right:2px;
		cursor: pointer;
	}
</style>
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
				filterData: null,
            }
        },
        computed: {
            todo: {
                get() {
                    if (this.filterData) {
                        if (this.filterData.todoStatus && this.filterData.todoName) {
                            return this.filterData.todoName + '|' + this.filterData.todoStatus;
                        } else {
                            return this.filterData.todoName;
                        }
                    }
                },
	            set() {}
            },
            requirement: {
                get() {
                    if (this.filterData) {
                        if (this.filterData.requirementName && this.filterData.requirementStatus)
                            return this.filterData.requirementName + '|' + this.filterData.requirementStatus;

                        return this.filterData.requirementName;
                    }
                },
                set() {}
            },
            due: {
                get() {
                    if (this.filterData) {
                        if (this.filterData.dueName && this.filterData.dueStatus)
                            return this.filterData.dueName + '|' + this.filterData.dueStatus;

                        return this.filterData.dueName;
                    }
                },
                set() {}
            }
        },
	    watch:{
            requirement(){
                if (this.filterData && this.filterData.requirementName) {
                    if (this.filterData.requirementName && this.filterData.requirementStatus)
                        return this.filterData.requirementName + '|' + this.filterData.requirementStatus;

                    return this.filterData.requirementName;
                }

                return ''
            },
            due(){
                if (this.filterData && this.filterData.dueName) {
                    if (this.filterData.dueStatus)
                        return this.filterData.dueName + '|' + this.filterData.dueStatus;

                    return this.filterData.dueName;
                }

                return ''
            },
		    filters: {
                handler(val) {
                    this.$emit('input', val);
                    this.$emit('update:filters', val);
                },
			    deep: true
		    },
            filterData: {
                handler(val) {
                    this.$emit('update:filters', val);
                },
			    deep: true
		    },
	    },
        methods: {
            propertyExists(property) {
                // Instead of concerning the filters component about where it is
                // It is may be best to behave based on the filter object passed in
                // We can ho this be checking whether a property exists in it
                return this.filterData && this.filterData.hasOwnProperty(property);
            },
	        syncFilters() {
                // this.$root.$emit('reservations-filters:update:filter', this.filters);
            }

        },
        mounted(){
			this.filterData = this.filters;
        }
    }
</script>
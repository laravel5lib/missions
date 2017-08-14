/**
 * Created by jerezb on 2017-05-24.
 */
// The purpose of this mixin is to eliminate duplicate code and provide consistent use of the Utilities API when needed
import _ from 'underscore';
export default {
    data() {
        return {
            UTILITIES: {
                countries: [],
                airports: [],
                airlines: [],
                timezones: [],
                roles: [],
                roleTypes: {
                    general: [],
                    medical: [],
                    leadership: [],
                },
                activityTypes: [],
                trips: [],
            }
        }
    },
    methods: {
        getCountries(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/countries', {params: {search: search}}).then((response) => {
                this.UTILITIES.countries = response.data.countries;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.countries;
                }
            }).catch(this.$root.handleApiError)
        },
        getTimezones(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/timezones', {params: {search: search}}).then((response) => {
                this.UTILITIES.timezones = response.data.timezones;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.timezones;
                }
            }).catch(this.$root.handleApiError)
        },
        getRoles(conditionArray, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/team-roles').then((response) => {
                let arr = [];
                _.each(response.data.roles, function (name, key) {
                    if (conditionArray && _.isArray(conditionArray)) {
                        if (_.contains(conditionArray, key))
                            this.UTILITIES.roles.push({ value: key, name: name});
                    } else
                        arr.push({ value: key, name: name});
                }.bind(this));
                this.UTILITIES.roles = arr;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.roles;
                }
            }).catch(this.$root.handleApiError);
        },
        getRolesByType(type, loading){
            if (!_.contains(['leadership', 'general', 'medical'], type)) {
                console.error('`type` is not an existing type of collection of roles');
                return;
            }

            loading ? loading(true) : void 0;
            return this.$http.get('utilities/team-roles/' + type).then((response) => {
                let arr = [];
                _.each(response.data.roles, function (name, key) {
                    arr.push({ value: key, name: name});
                }.bind(this));
                this.UTILITIES.roleTypes[type] = arr;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.roleTypes[type];
                }
            }).catch(this.$root.handleApiError);
        },
        getAirports(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/airports', { params: {search: search, sort: 'name'} }).then((response) => {
                let airports = response.data.data;
                _.each(airports, function (airport) {
                    if (airport.iata) {
                        airport.extended_name = airport.name + ' (' + airport.iata + ')';
                    } else if (airport.icao) {
                        airport.extended_name = airport.name + ' (' + airport.icao + ')'
                    } else
                        airport.extended_name = airport.name;
                });

                this.UTILITIES.airports = airports;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.airports;
                }
            }).catch(this.$root.handleApiError);
        },
        getAirport(reference){
            return this.$http.get('utilities/airports/' + reference).then((response) => {
                if (response.data.iata) {
                    response.data.extended_name = response.data.name + ' (' + response.data.iata + ')';
                } else if (response.data.icao) {
                    response.data.extended_name = response.data.name + ' (' + response.data.icao + ')'
                } else
                    response.data.extended_name = response.data.name;

                return response.data.data;
            }).catch(this.$root.handleApiError);
        },
        getAirlines(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/airlines', { params: {search: search, sort: 'name'} }).then((response) => {
                let airlines = response.data.data;
                _.each(airlines, function (airline) {
                    airline.extended_name = airline.iata ? airline.name + ' (' + airline.iata + ')' : airline.name;
                });
                    this.UTILITIES.airlines = airlines;
                    this.UTILITIES.airlines.push({
                        name: 'Other',
                        extended_name: 'Other',
                        call_sign: ''
                    });
                    if (loading) {
                        loading(false);
                    } else {
                        return this.UTILITIES.airlines;
                    }
                }).catch(this.$root.handleApiError);
        },
        getAirline(reference){
            return this.$http.get('utilities/airlines/' + reference).then((response) => {
                    return response.data.data;
                }).catch(this.$root.handleApiError);
        },
        getTypes() {
            return this.$http.get('utilities/activities/types').then((response) => {
                    return this.UTILITIES.activityTypes = response.data;
                }).catch(this.$root.handleApiError);
        },
        getActivityTypes() {
            return this.$http.get('utilities/activities/types').then((response) => {
                    return this.UTILITIES.activityTypes = response.data;
                }).catch(this.$root.handleApiError);
        },
        getTrips() {
            return this.$http.get('utilities/past-trips').then((response) => {
                return this.UTILITIES.trips = response.data;
            }).catch(this.$root.handleApiError);
        },
        getSlug(slug) {
            return this.$http.get('utilities/make-slug/' + slug, { params: { hideLoader: true } })
                .then((response) => {
                    return response.data.slug;
                }).catch(this.$root.handleApiError);
        },
    },
}
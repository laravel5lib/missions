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
            return this.$http.get('utilities/countries', {params: {search: search}}).then(function (response) {
                this.UTILITIES.countries = response.body.countries;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.countries;
                }
            })
        },
        getTimezones(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/timezones', {params: {search: search}}).then(function (response) {
                this.UTILITIES.timezones = response.body.timezones;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.timezones;
                }
            })
        },
        getRoles(conditionArray, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/team-roles').then(function (response) {
                let arr = [];
                _.each(response.body.roles, function (name, key) {
                    if (conditionArray) {
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
            });
        },
        getRolesByType(type, loading){
            if (!_.contains(['leadership', 'general', 'medical'], type)) {
                console.error('`type` is not an existing type of collection of roles');
                return;
            }

            loading ? loading(true) : void 0;
            return this.$http.get('utilities/team-roles/' + type).then(function (response) {
                let arr = [];
                _.each(response.body.roles, function (name, key) {
                    arr.push({ value: key, name: name});
                }.bind(this));
                this.UTILITIES.roleTypes[type] = arr;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.roleTypes[type];
                }
            });
        },
        getAirports(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/airports', { params: {search: search, sort: 'name'} }).then(function (response) {
                this.UTILITIES.airports = response.body.data;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.airports;
                }
            });
        },
        getAirport(reference){
            return this.$http.get('utilities/airports/' + reference).then(function (response) {
                return response.body.data;
            });
        },
        getAirlines(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/airlines', { params: {search: search, sort: 'name'} }).then(function (response) {
                let airlines = response.body.data;
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
                },
                function (response) {
                    console.log(response);
                });
        },
        getAirline(reference){
            return this.$http.get('utilities/airlines/' + reference).then(function (response) {
                    return response.body.data;
                },
                function (response) {
                    console.log(response);
                });
        },
        getTypes() {
            return this.$http.get('utilities/activities/types').then(function (response) {
                    return this.UTILITIES.activityTypes = response.body;
                },
                function (response) {
                    console.log(response);
                });
        },
        getTrips() {
            return this.$http.get('utilities/past-trips').then(function(response) {
                return this.UTILITIES.trips = response.body;
            }, function (response) {
                return response;
            });
        },
        getSlug(slug) {
            return this.$http.get('utilities/make-slug/' + slug, { params: { hideLoader: true } })
                .then(function (response) {
                    return response.body.slug;
                });
        },
    },
}
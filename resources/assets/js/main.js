import Vue from 'vue';
import login from './components/login.vue';
import campaigns from './components/campaigns/campaigns.vue';
import campaignGroups from './components/campaigns/campaign-groups.vue';
import groupTrips from './components/campaigns/group-trips.vue';
import groupTripWrapper from './components/campaigns/groups-trips-selection-wrapper.vue';
import tripRegWizard from './components/trips/trip-registration-wizard.vue';

// admin components
import campaignCreate from './components/campaigns/campaign-create.vue';

// jQuery
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
require('jquery.cookie');
require('bootstrap-sass');

$( document ).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
});

// Vue Resource
Vue.use(require('vue-resource'));
// Vue Validator
Vue.use(require('vue-validator'));

Vue.http.options.root = '/api';
Vue.http.interceptors.push({

    request: function (request) {
        var token, headers

        // swap local storage to cookie
        // token = window.localStorage.getItem('jwt-token')
        token = 'Bearer ' + $.cookie('jwt_token');
        console.log(token);
        headers = request.headers || (request.headers = {})

        if (token !== null && token !== 'undefined') {
            headers.Authorization = token
        }

        return request
    },

    response: function (response) {
        if (response.status && response.status === 401) {
            // swap local storage to cookie
            window.localStorage.removeItem('jwt-token')
        }
        if (response.headers && response.headers('Authorization')) {
            console.log('found authorization header')
            // swap local storage to cookie
            document.cookie = 'jwt_token=' + response.headers('Authorization')
            // window.localStorage.setItem('jwt-token', response.headers('Authorization'))
        }
        if (response.data && response.data.token && response.data.token.length > 10) {
            // swap local storage to cookie
            document.cookie = 'jwt_token=' + response.data.token
            // window.localStorage.setItem('jwt-token', 'Bearer ' + response.data.token)
        }

        return response
    }
});

// Register email validator function.
Vue.validator('email', function (val) {
    return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(val)
});

Vue.filter('phone', {
    read:function (phone) {
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    },
    write: function(phone, phoneVal) {
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }
});

new Vue({
    el: '#app',
    components: [
        login,
        campaigns,
        campaignGroups,
        groupTrips,
        groupTripWrapper,
        tripRegWizard,
        campaignCreate,
    ],
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    ready: function() {
        console.log('vue is ready')
    }
});
import Vue from 'vue';
import login from './components/login.vue';
import campaigns from './components/campaigns/campaigns.vue';
import campaignGroups from './components/campaigns/campaign-groups.vue';
import groupTrips from './components/campaigns/group-trips.vue';
import groupTripWrapper from './components/campaigns/groups-trips-selection-wrapper.vue';
import tripRegWizard from './components/trips/trip-registration-wizard.vue';

// admin components
import campaignCreate from './components/campaigns/admin-campaign-create.vue';
import campaignEdit from './components/campaigns/admin-campaign-edit.vue';
import campaignDetails from './components/campaigns/admin-campaign-details.vue';

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

        token = 'Bearer ' + $.cookie('api_token');

        headers = request.headers || (request.headers = {})

        if (token !== null && token !== 'undefined') {
            headers.Authorization = token
        }

        return request
    },

    response: function (response) {
        if (response.status && response.status === 401) {
            $.removeCookie('api_token');
        }
        if (response.headers && response.headers('Authorization')) {
            $.cookie('api_token', response.headers('Authorization'));
        }
        if (response.data && response.data.token && response.data.token.length > 10) {
            $.cookie('api_token', response.data.token);
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
    data: {
      user: {
        name: '',
        email: '',
        public: false
      }
    },
    components: [
        login,
        campaigns,
        campaignGroups,
        groupTrips,
        groupTripWrapper,
        tripRegWizard,
        campaignCreate,
        campaignEdit,
        campaignDetails,
    ],
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    ready: function() {
        // console.log('vue is ready'),
        this.$on('userHasLoggedIn', function (user) {
          this.setUser(user)
        })
    },
    methods: {
        setUser: function (user) {
          // Save user info
          this.user = user
          this.authenticated = true
        }
    }
});
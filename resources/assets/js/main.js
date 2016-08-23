import Vue from 'vue';
import login from './components/login.vue';
import topNav from './components/top-nav.vue';
import actionTrigger from './components/action-trigger.vue';
import donate from './components/donate.vue';
import campaigns from './components/campaigns/campaigns.vue';
import campaignGroups from './components/campaigns/campaign-groups.vue';
import groupTrips from './components/campaigns/group-trips.vue';
import groupProfileTrips from './components/groups/group-profile-trips.vue';
import groupProfileStories from './components/groups/group-profile-stories.vue';
import groupTripWrapper from './components/campaigns/groups-trips-selection-wrapper.vue';
import tripDetailsMissionaries from './components/trips/trip-details-missionaries.vue';
import tripRegWizard from './components/trips/trip-registration-wizard.vue';
import reservationsList from './components/reservations/reservations-list.vue';
import donationsList from './components/reservations/donations-list.vue';
import recordsList from './components/records/records-list.vue';
import groupsList from './components/groups/groups-list.vue';
import visasList from './components/visas/visas-list.vue';
import passportsList from './components/passports/passports-list.vue';
import passportCreateUpdate from './components/passports/passport-create-update.vue';
import visaCreateUpdate from './components/visas/visa-create-update.vue';
import reservationsPassportsManager from './components/reservations/reservations-passports-manager.vue';
import reservationsVisasManager from './components/reservations/reservations-visas-manager.vue';
import userSettings from './components/users/user-settings.vue';
import userProfileStories from './components/users/user-profile-stories.vue';
import userProfileFundraisers from './components/users/user-profile-fundraisers.vue';
import groupProfileFundraisers from './components/groups/group-profile-fundraisers.vue';

// admin components
import adminCampaignCreate from './components/campaigns/admin-campaign-create.vue';
import adminCampaignEdit from './components/campaigns/admin-campaign-edit.vue';
import adminCampaignDetails from './components/campaigns/admin-campaign-details.vue';
import adminCampaignTripCreate from './components/trips/admin-trip-create.vue';
import adminCampaignTripEdit from './components/trips/admin-trip-edit.vue';
import adminTrips from './components/trips/admin-trips-list.vue';
import adminTripsReservations from './components/trips/admin-trip-reservations-list.vue';
import adminTripsFacilitators from './components/trips/admin-trip-facilitators.vue';
import adminTripsDuplicate from './components/trips/admin-trip-duplicate.vue';
import adminTripsDelete from './components/trips/admin-trip-delete.vue';
import adminGoups from './components/groups/admin-groups-list.vue';
import adminGroupCreate from './components/groups/admin-group-create.vue';
import adminGroupEdit from './components/groups/admin-group-edit.vue';
import adminGroupManagers from './components/groups/admin-group-managers.vue';
import adminReservations from './components/reservations/admin-reservations-list.vue';
import adminUsers from './components/users/admin-users-list.vue';
import adminUserCreate from './components/users/admin-user-create.vue';
import adminUserEdit from './components/users/admin-user-edit.vue';
import adminUserDelete from './components/users/admin-user-delete.vue';
import adminUploads from './components/uploads/admin-uploads-list.vue';
import adminUploadCreateUpdate from './components/uploads/admin-upload-create-update.vue';

// jQuery
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
window._ = require('underscore');
window.marked = require('marked');
require('gsap');
window.ScrollMagic = require('scrollmagic');
// require('vue-strap/src/index.js');
// window.VueStrap = require('vue-strap/dist/vue-strap.min');
import VueStrap from 'vue-strap/dist/vue-strap.min';

require('jquery.cookie');
require('bootstrap-sass');

$( document ).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });
});

// Vue Resource
Vue.use(require('vue-resource'));
// Vue Validator
Vue.use(require('vue-validator'));

Vue.http.options.root = '/api';
Vue.http.interceptors.push({

    request: function (request) {
        var token, headers;

        token = 'Bearer ' + $.cookie('api_token');

        headers = request.headers || (request.headers = {})

        if (token !== null && token !== 'undefined') {
            headers.Authorization = token
        }

        return request
    },

    response: function (response) {
        if (response.status && response.status === 401) {
            Vue.http.post('/api/refresh').then(
                function (response) {
                    $.cookie('api_token', response.data.token);
                    window.location.reload();
                },
                function (response) {
                    if (response.status && response.status === 401 || response.status && response.status === 500) {
                        $.removeCookie('api_token');
                        window.location.replace('/logout');
                    };
                }
            )

        }
        if (response.status && response.status === 500) {
            alert('Oops! Something went wrong with the server.')
            // $.removeCookie('api_token');
            // window.location.replace('/logout');
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
        phone = phone||'';
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    },
    write: function(phone, phoneVal) {
        phone = phone||'';
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }
});

Vue.filter('number', {
    read:function (number, decimals) {
        return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
    },
    write: function(number, numberVal, decimals) {
        return number;
    }
});

Vue.filter('percentage', {
    read:function (number, decimals) {
        return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
    },
    write: function(number, numberVal, decimals) {
        return number + '%';
    }
});

Vue.filter('moment', function (val, format) {
    return moment(val).format(format||'LL');
});

var VueCropOptions = {
    setSelect: [10, 10, 100, 100],
    aspectRatio: 1,
    bgColor: 'red',
    minSize: [100, 100]
};
var VueCropEvents = [
    'create',
    'start',
    'move',
    'end',
    'focus',
    'blur',
    'remove'
];

Vue.directive('crop', {

    acceptStatement: true,

    bind: function() {
        var event = this.arg;

        if ($.inArray(event, VueCropEvents) == -1) {
            console.warn('Invalid v-crop event: ' + event);
            return;
        }

        if (this.vm.jcrop) return;

        var $wrapper = $(this.el).wrap('<div/>').parent();
        $wrapper.width(this.el.width).height(this.el.height);
        this.vm.jcrop = $.Jcrop.attach($wrapper, VueCropOptions);
        // send api to active componant
        this.vm.$dispatch('vueCrop-api', this.vm.jcrop);
    },

    update: function(callback) {
        this.vm.jcrop.container.on('crop' + this.arg, callback)
    },

    unbind: function() {
        this.vm.jcrop.container.off('crop' + this.arg);

        if (this._watcher.id != 1) return;

        this.vm.jcrop.destroy();
        this.vm.jcrop = null
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
        groupProfileTrips,
        groupProfileStories,
        groupProfileFundraisers,
        groupTripWrapper,
        tripDetailsMissionaries,
        tripRegWizard,
        reservationsList,
        donationsList,
        topNav,
        actionTrigger,
        donate,

        //dashboard components
        recordsList,
        passportsList,
        passportCreateUpdate,
        visasList,
        groupsList,
        visaCreateUpdate,
        reservationsPassportsManager,
        reservationsVisasManager,
        userSettings,
        userProfileStories,
        userProfileFundraisers,

        // admin components
        adminCampaignCreate,
        adminCampaignEdit,
        adminCampaignDetails,
        adminCampaignTripCreate,
        adminCampaignTripEdit,
        adminTrips,
        adminTripsReservations,
        adminTripsFacilitators,
        adminTripsDuplicate,
        adminTripsDelete,
        adminGoups,
        adminGroupCreate,
        adminGroupEdit,
        adminGroupManagers,
        adminReservations,
        adminUsers,
        adminUserCreate,
        adminUserEdit,
        adminUserDelete,
        adminUploads,
        adminUploadCreateUpdate,
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
          this.user = user;
          this.authenticated = true;
        }
    }
});
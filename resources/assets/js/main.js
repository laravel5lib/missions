import Vue from 'vue';
import login from './components/login.vue';
import topNav from './components/top-nav.vue';
import actionTrigger from './components/action-trigger.vue';
import donate from './components/donate.vue';
import modalDonate from './components/modal-donate.vue';
import campaigns from './components/campaigns/campaigns.vue';
import groups from './components/groups/groups.vue';
import fundraisers from './components/fundraisers/fundraisers.vue';
import fundraisersManager from './components/fundraisers/fundraisers-manager.vue';
import fundraisersStories from './components/fundraisers/fundraisers-stories.vue';
import fundraisersUploads from './components/fundraisers/fundraisers-uploads.vue';
import campaignGroups from './components/campaigns/campaign-groups.vue';
import groupTrips from './components/campaigns/group-trips.vue';
import groupProfileTrips from './components/groups/group-profile-trips.vue';
import groupProfileStories from './components/groups/group-profile-stories.vue';
import groupTripWrapper from './components/campaigns/groups-trips-selection-wrapper.vue';
import groupInterestSignup from './components/groups/group-interest-signup.vue';
import tripDetailsMissionaries from './components/trips/trip-details-missionaries.vue';
import tripRegWizard from './components/trips/trip-registration-wizard.vue';
import reservationsList from './components/reservations/reservations-list.vue';
import donationsList from './components/reservations/donations-list.vue';
import recordsList from './components/records/records-list.vue';
import groupsList from './components/groups/groups-list.vue';
import visasList from './components/records/visas/visas-list.vue';
import medicalsList from './components/records/medicals/medicals-list.vue';
import passportsList from './components/records/passports/passports-list.vue';
import passportCreateUpdate from './components/records/passports/passport-create-update.vue';
import visaCreateUpdate from './components/records/visas/visa-create-update.vue';
import medicalCreateUpdate from './components/records/medicals/medical-create-update.vue';
import reservationAvatar from './components/reservations/reservation-avatar.vue';
import reservationCosts from './components/reservations/reservation-costs.vue';
import reservationDues from './components/reservations/reservation-dues.vue';
import reservationFunding from './components/reservations/reservation-funding.vue';
import reservationsPassportsManager from './components/reservations/reservations-passports-manager.vue';
import reservationsMedicalReleasesManager from './components/reservations/reservations-medical-releases-manager.vue';
import reservationsVisasManager from './components/reservations/reservations-visas-manager.vue';
import userSettings from './components/users/user-settings.vue';
import userProfileCountries from './components/users/user-profile-countries.vue';
import userProfileStories from './components/users/user-profile-stories.vue';
import userProfileFundraisers from './components/users/user-profile-fundraisers.vue';
import userProfileFundraisersDonors from './components/users/user-profile-fundraisers-donors.vue';
import userProfileFundraisersProgress from './components/users/user-profile-fundraisers-progress.vue';
import groupProfileFundraisers from './components/groups/group-profile-fundraisers.vue';
import dashboardGroupTrips from './components/groups/dashboard-group-trips.vue';
import dashboardGroupReservations from './components/groups/dashboard-group-reservations.vue';
import dashboardInterestsList from './components/interests/dashboard-interests-list.vue';
import notes from './components/notes.vue';
import todos from './components/todos.vue';
import userPermissions from './components/users/user-permissions.vue';

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
import adminInterestsList from './components/interests/admin-interests-list.vue';
import adminGoups from './components/groups/admin-groups-list.vue';
import adminGroupCreate from './components/groups/admin-group-create.vue';
import adminGroupEdit from './components/groups/admin-group-edit.vue';
import adminGroupManagers from './components/groups/admin-group-managers.vue';
import adminReservations from './components/reservations/admin-reservations-list.vue';
import adminReservationEdit from './components/reservations/admin-reservation-edit.vue';
import adminReservationCosts from './components/reservations/admin-reservation-costs.vue';
import adminReservationDues from './components/reservations/admin-reservation-dues.vue';
import adminReservationDeadlines from './components/reservations/admin-reservation-deadlines.vue';
import adminUsers from './components/users/admin-users-list.vue';
import adminUserCreate from './components/users/admin-user-create.vue';
import adminUserEdit from './components/users/admin-user-edit.vue';
import adminUserDelete from './components/users/admin-user-delete.vue';
import adminUploads from './components/uploads/admin-uploads-list.vue';
import adminUploadCreateUpdate from './components/uploads/admin-upload-create-update.vue';
import reconcileFund from './components/reconcile-fund.vue';
import projectCauses from './components/admin/project-causes.vue';
import causeEditor from './components/admin/cause-editor.vue';
import projectsList from './components/admin/projects-list.vue';
import projectTypes from './components/admin/project-types.vue';

// jQuery
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
window._ = require('underscore');
window.marked = require('marked');
require('gsap');
window.ScrollMagic = require('scrollmagic');
require('scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');
// require('vue-strap/src/index.js');
// window.VueStrap = require('vue-strap/dist/vue-strap.min');
import VueStrap from 'vue-strap/dist/vue-strap.min';
window.videojs = require('video.js');
require('videojs-youtube');
// require('videojs-vimeo');
require('jquery.cookie');
require('bootstrap-sass');
window.AOS = require('aos');
AOS.init();
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
            $.removeCookie('api_token');
            console.log('not logged in');
            // window.location.replace('/logout');
        }
        if (response.status && response.status === 500) {
            console.log('Oops! Something went wrong with the server.')
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
        fundraisers,
        campaigns,
        groups,
        campaignGroups,
        groupTrips,
        groupProfileTrips,
        groupProfileStories,
        groupProfileFundraisers,
        groupTripWrapper,
        groupInterestSignup,
        tripDetailsMissionaries,
        tripRegWizard,
        reservationsList,
        donationsList,
        fundraisersManager,
        fundraisersStories,
        fundraisersUploads,
        topNav,
        actionTrigger,
        donate,
        modalDonate,
        notes,
        todos,
        userPermissions,

        //dashboard components
        recordsList,
        passportsList,
        passportCreateUpdate,
        visasList,
        visaCreateUpdate,
        medicalsList,
        medicalCreateUpdate,
        groupsList,
        reservationAvatar,
        reservationCosts,
        reservationDues,
        reservationFunding,
        reservationsPassportsManager,
        reservationsMedicalReleasesManager,
        reservationsVisasManager,
        userSettings,
        userProfileCountries,
        userProfileStories,
        userProfileFundraisers,
        userProfileFundraisersDonors,
        userProfileFundraisersProgress,
        dashboardGroupTrips,
        dashboardGroupReservations,
        dashboardInterestsList,

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
        adminInterestsList,
        adminGoups,
        adminGroupCreate,
        adminGroupEdit,
        adminGroupManagers,
        adminReservations,
        adminReservationEdit,
        adminReservationCosts,
        adminReservationDues,
        adminReservationDeadlines,
        adminUsers,
        adminUserCreate,
        adminUserEdit,
        adminUserDelete,
        adminUploads,
        adminUploadCreateUpdate,
        reconcileFund,
        projectCauses,
        causeEditor,
        projectsList,
        projectTypes
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
        });

        // Track window resizing
        $(window).on('resize', function(){
            this.$emit('Window:resize');
        }.bind(this));
    },
    methods: {
        setUser: function (user) {
          // Save user info
          this.user = user;
          this.authenticated = true;
        }
    }
});
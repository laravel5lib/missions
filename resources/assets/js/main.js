import Vue from 'vue';
import contactForm from './components/contact-form.vue';
import speakerForm from './components/speaker-form.vue';
import sponsorProjectForm from './components/sponsor-project-form.vue';
import login from './components/login.vue';
import pagination from './components/pagination.vue';
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
import tripRegistrationWizard from './components/trips/trip-registration-wizard.vue';
import userProjectsList from './components/projects/user-projects-list.vue';
import reservationsList from './components/reservations/reservations-list.vue';
import donationsList from './components/reservations/donations-list.vue';
import recordsList from './components/records/records-list.vue';
import groupsList from './components/groups/groups-list.vue';
import visasList from './components/records/visas/visas-list.vue';
import medicalsList from './components/records/medicals/medicals-list.vue';
import passportsList from './components/records/passports/passports-list.vue';
import passportCreateUpdate from './components/records/passports/passport-create-update.vue';
import essaysList from './components/records/essays/essays-list.vue';
import referralsList from './components/records/referrals/referrals-list.vue';
import visaCreateUpdate from './components/records/visas/visa-create-update.vue';
import medicalCreateUpdate from './components/records/medicals/medical-create-update.vue';
import essayCreateUpdate from './components/records/essays/essay-create-update.vue';
import referralCreateUpdate from './components/records/referrals/referral-create-update.vue';
import reservationAvatar from './components/reservations/reservation-avatar.vue';
import reservationCosts from './components/reservations/reservation-costs.vue';
import reservationDues from './components/reservations/reservation-dues.vue';
import funding from './components/funding.vue';
import userSettings from './components/users/user-settings.vue';
import userProfileCountries from './components/users/user-profile-countries.vue';
import userProfileTripHistory from './components/users/user-profile-trip-history.vue';
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
import uploadCreateUpdate from './components/uploads/admin-upload-create-update.vue';
import reservationRequirements from './components/reservations/reservation-requirements.vue';
import referralResponse from './components/referrals/referral-response.vue';

// admin components
import campaignCreate from './components/campaigns/admin-campaign-create.vue';
import campaignEdit from './components/campaigns/admin-campaign-edit.vue';
import adminCampaignDetails from './components/campaigns/admin-campaign-details.vue';
import campaignTripCreateWizard from './components/trips/admin-trip-create.vue';
import campaignTripEditWizard from './components/trips/admin-trip-edit.vue';
import adminTrips from './components/trips/admin-trips-list.vue';
import adminTripReservationsList from './components/trips/admin-trip-reservations-list.vue';
import adminTripFacilitators from './components/trips/admin-trip-facilitators.vue';
import adminTripDuplicate from './components/trips/admin-trip-duplicate.vue';
import adminTripCreateUpdate from './components/trips/admin-trip-create-update.vue';
import adminTripDelete from './components/trips/admin-trip-delete.vue';
import costManager from './components/admin/cost-manager.vue';
import adminTripDescription from './components/trips/admin-trip-description.vue';
import deadlinesManager from './components/admin/deadlines-manager.vue';
import adminTripRequirements from './components/trips/admin-trip-requirements.vue';
import adminTripTodos from './components/trips/admin-trip-todos.vue';
import adminInterestsList from './components/interests/admin-interests-list.vue';
import adminGroups from './components/groups/admin-groups-list.vue';
import adminGroupCreate from './components/groups/admin-group-create.vue';
import adminGroupEdit from './components/groups/admin-group-edit.vue';
import adminGroupDelete from './components/groups/admin-group-delete.vue';
import adminGroupManagers from './components/groups/admin-group-managers.vue';
import adminReservationsList from './components/reservations/admin-reservations-list.vue';
import adminReservationCreate from './components/reservations/admin-reservation-create.vue';
import adminReservationEdit from './components/reservations/admin-reservation-edit.vue';
import adminReservationCosts from './components/reservations/admin-reservation-costs.vue';
import adminReservationDues from './components/reservations/admin-reservation-dues.vue';
import adminReservationDeadlines from './components/reservations/admin-reservation-deadlines.vue';
import adminUsersList from './components/users/admin-users-list.vue';
import adminUserCreate from './components/users/admin-user-create.vue';
import adminUserEdit from './components/users/admin-user-edit.vue';
import adminUserDelete from './components/users/admin-user-delete.vue';
import adminUploadsList from './components/uploads/admin-uploads-list.vue';
import adminUploadCreateUpdate from './components/uploads/admin-upload-create-update.vue';
import reconcileFund from './components/reconcile-fund.vue';
import projectCauses from './components/admin/project-causes.vue';
import causeEditor from './components/admin/cause-editor.vue';
import projectsList from './components/admin/projects-list.vue';
import projectEditor from './components/admin/project-editor.vue';
import projectCosts from './components/projects/project-costs.vue';
import projectDues from './components/projects/project-dues.vue';
import initiativesList from './components/admin/initiatives-list.vue';
import initiativeEditor from './components/admin/initiative-editor.vue';
import adminDonorsList from './components/financials/donors/admin-donors-list.vue';
import adminFundsList from './components/financials/funds/admin-funds-list.vue';
import adminTransactionsList from './components/financials/transactions/admin-transactions-list.vue';
import donorForm from './components/financials/donors/donor-form.vue';
import tripInterestEditor from './components/interests/trip-interests-editor.vue';
import refundForm from './components/financials/transactions/refund-form.vue';
import transactionDelete from './components/financials/transactions/transaction-delete.vue';
import fundManager from './components/financials/funds/fund-manager.vue';

// jQuery
window.$ = window.jQuery = require('jquery');
window.moment = require('moment');
window._ = require('underscore');
window.marked = require('marked');
require('gsap');
window.ScrollMagic = require('scrollmagic');
require('scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');
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

    $('[data-toggle="tooltip"]').tooltip();
});

// Global Components
import VueStrap from 'vue-strap/dist/vue-strap.min';
Vue.component('pagination', pagination);
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('aside', VueStrap.aside);
Vue.component('panel', VueStrap.panel);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabs);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);
// Vue.component('vSelect', require('vue-select'));
// import myDatepicker from 'vue-datepicker/vue-datepicker-1.vue'
import myDatepicker from './components/date-picker.vue'
Vue.component('date-picker', myDatepicker);

// Vue Cookie
Vue.use(require('vue-cookie'));
// Vue Resource
Vue.use(require('vue-resource'));
// Vue Validator
Vue.use(require('vue-validator'));
// Vue Textarea Autosize
var VueAutosize = require('vue-autosize');
Vue.use(VueAutosize);

Vue.http.options.root = '/api';
Vue.http.interceptors.push({

    request: function (request) {
        var token, headers;

        token = 'Bearer ' + $.cookie('api_token');

        headers = request.headers || (request.headers = {});

        if (token !== null && token !== 'undefined') {
            headers.Authorization = token
        }


        // Show Spinners in all components where they exist
        if (_.contains(['GET', 'POST', 'PUT'],request.method)) {
            // debugger;
            if (this.$refs.spinner) {
                switch (request.method) {
                    case 'GET':
                        this.$refs.spinner.show({text: 'Loading'});
                        break;
                    case 'POST':
                        this.$refs.spinner.show({text: 'Saving'});
                        break;
                    case 'PUT':
                        this.$refs.spinner.show({text: 'Updating'});
                        break;
                }
            }
        }

        // Only POST and PUT Requests to our API
        if (_.contains(['POST', 'PUT'],request.method) && request.root === '/api') {
            console.log(this);
            console.log(request);

            /*
             * Date Conversion: Local to UTC
             */
            // search nested objects/arrays for dates to convert
            // YYYY-MM-DD
            let dateRegex = /^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/;
            // YYYY-MM-DD HH:MM:SS
            let dateTimeRegex = /^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$/;
            searchObjAndConvertDates(request.data);

            function searchObjAndConvertDates(obj) {
                _.each(obj, function (value, key) {
                    // nested search
                    if (_.isObject(value) || _.isArray(value))
                        searchObjAndConvertDates(value);

                    let testDate = _.isString(value) && value.length === 10 && dateRegex.test(value);
                    let testDateTime = _.isString(value) && value.length === 19 && dateTimeRegex.test(value);


                    if (testDate) {
                        // console.log('then: ', value);
                        obj[key] = moment(value).startOf('day').utc().format('YYYY-MM-DD');
                        // console.log('now: ', value);
                    }

                    if (testDateTime) {
                        // console.log('then: ', value);
                        obj[key] = moment(value).utc().format('YYYY-MM-DD HH:mm:ss');
                        // console.log('now: ', value);
                    }


                });
            }
        }
        return request
    },

    response: function (response) {
        // Hide Spinners in all components where they exist
        if (this.$refs.spinner && !_.isUndefined(this.$refs.spinner._started)) {
            this.$refs.spinner.hide();
        }

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
// Validate datetime inputs
Vue.validator('datetime ', function (val) {
    return moment(val).isValid();
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

// MVC concept of handling dates
// Model/Server -> UTC | Vue Model/Controller -> UTC | View/Template -> Local
// This filter should convert date assigned property from UTC to local when being displayed -> read()
// This filter should convert date assigned property from Local to UTC when being changed via input -> writer5
Vue.filter('moment', {
    read: function(val, format, diff = false) {
        // console.log('before: ', val);
        var date = moment.utc(val).local().format(format||'LL');

        if(diff) {
            date = moment.utc(val).local().fromNow();
        }
        // console.log('after: ', date);

        return date;
    },
    write: function(val, oldVal) {
        let format = 'YYYY-MM-DD HH:mm:ss';
        // let format = val.length > 10 ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD';
        return   moment(val).local().utc().format(format);
    }
});

Vue.filter('mDateToDatetime', {
    read: function(value) {
        return moment.utc(value).local().format(format||'LL');
    },
    write: function(value, oldVal) {
        return moment(value).utc();
    }
});

Vue.filter('mUTC', {
    read: function(value) {
        return moment.utc(value);
    },
    write: function(value, oldVal) {
        return moment.utc(value);
    }
});

Vue.filter('mLocal', {
    read: function(value) {
        return moment.isMoment(value) ? value.local() : null;
    },
    write: function(value, oldVal) {
        return moment.isMoment(value) ? value.local() : null;
    }
});

Vue.filter('mFormat', {
    read: function(value, format) {
        return moment(value).format(format);
    },
    write: function(value, oldVal) {
        return value;
    }
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

Vue.mixin({
    methods: {
        /*showSpinner(){
            this.$refs.spinner.show();
        },
        hideSpinner(){
            this.$refs.spinner.hide();
        },*/
    },
    computed: {
        firstUrlSegment: function () {
            return document.location.pathname.split("/").slice(1,2).toString();
        }
    },
    ready() {
        function isTouchDevice(){
            return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
        }
        // Disable tooltips on all components on mobile
        if(isTouchDevice()) {
            $("[rel='tooltip']").tooltip('destroy');
        }

    }
});

new Vue({
    el: '#app',
    data: {
        datePickerSettings: {
            type: 'min',
            week: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
            month: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            format: 'YYYY-MM-DD HH:mm:ss',
            inputStyle: { width: '100%', border: 'none'},
            color: { header: '#F74451'}
        },
        showSuccess: false,
        showError: false,
        message: '',
    },
    computed: {
        impersonatedUser() {
            return JSON.parse(localStorage.getItem('impersonatedUser'));
        },
        impersonatedToken() {
            return this.$cookie.get('impersonate');
        },
        user() {
            return this.$cookie.get('impersonate') !== null ? this.getImpersonatedUser() : JSON.parse(localStorage.getItem('user'));
        },
    },
    components: {
        login,
        contactForm,
        speakerForm,
        sponsorProjectForm,
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
        tripRegistrationWizard,
        reservationsList,
        userProjectsList,
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
        uploadCreateUpdate,
        reservationRequirements,
        referralResponse,

        //dashboard components
        recordsList,
        passportsList,
        essaysList,
        referralsList,
        essayCreateUpdate,
        referralCreateUpdate,
        passportCreateUpdate,
        visasList,
        visaCreateUpdate,
        medicalsList,
        medicalCreateUpdate,
        groupsList,
        reservationAvatar,
        reservationCosts,
        reservationDues,
        funding,
        userSettings,
        userProfileCountries,
        userProfileTripHistory,
        userProfileStories,
        userProfileFundraisers,
        userProfileFundraisersDonors,
        userProfileFundraisersProgress,
        dashboardGroupTrips,
        dashboardGroupReservations,
        dashboardInterestsList,

        // admin components
        campaignCreate,
        campaignEdit,
        adminCampaignDetails,
        campaignTripCreateWizard,
        campaignTripEditWizard,
        adminTrips,
        adminTripCreateUpdate,
        adminTripReservationsList,
        adminTripFacilitators,
        adminTripDuplicate,
        adminTripDelete,
        costManager,
        adminTripDescription,
        deadlinesManager,
        adminTripRequirements,
        adminTripTodos,
        adminInterestsList,
        adminGroups,
        adminGroupCreate,
        adminGroupEdit,
        adminGroupDelete,
        adminGroupManagers,
        adminReservationsList,
        adminReservationCreate,
        adminReservationEdit,
        adminReservationCosts,
        adminReservationDues,
        adminReservationDeadlines,
        adminUsersList,
        adminUserCreate,
        adminUserEdit,
        adminUserDelete,
        adminUploadsList,
        adminUploadCreateUpdate,
        reconcileFund,
        projectCauses,
        causeEditor,
        projectsList,
        projectEditor,
        projectCosts,
        projectDues,
        initiativesList,
        initiativeEditor,
        adminDonorsList,
        adminFundsList,
        adminTransactionsList,
        donorForm,
        tripInterestEditor,
        refundForm,
        transactionDelete,
        fundManager
    },
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    ready: function() {
        // Track window resizing
        $(window).on('resize', function(){
            this.$emit('Window:resize');
        }.bind(this));

        this.$on('userHasLoggedIn', function (user) {
            this.user = user;
            this.authenticated = true;
        });

        this.$on('showSuccess', function (msg) {
            this.message = msg;
            this.showSuccess = true;
        });

        this.$on('showError', function (msg) {
            this.message = msg;
            this.showError = true;
        });

    },
    created () {
        // if api_token cookie doesn't exist user data will be cleared if they do exist
        if (this.$cookie.get('api_token') === null) {
            if (localStorage.hasOwnProperty('user'))
                localStorage.removeItem('user');
            if (localStorage.hasOwnProperty('impersonatedUser'))
                localStorage.removeItem('impersonatedUser');
        } else {
            // because user is computed, this must be called to initiate impersonation or normal user before anything else
            this.user;
        }
    },
    methods: {
        setUser: function (user) {
          // Save user info
          this.user = user;
          this.authenticated = true;
        },
        getImpersonatedUser: function () {
            if (this.impersonatedUser !== null) {
                console.log('impersonating: ', this.impersonatedUser.name);
                return this.impersonatedUser
            } else {
                return this.$http.get('users/' + this.impersonatedToken + '?include=roles,abilities')
                    .then(function (response) {
                        console.log('impersonating: ', response.data.data.name);
                        localStorage.setItem('impersonatedUser', JSON.stringify(response.data.data));
                        return this.impersonatedUser = response.data.data;
                    }.bind(this))
            }
        }
    },
    events: {
        'showSuccess': function (msg) {
            this.message = msg;
            this.showSuccess = true;
        },
        'showError': function (msg) {
            this.message = msg;
            this.showError = true;
        },
        'userHasLoggedIn': function (user) {
            localStorage.setItem('user', JSON.stringify(user));
        }
    }
});
// IE support for vue $cookie
Number.isInteger = Number.isInteger || function(value) {
    return typeof value === "number" && isFinite(value) && Math.floor(value) === value;
};

import Vue from 'vue';
import markdownExampleModal from './components/markdown-example-modal.vue';
import contactForm from './components/contact-form.vue';
import speakerForm from './components/speaker-form.vue';
import sponsorProjectForm from './components/sponsor-project-form.vue';
import login from './components/login.vue';
import pagination from './components/pagination.vue';
import topNav from './components/top-nav.vue';
import actionTrigger from './components/action-trigger.vue';
import actionDropdownSelect from './components/action-dropdown-select.vue';
import actionSelect from './components/action-select.vue';
import listenText from './components/listen-text.vue';
import donate from './components/donate.vue';
import modalDonate from './components/modal-donate.vue';
import campaigns from './components/campaigns/campaigns.vue';
import groups from './components/groups/groups.vue';
import fundraisers from './components/fundraisers/fundraisers.vue';
import fundraisersManager from './components/fundraisers/fundraisers-manager.vue';
import fundraisersStories from './components/fundraisers/fundraisers-stories.vue';
import fundraisersUploads from './components/fundraisers/fundraisers-uploads.vue';
import fundraiserCollection from './components/fundraisers/fundraiser-collection.vue';
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
import restoreReservation from './components/reservations/restore-reservation.vue';
import transferReservation from './components/reservations/transfer-reservation.vue';
import donationsList from './components/reservations/donations-list.vue';
import recordsList from './components/records/records-list.vue';
import groupsList from './components/groups/groups-list.vue';
import visasList from './components/records/visas/visas-list.vue';
import medicalsList from './components/records/medicals/medicals-list.vue';
import medicalCredentialsList from './components/records/credentials/medical-credentials-list.vue';
import mediaCredentialsList from './components/records/credentials/media-credentials-list.vue';
import passportsList from './components/records/passports/passports-list.vue';
import passportCreateUpdate from './components/records/passports/passport-create-update.vue';
import essaysList from './components/records/essays/essays-list.vue';
import influencerQuestionnairesList from './components/records/influencers/influencer-questionnaires-list.vue';
import referralsList from './components/records/referrals/referrals-list.vue';
import visaCreateUpdate from './components/records/visas/visa-create-update.vue';
import medicalCreateUpdate from './components/records/medicals/medical-create-update.vue';
import medicalCredentialCreateUpdate from './components/records/credentials/medical-credential-create-update.vue';
import mediaCredentialCreateUpdate from './components/records/credentials/media-credential-create-update.vue';
import essayCreateUpdate from './components/records/essays/essay-create-update.vue';
import influencerQuestionnaireCreateUpdate from './components/records/influencers/influencer-questionnaire-create-update.vue';
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
import sendEmail from './components/send-email.vue';
import reportsList from './components/reports/reports-list.vue';
import teamManager from './components/teams/team-manager.vue';
import roomingWizard from './components/rooms/rooming-wizard.vue';
import roomingTypeManager from './components/rooms/rooming-type-manager.vue';
import teamTypeManager from './components/teams/team-type-manager.vue';
import regionsManager from './components/regions/regions-manager.vue';

// admin components
import campaignCreate from './components/campaigns/admin-campaign-create.vue';
import campaignEdit from './components/campaigns/admin-campaign-edit.vue';
import adminCampaignsList from './components/campaigns/admin-campaigns-list.vue';
import adminCampaignDetails from './components/campaigns/admin-campaign-details.vue';
import visibilityControls from './components/campaigns/visibility-controls.vue';
import campaignTripCreateWizard from './components/trips/admin-trip-create.vue';
import campaignTripEditWizard from './components/trips/admin-trip-edit.vue';
import adminCampaignTrips from './components/campaigns/admin-campaign-trips.vue';
import adminGroupTrips from './components/groups/admin-group-trips-list.vue';
import adminTripReservations from './components/trips/admin-trip-reservations-list.vue';
import adminTripFacilitators from './components/trips/admin-trip-facilitators.vue';
import adminTripDuplicate from './components/trips/admin-trip-duplicate.vue';
import adminTripCreateUpdate from './components/trips/admin-trip-create-update.vue';
import adminDeleteModal from './components/admin-delete-modal.vue';
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
import adminProjectCosts from './components/admin/admin-project-costs.vue';
import adminProjectDues from './components/admin/admin-project-dues.vue';
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
import companionManager from './components/reservations/companion-manager.vue';
import promotionals from './components/admin/promotionals.vue';
import transports from './components/admin/transports.vue';
import transportsDetails from './components/admin/transports-details.vue';
import roomingAccommodations from './components/rooms/rooming-accommodations.vue';
import regionsAccommodations from './components/regions/regions-accommodations.vue';
import restoreFund from './components/financials/funds/restore-fund.vue';

// filter components
import reservationsFilters from './components/filters/reservations-filters.vue';

// jQuery
window.$ = window.jQuery = require('jquery');
// import slim from './vendors/slim.module.js';
window.Slim = require('./vendors/slim.commonjs.js');
window.moment = require('moment');
window.timezone = require('moment-timezone');
window._ = require('underscore');
window.marked = require('marked');
require('gsap');
window.ScrollMagic = require('scrollmagic');
require('scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap');
require('scrollmagic/scrollmagic/uncompressed/plugins/debug.addIndicators');
window.videojs = require('video.js');
require('videojs-youtube');
// require('videojs-vimeo');
require('jquery.cookie');
require('bootstrap-sass');
// require('tether');
window.Shepherd = require('tether-shepherd');
require('eonasdan-bootstrap-datetimepicker');

window.AOS = require('aos');
AOS.init();
$(document).ready(function () {
    console.log($.fn.popover.Constructor.VERSION);
    console.log($.fn.jquery);
    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });

    $('[data-toggle="tooltip"]').tooltip();
});

// Global Components
import VueStrap from 'vue-strap/dist/vue-strap.min';
// Vue.component('tour-guide', tourGuide);
Vue.component('pagination', pagination);
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('aside', VueStrap.aside);
Vue.component('button-group', VueStrap.buttonGroup);
Vue.component('panel', VueStrap.panel);
Vue.component('radio', VueStrap.radio);
Vue.component('checkbox', VueStrap.checkbox);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('dropdown', VueStrap.dropdown);
Vue.component('strap-select', VueStrap.select);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabset);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);
// Vue.component('vSelect', require('vue-select'));
// import myDatepicker from 'vue-datepicker/vue-datepicker-1.vue'
// import myDatepicker from './components/date-picker.vue'
import myDatepicker from './components/date-picker.vue'
Vue.component('date-picker', myDatepicker);
// Vue.component('date-picker', require('vue-datetime-picker/src/vue-datetime-picker.js'));
Vue.component('bootstrap-alert-error', {
    props: ['field', 'validator', 'message'],
    template: '<div><div class="alert alert-danger alert-dismissible error-{{field}}-{{validator}}" role="alert">{{ message }} </div></div>',
});
// Vue Cookie
Vue.use(require('vue-cookie'));
// Vue Resource
Vue.use(require('vue-resource'));
// Vue Validator
Vue.use(require('vue-validator'));
// Vue Textarea Autosize
var VueAutosize = require('vue-autosize');
Vue.use(VueAutosize);
// Vue Truncate
Vue.use(require('vue-truncate'));

Vue.http.options.root = '/api';
Vue.http.interceptors.push(function(request, next) {

    // modify request
    let token, headers;

    token = $.cookie('api_token') ? $.cookie('api_token').indexOf('Bearer') !== -1 ? $.cookie('api_token') : 'Bearer ' + $.cookie('api_token') : null;

    headers = request.headers || (request.headers = {});

    if (token !== null && token !== 'undefined') {
        headers.set('Authorization', token);
    }


    // Show Spinners in all components where they exist
    if (_.contains(['GET', 'POST', 'PUT'], request.method.toUpperCase())) {
        if (this.$refs.spinner && !request.params.hideLoader) {
            switch (request.method.toUpperCase()) {
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
    //if (_.contains(['POST', 'PUT'], request.method) && request.root === '/api') {
        // console.log(this);
        // console.log(request);

        /*
         * Date Conversion: Local to UTC
         */
        // search nested objects/arrays for dates to convert
        // YYYY-MM-DD
        //let dateRegex = /^\d{4}\-(0?[1-9]|1[012])\-(0?[1-9]|[12][0-9]|3[01])$/;
        // YYYY-MM-DD HH:MM:SS
        //let dateTimeRegex = /^\d\d\d\d-(0?[1-9]|1[0-2])-(0?[1-9]|[12][0-9]|3[01]) (00|[0-9]|1[0-9]|2[0-3]):([0-9]|[0-5][0-9]):([0-9]|[0-5][0-9])$/;
    //}

    // continue to next interceptor
    next(function(response) {

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
        if (response.headers && response.headers.has('Authorization')) {
            $.cookie('api_token', response.headers.get('Authorization'));
        }
        if (response.data && response.data.token && response.data.token.length > 10) {
            $.cookie('api_token', response.data.token);
        }

    });
});


// Register email validator function.
Vue.validator('email', function (val) {
    if (! val) return true;

    return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(val)
});
// Validate datetime inputs
Vue.validator('datetime ', function (val) {
    if (! val) return true;
    if (val === 'Invalid date') return false;

    return moment(val).isValid();
});

Vue.filter('phone', {
    read: function (phone) {
        phone = phone || '';
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    },
    write: function (phone, phoneVal) {
        phone = phone || '';
        return phone.replace(/[^0-9]/g, '')
            .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
    }
});

Vue.filter('number', {
    read: function (number, decimals) {
        return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
    },
    write: function (number, numberVal, decimals) {
        return number;
    }
});

Vue.filter('percentage', {
    read: function (number, decimals) {
        return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
    },
    write: function (number, numberVal, decimals) {
        return number + '%';
    }
});

// MVC concept of handling dates
// Model/Server -> UTC | Vue Model/Controller -> UTC | View/Template -> Local
// This filter should convert date assigned property from UTC to local when being displayed -> read()
// This filter should convert date assigned property from Local to UTC when being changed via input -> writer5
Vue.filter('moment', {
    read: function (val, format, diff = false, noLocal = false) {

        if (!val) return val;

        if (noLocal) {
            return moment(val).format(format || 'LL'); // do not convert to local
        }

        // console.log('before: ', val);
        let date = moment.utc(val, 'YYYY-MM-DD HH:mm:ss').local().format(format || 'LL');

        if (diff) {
            date = moment.utc(val).local().fromNow();
        }
        // console.log('after: ', date);

        return date;
    },
    write: function (val, oldVal, format = 'YYYY-MM-DD HH:mm:ss', diff = false, noLocal = false) {
        // let format = 'YYYY-MM-DD HH:mm:ss';
        // let format = val.length > 10 ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD';
        if (!val) return val;

        if (noLocal) {
            // interpret as still UTC
            // format does not need to change (obviously...)
            // console.log('Date: ', val);
            //return val;
            // console.log(moment.utc(val).format(format));
            return moment(val).format(format || 'LL'); // do not convert to local
        }

        return moment(val, 'YYYY-MM-DD HH:mm:ss').local().utc().format(format);
    }
});

Vue.filter('mDateToDatetime', {
    read: function (value) {
        return moment.utc(value).local().format(format || 'LL');
    },
    write: function (value, oldVal) {
        return moment(value).utc();
    }
});

Vue.filter('mUTC', {
    read: function (value) {
        return moment.utc(value);
    },
    write: function (value, oldVal) {
        return moment.utc(value);
    }
});

Vue.filter('mLocal', {
    read: function (value) {
        return moment.isMoment(value) ? value.local() : null;
    },
    write: function (value, oldVal) {
        return moment.isMoment(value) ? value.local() : null;
    }
});

Vue.filter('mFormat', {
    read: function (value, format) {
        return moment(value).format(format);
    },
    write: function (value, oldVal) {
        return value;
    }
});

Vue.filter('underscoreToSpace', function (value) {
    return value.replace(/_/g, ' ');
});

Vue.directive('tour-guide', {
    bind: function () {

        this.topScrollHandler = function(element){
            if (element) {
                var $element = window.jQuery(element);
                var topOfElement = $element.offset().top;
                var heightOfElement = $element.height();
                window.jQuery('html, body').animate({
                    scrollTop: topOfElement - heightOfElement
                }, {
                    duration: 1000
                });
            }
        };

        this.tour = window.tour = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-element shepherd-open shepherd-theme-arrows step-class',
                scrollTo: true,
                scrollToHandler: this.topScrollHandler,
                showCancelLink: true
            }
        });

        this.tour.addStep('intro', {
            title: 'Hello!',
            text: 'This guided tour will walk you through the features on this page. Take this tour anytime by clicking the <i class="fa fa-question-circle-o"></i> Tour link. Shall we begin?',
            showCancelLink: false,
            buttons: [
                {
                    text: 'Not Now',
                    action: this.tour.cancel,
                    classes: 'shepherd-button-secondary'
                },
                {
                    text: 'Continue',
                    action: this.tour.next
                }
            ]
        });

        // if pageSteps exists, add them to tour
        if (window.pageSteps && window.pageSteps.length) {
            _.each(window.pageSteps, function (step) {
                // if buttons are present
                if (step.buttons) {
                    _.each(step.buttons, function (button) {
                        // if action is present
                        if (button.action && _.isString(button.action))
                            button['action'] = this.tour[button.action];
                    }.bind(this));
                }
                tour.addStep(step);
            }.bind(this))
        }

        // Initialize the tour
        let completed = JSON.parse(localStorage.getItem('ToursCompleted')) || [];
        if (!_.contains(completed, location.pathname)) {
            this.tour.start();
        }

        this.tour.on('cancel', function () {
            this.storeTourRecord();
        }.bind(this));

        this.tour.on('complete', function () {
            this.storeTourRecord();
        }.bind(this));

        this.storeTourRecord = function () {
            let completed = JSON.parse(localStorage.getItem('ToursCompleted')) || [];
            completed.push(location.pathname);
            localStorage.setItem('ToursCompleted', JSON.stringify(_.uniq(completed)));
        }
    },
    update: function () {
        // debugger
    },
    unbind: function () {
    }
});

Vue.directive('error-handler', {
    // This directive handles client-side and server-side errors.
    // It expects an object expression with three values:  { value: fieldValue, client: 'clientSideField', server: 'serverSideField' }

    // PROPERTIES
    // The `value` property expects the actual field value to stay reactive.
    // The `client` property expects the handle that the validator plugin uses for validation.
    // The `server` property expects the handle that the server request rules use for validation.
    // If the `handle` property is present, client and server properties will be set to this.
    // OPTIONAL PROPERTIES
    // The `class` property allows you to set the error class to be set during validation.
    // The `messages` property allows you to customize the error message the user sees based on validators
    // i.e (`req` - required, `min` - minlength, `max` - maxlength, `email` - custom email validator)

    // When server-side validation errors are returned to the `this.errors` object, this hand;e references the property
    // for the field
    deep: true,
    bind: function () {
        this.vm.$on('attemptSubmit', function (val) {
            // The `attemptSubmit` variable delays validation until necessary, because this doesn't directly influence
            // the directive we want to watch it using the error-handler mixin
            this.handleClass(this.storage);
        }.bind(this));

        this.vm.$on('errors', function (val) {
            // The `attemptSubmit` variable delays validation until necessary, because this doesn't directly influence
            // the directive we want to watch it using the error-handler mixin
            this.handleClass(this.storage);
            this.handleMessages(this.storage, val);
        }.bind(this));

        this.handleClass = function (value) {
            // $nextTick is necessary for the component values to update first
            if (this.vm) {
                this.vm.$nextTick(function () {
                    // debugger;
                    let classTest = this.vm.checkForError(value.client) || this.vm.errors[value.server];
                    if (classTest) {
                        $(this.el).addClass(value.class || 'has-error');
                    } else {
                        $(this.el).removeClass(value.class || 'has-error');
                    }
                }.bind(this));
            }
        };

        this.messages = [];
        this.handleMessages = function (value, errors) {
            // $nextTick is necessary for the component values to update first
            if (this.vm && this.vm.attemptSubmit) {
                this.vm.$nextTick(function () {
                    //if (errors[value.server] || this.vm['$' + this.vm.validatorHandle][this.storage.client].invalid && this.vm.attemptSubmit) {
                        // Lets first package errors to simply iterate
                        let newMessages = [];
                    if (errors[value.server])
                        _.each(errors[value.server], function(error, index) {
                            newMessages.push("<div class='help-block server-validation-error'>" + error + "</div>");
                        });

                    let genericMessage = this.vm.MESSAGES.DEFAULT;
                    let validationObject = this.vm['$' + this.vm.validatorHandle][this.storage.client];
                    if (_.isObject(validationObject)) {
                        if (validationObject.required) {
                            // Grab message from storage if it exists or use generic default
                            // The manually set messages must be an object
                            let reqMessage;
                            if (this.storage.messages && this.storage.messages.req) {
                                reqMessage = this.storage.messages.req;
                            } else {
                                genericMessage = this.vm.MESSAGES[this.storage.client] || genericMessage;
                                reqMessage = _.isObject(genericMessage) ? genericMessage.req : genericMessage;
                            }

                            newMessages.push("<div class='help-block server-validation-error'>" + reqMessage + "</div>");
                        }

                        if (validationObject.minlength) {
                            // Grab message from storage if it exists or use generic default
                            let minMessage;
                            if (this.storage.messages && this.storage.messages.min) {
                                minMessage = this.storage.messages.min;
                            } else {
                                genericMessage = this.vm.MESSAGES[this.storage.client] || genericMessage;
                                minMessage = _.isObject(genericMessage) ? genericMessage.min : genericMessage;
                            }
                            if (minMessage !== genericMessage)
                                newMessages.push("<div class='help-block server-validation-error'>" + minMessage + "</div>");
                        }

                        if (validationObject.maxlength) {
                            // Grab message from storage if it exists or use generic default
                            let maxMessage;
                            if (this.storage.messages && this.storage.messages.max) {
                                maxMessage = this.storage.messages.max;
                            } else {
                                genericMessage = this.vm.MESSAGES[this.storage.client] || genericMessage;
                                maxMessage = _.isObject(genericMessage) ? genericMessage.max : genericMessage;
                            }
                            if (maxMessage !== genericMessage)
                                newMessages.push("<div class='help-block server-validation-error'>" + maxMessage + "</div>");
                        }
                        // custom email validator
                        if (validationObject.email) {
                            // Grab message from storage if it exists or use generic default
                            let emailMessage;
                            if (this.storage.messages && this.storage.messages.email) {
                                emailMessage = this.storage.messages.email;
                            } else {
                                genericMessage = this.vm.MESSAGES[this.storage.client] || genericMessage;
                                emailMessage = _.isObject(genericMessage) ? genericMessage.email : genericMessage;
                            }
                            if (emailMessage !== genericMessage)
                                newMessages.push("<div class='help-block server-validation-error'>" + emailMessage + "</div>");
                        }
                        // custom datetime validator
                        if (validationObject.datetime) {
                            // Grab message from storage if it exists or use generic default
                            let datetimeMessage;
                            if (this.storage.messages && this.storage.messages.datetime) {
                                datetimeMessage = this.storage.messages.datetime;
                            } else {
                                genericMessage = this.vm.MESSAGES[this.storage.client] || genericMessage;
                                datetimeMessage = _.isObject(genericMessage) ? genericMessage.email : genericMessage;
                            }
                            if (datetimeMessage !== genericMessage)
                                newMessages.push("<div class='help-block server-validation-error'>" + datetimeMessage + "</div>");
                        }
                    }
                    //console.log(newMessages);

                    this.messages = newMessages;

                    // We want to keep track of listed errors and specify their location when displayed
                    // Search for an '.errors-block' element as child to display messages in
                    // If it does not exist we will place the error message after the input element

                    let errorsBlock = this.el.getElementsByClassName('errors-block')[0] || false;
                    if (errorsBlock) {
                        $(errorsBlock).find('.server-validation-error').remove();
                        if (errors[value.server] || this.storage.client && this.vm.checkForError(this.storage.client))
                            $(errorsBlock).append(this.messages);
                    } else {
                        let inputGroup = $(this.el).hasClass('input-group') ? this.el : this.el.getElementsByClassName('input-group')[0];
                        let inputEl = $(this.el).find('.form-control:not(.v-select *)');
                        if (inputGroup) {
                            $(this.el).parent().find('.server-validation-error').remove();
                            if (errors[value.server] || this.storage.client && this.vm.checkForError(this.storage.client))
                                $(inputGroup).after(this.messages);
                        } else {
                            $(this.el).find('.server-validation-error').remove();
                            if (errors[value.server] || this.storage.client && this.vm.checkForError(this.storage.client))
                                inputEl.after(this.messages);
                        }
                    }
                    //}
                }.bind(this));
            }
        }
    },
    update: function (value) {
        // If server value is identical to client, use 'handle' property for simplicity
        if(value.handle) {
            value.client = value.server = value.handle;
        }

        // Store the value within the directive to be used outside the update function
        this.storage = value;

        // Handle error class and messages on element
        this.handleClass(value);
        this.handleMessages(value, this.vm.errors);
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
            return document.location.pathname.split("/").slice(1, 2).toString();
        },
        isAdminRoute() {
            return this.firstUrlSegment == 'admin';
        },
        isDashboardRoute() {
            return this.firstUrlSegment == 'dashboard';
        },
    },
    ready() {
        function isTouchDevice() {
            return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
        }

        // Disable tooltips on all components on mobile
        if (isTouchDevice()) {
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
            inputStyle: {width: '100%', border: 'none'},
            color: {header: '#F74451'}
        },
        showSuccess: false,
        showInfo: false,
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
            return this.$cookie.get('impersonate') !== null ? this.getImpersonatedUser() : this.fetchUser();
        },
    },
    components: {
        login,
        markdownExampleModal,
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
        fundraiserCollection,
        topNav,
        actionTrigger,
        actionDropdownSelect,
        actionSelect,
        listenText,
        donate,
        modalDonate,
        notes,
        todos,
        userPermissions,
        uploadCreateUpdate,
        reservationRequirements,
        referralResponse,
        sendEmail,
        restoreReservation,
        transferReservation,
        restoreFund,

        //dashboard components
        recordsList,
        passportsList,
        essaysList,
        influencerQuestionnairesList,
        referralsList,
        essayCreateUpdate,
        influencerQuestionnaireCreateUpdate,
        referralCreateUpdate,
        passportCreateUpdate,
        visasList,
        visaCreateUpdate,
        medicalsList,
        medicalCredentialsList,
        mediaCredentialsList,
        medicalCreateUpdate,
        medicalCredentialCreateUpdate,
        mediaCredentialCreateUpdate,
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
        teamManager,
        teamTypeManager,
        regionsManager,
        regionsAccommodations,
        reportsList,
        roomingWizard,
        roomingTypeManager,

        // admin components
        campaignCreate,
        campaignEdit,
        adminCampaignsList,
        adminCampaignDetails,
        visibilityControls,
        campaignTripCreateWizard,
        campaignTripEditWizard,
        adminCampaignTrips,
        adminGroupTrips,
        adminTripCreateUpdate,
        adminTripReservations,
        adminTripFacilitators,
        adminTripDuplicate,
        adminDeleteModal,
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
        adminProjectCosts,
        adminProjectDues,
        initiativesList,
        initiativeEditor,
        adminDonorsList,
        adminFundsList,
        adminTransactionsList,
        donorForm,
        tripInterestEditor,
        refundForm,
        transactionDelete,
        fundManager,
        companionManager,
        promotionals,
        transports,
        transportsDetails,
        roomingAccommodations,

        reservationsFilters,
    },
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    ready: function () {
        // Track window resizing
        $(window).on('resize', function () {
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

        this.$on('showInfo', function (msg) {
            this.message = msg;
            this.showInfo = true;
        });

        this.$on('showError', function (msg) {
            this.message = msg;
            this.showError = true;
        });

        // check if impersonated data is no longer needed
        if (this.$cookie.get('impersonate') === null)
            localStorage.removeItem('impersonatedUser');

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
        fetchUser() {
            if (localStorage.hasOwnProperty('user')) {
                return JSON.parse(localStorage.getItem('user'))
            } else {
                let that = this;
                this.$http.get('users/me?include=roles,abilities')
                    .then(function (response) {
                            that.$root.$emit('userHasLoggedIn', response.body.data);
                            return response.body.data;
                        },
                        function (response) {
                            if (this.isAdminRoute || this.isDashboardRoute)
                                window.location = '/logout';
                        });
            }
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
        },
        hasAbility(ability) {
            let abilities = _.pluck(this.user.abilities.data, 'name');
            return this.user ? _.contains(abilities, ability) : false;
        },

        startTour(){
            window.tour.start();
        },

        // Simple error handlers for API calls
        handleApiSoftError(response) {
            console.error(response.body.message ? response.body.message : response.body);
        },
        handleApiError(response) {
            console.error(response.body.message ? response.body.message : response.body);
            this.$root.$emit('showError', response.body.message)
        },
        convertSearchToObject() {
            let search = location.search.substring(1);
            return search ? JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}',
                function (key, value) {
                    return key === "" ? value : decodeURIComponent(value)
                }) : {};
        }

    },
    events: {
        'showSuccess': function (msg) {
            this.message = msg;
            this.showError = false;
            this.showInfo = false;
            this.showSuccess = true;
        },
        'showInfo': function (msg) {
            this.message = msg;
            this.showError = false;
            this.showInfo = true;
            this.showSuccess = false;
        },
        'showError': function (msg) {
            this.message = msg;
            this.showInfo = false;
            this.showSuccess = false;
            this.showError = true;
        },
        'userHasLoggedIn': function (user) {
            localStorage.setItem('user', JSON.stringify(user));
        }
    }
});
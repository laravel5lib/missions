/**
 * Created by jerezb on 2017-02-23.
 */
let $, jQuery, moment, timezone;
$ = jQuery = window.$;
moment = window.moment = require('moment');
timezone = window.timezone = require('moment-timezone');
require('eonasdan-bootstrap-datetimepicker');

let localStorage = window.localStorage;

import _ from 'underscore';
import Vue from 'vue';

Vue.use(require('vue-cookie'));
Vue.use(require('vue-resource'));
import VueResourceMock from 'vue-resource-mock-api'
import MockData from './mock-api'
Vue.use(VueResourceMock, { routes: MockData, matchOptions: {
    segmentValueCharset: 'a-zA-Z0-9.,-_%',
}});
Vue.http.options.root = '/api';
Vue.http.interceptors.push(function(request, next) {

    // modify request
    var token, headers;

    token = 'Bearer ' + $.cookie('api_token');

    headers = request.headers || (request.headers = {});

    if (token !== null && token !== 'undefined') {
        headers.set('Authorization', token);
    }

    // Only POST and PUT Requests to our API
    if (_.contains(['POST', 'PUT'], request.method) && request.root === '/api') {
        // console.log(this);
        // console.log(request);

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

// Global Components
import VueStrap from 'vue-strap/dist/vue-strap.min';
// Vue.component('tour-guide', tourGuide);
import pagination from '../components/pagination.vue';
Vue.component('pagination', pagination);
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('aside', VueStrap.aside);
Vue.component('panel', VueStrap.panel);
Vue.component('checkbox', VueStrap.checkbox);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabset);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);
// Vue.component('vSelect', require('vue-select'));
// import myDatepicker from 'vue-datepicker/vue-datepicker-1.vue'
import myDatepicker from '../components/date-picker.vue'
Vue.component('date-picker', myDatepicker);

// Vue Validator
Vue.use(require('vue-validator'));

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

Vue.filter('moment', {
    read: function (val, format, diff = false, noLocal = false) {

        if (noLocal) {
            return moment(val).format(format || 'LL'); // do not convert to local
        }

        // console.log('before: ', val);
        var date = moment.utc(val).local().format(format || 'LL');

        if (diff) {
            date = moment.utc(val).local().fromNow();
        }
        // console.log('after: ', date);

        return date;
    },
    write: function (val, oldVal) {
        let format = 'YYYY-MM-DD HH:mm:ss';
        // let format = val.length > 10 ? 'YYYY-MM-DD HH:mm:ss' : 'YYYY-MM-DD';
        return moment(val).local().utc().format(format);
    }
});

let RootInstance = {
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    data: {
        userId: '',
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
        },
        hasAbility(ability) {
            let abilities = _.pluck(this.user.abilities.data, 'name');
            return this.user ? _.contains(abilities, ability) : false;
        },

        startTour(){
            window.tour.start();
        },

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
};

export { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage };

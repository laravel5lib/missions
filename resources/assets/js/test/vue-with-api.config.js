/**
 * Created by jerezb on 2017-02-23.
 */
let $, jQuery, moment, timezone;
$ = jQuery = window.$;
window.moment = require('moment');
window.timezone = require('moment-timezone');
let localStorage = window.localStorage;

import _ from 'underscore';
import Vue from 'vue';

Vue.use(require('vue-cookie'));
Vue.use(require('vue-resource'));
import VueResourceMock from 'vue-resource-mock-api'
import MockData from './mock-api'
Vue.use(VueResourceMock, { routes: MockData, matchOptions: { segmentValueCharset: 'a-zA-Z0-9.,:-_%' }});
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

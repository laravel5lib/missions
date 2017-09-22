/**
 * Created by jerezb on 2017-02-23.
 */
let $, jQuery, moment, timezone;
$ = jQuery = window.$;
moment = window.moment = require('moment');
timezone = window.timezone = require('moment-timezone');
_ = window._ = require('underscore');
window.marked = require('marked');
window.Slim = require('../vendors/slim.commonjs.js');

let localStorage = window.localStorage;

import _ from 'underscore';
import Vue from 'vue';
import pagination from '../components/pagination.vue';
import reservationsFilters from '../components/filters/reservations-filters.vue';

Vue.use(require('vue-cookie'));
Vue.use(require('vue-resource'));
import VueResourceMock from 'vue-resource-mock-api'
import MockData from './mock-api'
Vue.use(VueResourceMock, { routes: MockData, matchOptions: {
    segmentValueCharset: 'a-zA-Z0-9.,-_%',
}});

// Global Components
import VueStrap from 'vue-strap/dist/vue-strap';
// Vue.component('tour-guide', tourGuide);
Vue.component('pagination', pagination);
Vue.component('modal', VueStrap.modal);
Vue.component('accordion', VueStrap.accordion);
Vue.component('alert', VueStrap.alert);
Vue.component('mm-aside', VueStrap.aside);
Vue.component('button-group', VueStrap.buttonGroup);
Vue.component('panel', VueStrap.panel);
Vue.component('radio', VueStrap.radio);
Vue.component('checkbox', VueStrap.checkbox);
Vue.component('progressbar', VueStrap.progressbar);
Vue.component('dropdown', require('../components/dropdown.vue'));
Vue.component('strap-select', VueStrap.select);
Vue.component('spinner', VueStrap.spinner);
Vue.component('popover', VueStrap.popover);
Vue.component('tabs', VueStrap.tabset);
Vue.component('tab', VueStrap.tab);
Vue.component('tooltip', VueStrap.tooltip);
Vue.component('vSelect', require('vue-select'));
Vue.component('phone-input', {
    template: '<div><label for="infoPhone" v-if="label" v-text="label"></label><input ref="input" type="text" id="infoPhone" class="form-control" :value="value" @input="updateValue($event.target.value)" @focus="selectAll" @blur="formatValue" :placeholder="placeholder"></div>',
    props: {
        value: { type: String, default: '' },
        label: { type: String, default: '' },
        placeholder: { type: String, default: '(123) 456-7890' },

    },
    methods: {
        updateValue(value) {
            if (value === '') {
                this.$refs.input.value = value;
            } else {
                this.$refs.input.value = value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            }
            this.$emit('input', this.$refs.input.value);
        },
        formatValue() {
            if (this.value === '') {
                this.$refs.input.value = this.value;
            } else {
                this.$refs.input.value = this.value.replace(/[^0-9]/g, '').replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            }
        },
        selectAll(event) {
            // Workaround for Safari bug
            // http://stackoverflow.com/questions/1269722/selecting-text-on-focus-using-jquery-not-working-in-safari-and-chrome
            setTimeout(function () {
                event.target.select()
            }, 0)
        }
    }
});
// import myDatepicker from './components/date-picker.vue'
import myDatepicker from '../components/date-picker.vue'
Vue.component('date-picker', myDatepicker);
// Vue.component('date-picker', require('vue-datetime-picker/src/vue-datetime-picker.js'));
Vue.component('bootstrap-alert-error', {
    props: ['field', 'validator', 'message'],
    template: '<div><div :class="\'alert alert-danger alert-dismissible error-\' + field + \'-\' + validator" role="alert" v-bind="message"></div></div>',
});
// Vue Cookie
Vue.use(require('vue-cookie'));
// Vue Validator
Vue.use(require('vee-validate'));
// Vue Textarea Autosize
Vue.use(require('vue-autosize'));
// Vue Truncate
Vue.use(require('vue-truncate'));

import axios from 'axios';
axios.defaults.baseURL = '/api';
axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
};
/**
 * We will register the CSRF Token as a common header with Axios so that all outgoing HTTP requests automatically have
 * it attached. This is just a simple convenience so we don't have to attach every token manually.
 */
let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}
Vue.prototype.$http = axios;

// Resource duplicate of vue-resource for axios
// Inspired by https://github.com/pagekit/vue-resource/blob/develop/src/resource.js
let URI = require('urijs');
let URITemplate = require('urijs/src/URITemplate');
function Resource (url, params, actions, options) {
    let resource = {};
    actions = Object.assign({},
        Resource.actions,
        actions
    );

    _.each(actions, (action, name) => {
        action = merge({url, params: Object.assign({}, params)}, options, action);

        resource[name] = function () {
            if (options)
                debugger;
            let args = opts(action, arguments);
            return (Vue.prototype.$http)(args);
        };
    });

    return resource;
}
function merge(target) {
    let ref = [], slice = ref.slice;
    let args = slice.call(arguments, 1);

    args.forEach(source => {
        merger(target, source, true);
    });

    return target;
}
function merger(target, source, deep) {
    let key;
    for (key in source) {
        if (deep && (_.isObject(source[key]) || _.isArray(source[key]))) {
            if (_.isObject(source[key]) && !_.isObject(target[key])) {
                target[key] = {};
            }
            if (_.isArray(source[key]) && !_.isArray(target[key])) {
                target[key] = [];
            }
            merger(target[key], source[key], deep);
        } else if (source[key] !== undefined) {
            target[key] = source[key];
        }
    }
}
function opts(action, args) {
    let options = Object.assign({}, action), params = {}, body;
    // Use URI Template
    let template = new URITemplate(options.url);
    switch (args.length) {

        case 2:
            params = args[0];
            body = args[1];
            break;

        case 1:
            if (/^(POST)$/i.test(options.method)) {
                body = args[0];
            } else if (/^(PUT|PATCH)$/i.test(options.method)) {
                params = args[1];
            } else {
                params = args[0];
            }

            break;

        case 0:

            break;

        default:

            throw 'Expected up to 2 arguments [params, body], got ' + args.length + ' arguments';
    }

    options.data = body;
    options.params = Object.assign({}, action.params, options.params, params);
    options.url = template.expand(options.params);

    // clean variables from params if used in url template
    let usedParams = _.isObject(template.parts[1]) ? _.pluck(template.parts[1].variables, 'name') : [];
    _.each(usedParams, function (param) {
        delete options.params[param]
    });

    return options;
}
Resource.actions = {
    get: {method: 'GET'},
    post: {method: 'POST'},
    save: {method: 'POST'},
    query: {method: 'GET'},
    update: {method: 'PUT'},
    put: {method: 'PUT'},
    remove: {method: 'DELETE'},
    delete: {method: 'DELETE'}

};
// Resource END
Vue.prototype.$resource = Resource;

Vue.filter('phone', (phone) => {
    phone = phone || '';
    if (phone === '') return phone;
    return phone.replace(/[^0-9]/g, '')
        .replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
});

Vue.filter('number', (number, decimals) => {
    return isNaN(number) || number === 0 ? number : number.toFixed(decimals);
});

Vue.filter('percentage', (number, decimals) => {
    return isNaN(number) || number === 0 ? number : number.toFixed(decimals) + '%';
});

// MVC concept of handling dates
// Model/Server -> UTC | Vue Model/Controller -> UTC | View/Template -> Local
// This filter should convert date assigned property from UTC to local when being displayed -> read()
// This filter should convert date assigned property from Local to UTC when being changed via input -> writer5
Vue.filter('moment', (val, format, diff = false, noLocal = false) => {
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
});

Vue.filter('mDateToDatetime', (value, format) => {
    return moment.utc(value).local().format(format || 'LL');
});

Vue.filter('mUTC', (value) => {
    return moment.utc(value);
});

Vue.filter('mLocal', (value) => {
    return moment.isMoment(value) ? value.local() : null;
});

Vue.filter('mFormat', (value, format) => {
    return moment(value).format(format);
});

Vue.filter('underscoreToSpace', (value) => {
    return value.replace(/_/g, ' ');
});

Vue.filter('capitalize', (string) => {
    if (!string) return string;
    if (!_.isString(string)) string = string.toString();
    return string.replace(/\w\S*/g, function(txt){
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
});

Vue.directive('tour-guide', {
    inserted(el, binding, vnode) {

        function topScrollHandler (element){
            if (element) {
                let $element = window.jQuery(element);
                let topOfElement = $element.offset().top;
                let heightOfElement = $element.height();
                window.jQuery('html, body').animate({
                    scrollTop: topOfElement - heightOfElement
                }, {
                    duration: 1000
                });
            }
        }

        function storeTourRecord () {
            let completed = JSON.parse(localStorage.getItem('ToursCompleted')) || [];
            completed.push(location.pathname);
            localStorage.setItem('ToursCompleted', JSON.stringify(_.uniq(completed)));
        }

        let tour = window.tour = new Shepherd.Tour({
            defaults: {
                classes: 'shepherd-element shepherd-open shepherd-theme-arrows step-class',
                scrollTo: true,
                scrollToHandler: topScrollHandler,
                showCancelLink: true
            }
        });

        tour.addStep('intro', {
            title: 'Hello!',
            text: 'This guided tour will walk you through the features on this page. Take this tour anytime by clicking the <i class="fa fa-question-circle-o"></i> Tour link. Shall we begin?',
            showCancelLink: false,
            buttons: [
                {
                    text: 'Not Now',
                    action: tour.cancel,
                    classes: 'shepherd-button-secondary'
                },
                {
                    text: 'Continue',
                    action: tour.next
                }
            ]
        });

        // if pageSteps exists, add them to tour
        if (window.pageSteps && window.pageSteps.length) {
            _.each(window.pageSteps, (step) => {
                // if buttons are present
                if (step.buttons) {
                    _.each(step.buttons, (button) => {
                        // if action is present
                        if (button.action && _.isString(button.action))
                            button['action'] = tour[button.action];
                    });
                }
                tour.addStep(step);
            })
        }

        tour.on('cancel', storeTourRecord);

        tour.on('complete',storeTourRecord)
    },
    update() {
        // debugger
    },
    unbind() {
    }
});

Vue.directive('error-handler', {
    // This directive handles client-side and server-side errors.
    // It expects an object expression with three values:  { value: fieldValue, client: 'clientSideField', server: 'serverSideField' }

    // PROPERTIES
    // The `value` property expects the actual field value to stay reactive.
    // The `client` property expects the handle that the validator plugin uses for validation.
    // The `server` property expects the handle that the server request rules use for validation.
    // The `scope` property expects a string if provided, this is used to scope field validation.
    // If the `handle` property is present, client and server properties will be set to this.
    // OPTIONAL PROPERTIES
    // The `class` property allows you to set the error class to be set during validation.
    // The `messages` property allows you to customize the error message the user sees based on validators
    // i.e (`req` - required, `min` - minlength, `max` - maxlength, `email` - custom email validator)

    // When server-side validation errors are returned to the `this.errors` object, this handle references the property
    // for the field
    // deep: true,
    inserted(el, binding, vnode) {
        el.dataset.messages = JSON.stringify([]);

        vnode.context.$watch('errors', (val) => {
            let storedValue = el.dataset.storage !== undefined ? JSON.parse(el.dataset.storage) : binding.value;
            // The `attemptSubmit` variable delays validation until necessary, because this doesn't directly influence
            // the directive we want to watch it using the error-handler mixin
            if (storedValue) {
                vnode.context.handleValidationClass(el, storedValue);
                vnode.context.handleValidationMessages(el, storedValue);
            }
        }, { deep: true });


    },
    update(el, binding, vnode, oldVnode) {
        // If server value is identical to client, use 'handle' property for simplicity
        if(binding.value.handle) {
            binding.value.client = binding.value.handle;
            binding.value.server = binding.value.handle;
        }

        // Store the value within the directive to be used outside the update function
        el.dataset.storage = JSON.stringify(binding.value);

        // Handle error class and messages on element
        // vnode.context.handleValidationClass(el, vnode.context, binding.value);
        // vnode.context.handleValidationMessages(el, vnode.context, binding.value, vnode.context.errors);
    }
});

Vue.mixin({
    methods: {
        // Some universal methods to replace vue 1 filters
        limitBy(arr, number) {
            return _.first(arr, number);
        },
        orderByProp(arr, prop, direction = 1) {
            if (!_.isArray(arr) || !arr.length) return [];
            let list = _.sortBy(arr, prop);
            return direction === -1 ? list.reverse() : list
        },
        currency(number, symbol = '$') {
            if (!isNaN(number)) {
                return symbol + (Number(number).toFixed(2));
            }
            return number
        },
        pluralize: (value, unit, suffix = 's') => `${value} ${unit}${value !== 1 ? suffix : ''}`,
        /*showSpinner(){
               this.$refs.spinner.show();
               },
               hideSpinner(){
               this.$refs.spinner.hide();
               },*/
    },
    computed: {
        firstUrlSegment() {
            return document.location.pathname.split("/").slice(1, 2).toString();
        },
        isAdminRoute() {
            return this.firstUrlSegment == 'admin';
        },
        isDashboardRoute() {
            return this.firstUrlSegment == 'dashboard';
        },
    },
    mounted() {
        function isTouchDevice() {
            return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch);
        }

        // Disable tooltips on all components on mobile
        if (isTouchDevice()) {
            $("[rel='tooltip']").tooltip('destroy');
        }

    }
});

let RootInstance = {
    components: {},
    data: {
        userId: '',
        someObject: {},
        someArray: [],
        someString: '',
        // filters test Object
        filtersVars: {
            filters: {
                type: '',
                //tags: [],
                user: [],
                groups: [],
                campaign: null,
                gender: '',
                status: '',
                shirtSize: [],
                hasCompanions: null,
                due: '',
                todoName: '',
                todoStatus: null,
                designation: '',
                requirementName: '',
                requirementStatus: '',
                dueName: '',
                dueStatus: '',
                rep: '',
                age: [0, 120],
                minPercentRaised: '',
                maxPercentRaised: '',
                minAmountRaised: '',
                maxAmountRaised: ''
            },
            pagination: {
                current_page: 1,
            }
        }
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
    mounted: function () {
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

        // filters test methods
        filtersMethodsCallback(){
            console.log('callback function called');
        },
        filtersMethodsReset(){
            this.filtersVars.filters = {
                type: '',
                //tags: [],
                user: [],
                groups: [],
                campaign: null,
                gender: '',
                status: '',
                shirtSize: [],
                hasCompanions: null,
                due: '',
                todoName: '',
                todoStatus: null,
                designation: '',
                requirementName: '',
                requirementStatus: '',
                dueName: '',
                dueStatus: '',
                rep: '',
                age: [0, 120],
                minPercentRaised: '',
                maxPercentRaised: '',
                minAmountRaised: '',
                maxAmountRaised: ''
            };
            console.log('reset callback function called');
        },

        // Simple error handlers for API calls
        handleApiSoftError(response) {
            console.error(response.body.message ? response.body.message : response.body);
        },
        handleApiError(response) {
            console.error(response.body.message ? response.body.message : response.body);
            this.$root.$emit('showError', response.body.message)
        },


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
};

export { Vue, RootInstance, $, jQuery, moment, timezone, _, localStorage };

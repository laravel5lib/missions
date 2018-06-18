// IE support for vue $cookie
Number.isInteger = Number.isInteger || function(value) {
    return typeof value === "number" && isFinite(value) && Math.floor(value) === value;
};

// window._ = require('lodash');
window._ = require('underscore');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

window.$ = window.jQuery = require('jquery');

require('jquery.cookie');
require('bootstrap-sass');
require('eonasdan-bootstrap-datetimepicker');

$(document).ready(function () {
    console.log($.fn.popover.Constructor.VERSION);
    console.log($.fn.jquery);
    $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active')
    });

    $('[data-toggle="tooltip"]').tooltip();
});

(function($){
    $.fn.easyScroller = function(options) {

        let settings = $.extend({
            backToTop: false,
            scrollDownSpeed: 800,
            scrollUpSpeed: 600,
            topOffset: 0
        }, options);

        $(this).click(function(event) {
            let $this = $(this),
                sectionId = $this.attr('href');

            $('html, body').animate({
                scrollTop: $(sectionId).offset().top - settings.topOffset
            }, settings.scrollDownSpeed);

            return false;
        });

        if (settings.backToTop) {
            $(settings.backToTop).click(function () {
                $('html, body').animate({
                    scrollTop: 0
                }, settings.scrollUpSpeed);

                return false;
            });
        }
    };
})(window.jQuery);

/**
 * Slim Image Cropper is a cross platform JavaScript Image Cropping and Uploading
 * plugin. It's easy to setup and features beautiful graphics and animations.
 */
window.Slim = require('./vendors/slim.commonjs.js');

/**
 * We'll load moment and moment-timezone plugin which provides the ability to
 * parse,validate, manipulate, and display dates and times in JavaScript.
 */
window.moment = require('moment');
window.timezone = require('moment-timezone');

/**
 * moment-countdown is a tiny Moment.js plugin that integrates with Countdown.js.
 */
window.countdown = require('countdown');
countdown.setLabels('|| min| hr| day','ms| sec| mins| hrs| days| wks|| yrs',', ');

require('moment-countdown');

/**
 * Video.js is an open source library for working with video on the web,
 * also known as an HTML video player.
 */
window.videojs = require('video.js');
require('videojs-youtube');
require('videojs-vimeo');

/**
 * A full-featured markdown parser and compiler, written in JavaScript.
 */
window.marked = require('marked');

/**
 * ScrollMagic helps you to easily react to the user's current scroll position.
 */
import ScrollMagic from 'scrollmagic';
window.ScrollMagic = ScrollMagic;
import 'animation.gsap';
import 'debug.addIndicators';

/**
 * SweetAlert makes popup messages easy and pretty.
 */
import swal from 'sweetalert';


/**
 * AOS allows you to animate elements as you scroll down, and up. If you scroll back to
 * top, elements will animate to it's previous state and are ready to animate again if
 * you scroll down.
 */
window.AOS = require('aos');
AOS.init();

/**
 * Shepherd is a javascript library for guiding users through your app.
 * It uses Tether, another open source library,
 * to position all of its steps.
 */
window.Shepherd = require('tether-shepherd');

/**
 * Vue is a modern JavaScript library for building interactive web interfaces
 * using reactive data binding and reusable components. Vue's API is clean
 * and simple, leaving you to focus on building your next great project.
 */

window.Vue = require('vue');

Vue.use(require('vue-cookie'));
Vue.use(require('vee-validate'));
Vue.use(require('vue-autosize/src/index'));
Vue.use(require('vue-truncate'));

/**
 * Mask form inputs
 */
import VueTheMask from 'vue-the-mask'
Vue.use(VueTheMask);

/**
 * Mast form inputs for currency
 */
import money from 'v-money'
// register directive v-money and component <money>
Vue.use(money, { precision: 4 })

/**
 * Custom form class to handle vue component forms and form errors.
 */
import Form from './utilities/Form';
window.Form = Form;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.baseURL = '/api';

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;
if (csrfToken) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

/**
 * We'll make any existing vue-resource $http namespaces compatible with axios
 */
Vue.prototype.$http = axios;

/**
 * Resource duplicate of vue-resource for axios
 * Inspired by https://github.com/pagekit/vue-resource/blob/develop/src/resource.js
 */
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

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo'

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: 'your-pusher-key'
// });
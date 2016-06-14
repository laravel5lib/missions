import Vue from 'vue';

// jQuery
window.$ = window.jQuery = require('jquery')
require('bootstrap-sass');

$( document ).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
});

// Vue Resource
Vue.use(require('vue-resource'));

Vue.http.options.root = '/api'
Vue.http.interceptors.push({

    request: function (request) {
        var token, headers

        token = window.localStorage.getItem('jwt-token')
        headers = request.headers || (request.headers = {})

        if (token !== null && token !== 'undefined') {
            headers.Authorization = token
        }

        return request
    },

    response: function (response) {
        if (response.status && response.status === 401) {
            window.localStorage.removeItem('jwt-token')
        }
        if (response.headers && response.headers('Authorization')) {
            console.log('found authorization header')
            window.localStorage.setItem('jwt-token', response.headers('Authorization'))
        }
        if (response.data && response.data.token && response.data.token.length > 10) {
            window.localStorage.setItem('jwt-token', 'Bearer ' + response.data.token)
        }

        return response
    }
})

new Vue({
    el: '#app',
    http: {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    },
    ready: function() {
        // GET request
        this.$http({url: 'someUrl', method: 'GET'}).then(function (response) {
            // success callback
        }, function (response) {
            // error callback
        });

    }
});
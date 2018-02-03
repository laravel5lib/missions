
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will load all of this project's custom
 * vue filters, directives and mixinis.
 */
require('./filters');
require('./directives');
require('./mixins');

/**
 * Finally, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
require('./components');

const app = new Vue({
    el: '#app',
    propsData: {
        auth: {
            type: Boolean,
            default: false
        }
    },
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
        authenticated: false,
        user: null,
    },
    computed: {
        impersonatedUser() {
            return JSON.parse(localStorage.getItem('impersonatedUser'));
        },
        impersonatedToken() {
            return this.$cookie.get('impersonate');
        },
    },
    methods: {
        setUser(user) {
            // Save user info
            this.user = user;
            this.authenticated = true;
        },
        fetchUser() {
            if (localStorage.hasOwnProperty('user')) {
                return JSON.parse(localStorage.getItem('user'))
            } else {
                this.$http.get('users/me?include=roles,permissions')
                    .then((response) => {
                            this.$emit('userHasLoggedIn', response.data.data);
                            return response.data.data;
                        },
                        (response) => {
                            if (this.isAdminRoute || this.isDashboardRoute)
                               console.log('error');
                        });
            }
        },
        getImpersonatedUser() {
            if (this.impersonatedUser !== null) {
                console.log('impersonating: ', this.impersonatedUser.name);
                return this.impersonatedUser
            } else {
                return this.$http.get('users/' + this.impersonatedToken + '?include=roles,permissions')
                    .then((response) => {
                        console.log('impersonating: ', response.data.data.name);
                        localStorage.setItem('impersonatedUser', JSON.stringify(response.data.data));
                        return this.impersonatedUser = response.data.data;
                    });
            }
        },
        can(permission) {
            let permissions = _.pluck(this.user.permissions.data, 'name');
            return this.user ? _.contains(permissions, permission) : false;
        },
        cannot(permission) {
            let permissions = _.pluck(this.user.permissions.data, 'name');
            return this.user ? ! _.contains(permissions, permission) : false;
        },
        startTour() {
            window.tour.start();
        },
        // Simple error handlers for API calls
        handleApiSoftError(error) {
            console.error(error.response.data.message ? error.response.data.message : error.response.data);
        },
        handleApiError(error) {
            console.log(error);
            console.error(error.response.data.message ? error.response.data.message : error.response.data);
            this.$emit('showError', error.response.data.message)
        },
        convertSearchToObject() {
            let search = location.search.substring(1);
            return search ? JSON.parse('{"' + search.replace(/&/g, '","').replace(/=/g, '":"') + '"}',
                (key, value) => {
                    return key === "" ? value : decodeURIComponent(value)
                }) : {};
        }

    },
    created() {
        let self = this;
        // Add a request interceptor
        this.$http.interceptors.request.use((config) => {
            // modify request
            let token;

            token = $.cookie('api_token') ? $.cookie('api_token').indexOf('Bearer') !== -1 ? $.cookie('api_token') : 'Bearer ' + $.cookie('api_token') : null;
            if (token !== null && token !== 'undefined') {
                config.headers.common['Authorization'] = token;
            }

            // Show Spinners in all components where they exist
          
            if (_.contains(['GET', 'POST', 'PUT'], config.method.toUpperCase())) {
                if (!config.params.hideLoader) {
                    switch (config.method.toUpperCase()) {
                        case 'GET':
                            this.$root.$emit('spinner::show', {text: 'Loading'});
                            // this.$refs.spinner.show({text: 'Loading'});
                            break;
                        case 'POST':
                            this.$root.$emit('spinner::show', {text: 'Saving'});
                            // this.$refs.spinner.show({text: 'Saving'});
                            break;
                        case 'PUT':
                            this.$root.$emit('spinner::show', {text: 'Updating'});
                            // this.$refs.spinner.show({text: 'Updating'});
                            break;
                    }
                }
            }
            return config;
        }, (error) => {
            // Do something with request error
            // debugger;
            return Promise.reject(error);
        });

        // Add a response interceptor
        this.$http.interceptors.response.use((response) => {
            // Hide Spinners in all components where they exist
            this.$root.$emit('spinner::hide');
            
          if (response.status) {
                if (response.headers['Authorization']) {
                    $.cookie('api_token', response.headers['Authorization']);
                }
                if (response.data.token && response.data.token.length > 10) {
                    $.cookie('api_token', response.data.token);
                }
            }

            return response;
        }, (error) => {
            // Do something with response error
            if (error.response) {
                if (error.response.status === 401) {
                    $.removeCookie('api_token');
                    console.log('not logged in');
                    // window.location.replace('/logout');
                }
                if (error.response.status === 500) {
                    console.log('Oops! Something went wrong with the server.')
                }

            }
            return Promise.reject(error);
        });

        // if api_token cookie doesn't exist user data will be cleared if they do exist

        if ( $('#app').data('auth') ) {
            this.authenticated = true;
            this.user = this.$cookie.get('impersonate') !== null ? this.getImpersonatedUser() : this.fetchUser();
        } else {
            if (localStorage.hasOwnProperty('user'))
                localStorage.removeItem('user');
            if (localStorage.hasOwnProperty('impersonatedUser'))
                localStorage.removeItem('impersonatedUser');
        }


    },
    mounted() {
        // Track window resizing
        $(window).on('resize', () => {
            this.$emit('Window:resize');
        });

        this.$on('userHasLoggedIn', (user) => {
            localStorage.setItem('user', JSON.stringify(user));
            this.user = user;
            this.authenticated = true;
        });

        this.$on('showSuccess', (msg) => {
            this.message = msg;
            this.showError = false;
            this.showInfo = false;
            this.showSuccess = true;
        });

        this.$on('showInfo', (msg) => {
            this.message = msg;
            this.showError = false;
            this.showInfo = true;
            this.showSuccess = false;
        });

        this.$on('showError', (msg) => {
            this.message = msg;
            this.showInfo = false;
            this.showSuccess = false;
            this.showError = true;
        });

        // check if impersonated data is no longer needed
        if (this.$cookie.get('impersonate') === null)
            localStorage.removeItem('impersonatedUser');
    }
});
/* The purpose of this mixin is to work along with custom directives to provide a standard system of
 * error handling and error message rendering that is consistent and predictable.
 */
import $ from 'jquery';
import _ from 'underscore';

export default {
  data() {
    return {
      SERVER_ERRORS: [],
      DISPLAYED_MESSAGES: {},
      ERROR_MESSAGES: {
        DEFAULT: 'Field invalid.',
        // Defaults
        name: 'Please provide your name.',
        contact: 'Please provide a contact name.',
        position: 'Please provide a contact\'s position.',
        email: 'Please provide an email address.',
        phone: 'Please provide a phone number.',
        type: 'Please select a type.',
        given_names: 'Please provide the given names.',
        givennames: 'Please provide the given names.',
        firstname: 'Please provide the first name.',
        lastname: 'Please provide the last name.',
        surname: 'Please provide the surname.',
        number: 'Please provide a valid number.',
        country: 'Please select a country.',
        timezone: 'Please select a timezone.',
        password: 'Please enter your password.',
        passwordconfirmation: 'Please enter your password again.',
        issued: 'Please provide an issue date',
        expires: 'Please provide an expiration date',
        role: 'Please select a role.',
        author: 'Please enter author\'s name.',

        // START EMERGENCY CONTACT FORM
        emergencyname: 'Please enter an emergency contact name.',
        emergencyemail: 'Please enter the contact\'s email.',
        emergencyphone: 'Please enter the contact\'s phone number.',
        emergencyrelationship: 'Please select relationship to the contact.',
        // END EMERGENCY CONTACT FORM
      }
    }
  },
  computed: {
    isFormDirty() {
      return Object.keys(this.fields).some(key => this.fields[key].dirty || this.fields[key].touched);
    }
  },
  watch: {
    errors: {
      handler(val) {
        this.$emit('errors', val);
      },
      deep: true
    },
  },
  methods: {
    resetErrors() {
      //this.errors = {};
      //this.attemptSubmit = true;
    },
    handleValidationClass(el, value) {
      let classTest = this.errors.has(value.client, value.scope || null) || this.SERVER_ERRORS[value.server];
      if (classTest) {
        $(el).addClass(value.class || 'has-error');
      } else {
        $(el).removeClass(value.class || 'has-error');
      }
    },

    handleValidationMessages(el, value) {
      let validationObject, genericMessage;
      let messagesArrName = value.scope ? `${value.client}-${value.scope}` : value.client;
      this.DISPLAYED_MESSAGES[messagesArrName] = [];
      
      // Lets first package server errors for iteration
      if (this.SERVER_ERRORS[value.server]) {
        _.each(this.SERVER_ERRORS[value.server], function (error, index) {
          this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error sever-msg'>${error}</div>`);
          // this.DISPLAYED_MESSAGES[messagesArrName].push(error);
        });
      }

      genericMessage = this.ERROR_MESSAGES.DEFAULT;
      validationObject = _.findWhere(this.errors.items, {field: value.client});
      if (_.isObject(validationObject)) {
        if (validationObject.rule.includes('required')) {
          // Grab message from storage if it exists or use generic default
          // The manually set messages must be an object
          let reqMessage;
          if (value.messages && value.messages.req) {
            reqMessage = value.messages.req;
          } else {
            genericMessage = this.ERROR_MESSAGES[value.client] || 'This field is required';
            reqMessage = _.isObject(genericMessage) ? genericMessage.req : genericMessage;
          }

          // this.DISPLAYED_MESSAGES[messagesArrName].push( reqMessage);
          this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error req-msg'>${reqMessage}</div>`);
        }

        if (validationObject.rule.includes('min')) {
          // Grab message from storage if it exists or use generic default
          let minMessage;
          if (value.messages && value.messages.min) {
            minMessage = value.messages.min;
          } else {
            genericMessage = this.ERROR_MESSAGES[value.client] || genericMessage;
            minMessage = _.isObject(genericMessage) ? genericMessage.min : genericMessage;
          }
          if (minMessage !== genericMessage) {
            // this.DISPLAYED_MESSAGES[messagesArrName].push(minMessage);
            this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error min-msg'>${minMessage}</div>`);
          }
        }

        if (validationObject.rule.includes('max')) {
          // Grab message from storage if it exists or use generic default
          let maxMessage;
          if (value.messages && value.messages.max) {
            maxMessage = value.messages.max;
          } else {
            genericMessage = this.ERROR_MESSAGES[value.client] || genericMessage;
            maxMessage = _.isObject(genericMessage) ? genericMessage.max : genericMessage;
          }
          if (maxMessage !== genericMessage) {
            // this.DISPLAYED_MESSAGES[messagesArrName].push(maxMessage);
            this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error max-msg'>${maxMessage}</div>`);
          }
        }

        // custom email validator
        if (validationObject.rule.includes('email')) {
          // Grab message from storage if it exists or use generic default
          let emailMessage;
          if (value.messages && value.messages.email) {
            emailMessage = value.messages.email;
          } else {
            genericMessage = this.ERROR_MESSAGES[value.client] || genericMessage;
            emailMessage = _.isObject(genericMessage) ? genericMessage.email : genericMessage;
          }
          if (emailMessage !== genericMessage) {
            // this.DISPLAYED_MESSAGES[messagesArrName].push(emailMessage);
            this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error email-msg'>${emailMessage}</div>`);
          }
        }

        // custom datetime validator
        if (validationObject.rule.includes('date_format')) {
          // Grab message from storage if it exists or use generic default
          let datetimeMessage;
          if (value.messages && value.messages.datetime) {
            datetimeMessage = value.messages.datetime;
          } else {
            genericMessage = this.ERROR_MESSAGES[value.client] || genericMessage;
            datetimeMessage = _.isObject(genericMessage) ? genericMessage.email : genericMessage;
          }
          if (datetimeMessage !== genericMessage) {
            this.DISPLAYED_MESSAGES[messagesArrName].push(datetimeMessage);
            this.DISPLAYED_MESSAGES[messagesArrName].push(`<div class='help-block server-validation-error date-msg'>${datetimeMessage}</div>`);
          }
        }
      }

      // We want to keep track of listed errors and specify their location when displayed
      // Search for an '.errors-block' element as child to display messages in
      // If it does not exist we will place the error message after the input element
      let scope = value.scope || null;
      let errorsBlock = el.getElementsByClassName('errors-block')[0] || false;
      if (errorsBlock) {
        $(errorsBlock).find('.server-validation-error').remove();
        if (this.SERVER_ERRORS[value.server] || value.client && this.errors.has(value.client, scope)) {
          $(errorsBlock).append(this.DISPLAYED_MESSAGES[messagesArrName]);
        }
      } else {
        let inputGroup = $(el).hasClass('input-group') ? el : el.getElementsByClassName('input-group')[0];
        let inputEl = $(el).find('.form-control:not(.v-select *)');
        if (inputGroup) {
          $(el).parent().find('.server-validation-error').remove();
          if (this.SERVER_ERRORS[value.server] || value.client && this.errors.has(value.client, scope)) {
            $(inputGroup).after(this.DISPLAYED_MESSAGES[messagesArrName]);
          }
        } else {
          $(el).find('.server-validation-error').remove();
          if (this.SERVER_ERRORS[value.server] || value.client && this.errors.has(value.client, scope)) {
            inputEl.after(this.DISPLAYED_MESSAGES[messagesArrName]);
          }
        }
      }
    },
  },
  mounted() {

  }
}
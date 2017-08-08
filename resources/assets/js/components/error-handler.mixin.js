/* The purpose of this mixin is to work along with custom directives to provide a standard system of
 * error handling and error message rendering that is consistent and predictable.
 */
import _ from 'underscore';
export default {
    data(){
        return {
            validatorHandle: false,
            errors: {},
            attemptSubmit: false,
            MESSAGES: {
                DEFAULT: 'Field invalid.',
                // Defaults
                name: 'Please provide your name.',
                givennames: 'Please provide the given names.',
                surname: 'Please provide the surname.',
                number: 'Please provide a valid number.',
                country: 'Please select a country.',
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
    watch: {
        attemptSubmit(val) {
            this.$emit('attemptSubmit', val);
        },
        errors: {
            handler: function(val){
                this.$emit('errors', val);
            },
            deep: true
        },
    },
    methods: {
        checkForError(field){
            // if user clicked submit button while the field is invalid trigger error styles 
            if (this.attemptSubmit) {
                if (_.isString(field) && this['$' + this.validatorHandle] && this['$' + this.validatorHandle][field]) {
                    return this['$' + this.validatorHandle][field].invalid;
                }
                //console.warn(field + ' does not exist in ' + this.validatorHandle);
                return false
            }
            return false;
        },
        resetErrors(){
            this.errors = {};
            this.attemptSubmit = true;
        },
    },
    mounted(){
        if (!this.validatorHandle) {
            console.error('Please set `validatorHandle` to validator name');
        }
    }
}
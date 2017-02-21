// The purpose of this mixin is to work along custom directives to provide a standard system of
// error handling and error message rendering that is consistent and predictable.
export default {
    data(){
        return {
            validatorHandle: false,
            errors: {},
            attemptSubmit: false,
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
            return _.isString(field) && this['$' + this.validatorHandle][field].invalid && this.attemptSubmit;
        },
        resetErrors(){
            this.errors = {};
            this.attemptSubmit = false;
        },
    }, ready(){
        if (!this.validatorHandle) {
            console.error('Please set validatorHandle to validator name');
        }
    }
}
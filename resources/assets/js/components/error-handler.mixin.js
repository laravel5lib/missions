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
        }
    },
    methods: {
        checkForError(field){
            // if user clicked submit button while the field is invalid trigger error styles 
            return this['$' + this.validatorHandle][field].invalid && this.attemptSubmit;
        }
    }, ready(){
        if (!this.validatorHandle) {
            console.log('Please set validatorHandle to validator name');
        }
    }
}
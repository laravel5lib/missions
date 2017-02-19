// The purpose of this mixin is to work along custom directives to proviide a standard system of
// error handling and error message rendering that is consistent and predictable.
export default {
    data(){
        return {
            formHandle: false,
            errors: {},
            attemptSubmit: false,
        }
    },
    methods: {
        checkForError(field){
            // if user clicked submit button while the field is invalid trigger error styles 
            return this['$' + this.formHandle][field].invalid && this.attemptSubmit;
        }
    }
}
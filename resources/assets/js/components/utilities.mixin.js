/**
 * Created by jerezb on 2017-05-24.
 */
// The purpose of this mixin is to eliminate duplicate code and provide consistent use of the Utilities API when needed
export default {
    data() {
        return {
            UTILITIES: {
                countries: [],
                airports: [],
                airlines: [],
                timezones: [],
                roles: [],
            }
        }
    },
    methods: {
        getCountries(search, loading){
            loading ? loading(true) : void 0;
            return this.$http.get('utilities/countries', {params: {search: search}}).then(function (response) {
                this.UTILITIES.countries = response.body.countries;
                if (loading) {
                    loading(false);
                } else {
                    return this.UTILITIES.countries;
                }
            })
        }
    },
}
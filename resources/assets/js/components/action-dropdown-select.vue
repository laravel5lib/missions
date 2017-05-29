<template>
	<!--<strap-select :placeholder="text" :options="options" options-label="name" options-value="id" :value.sync="selectedVal" :multiple="multiple" required close-on-select></strap-select>-->
    <div class="btn-group">
        <button type="button" class="btn btn-white-hollow dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{selectedOptions ? selectedOptions[label] : text}} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a @click="selectedOptions = option" v-for="option in options">{{option[label]}} <i v-if="selectedOptions && selectedOptions[label] === option[label]" class="fa fa-check"></i></a></li>
        </ul>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    export default{
        name: 'action-dropdown-select',
        components: {vSelect},
        props: {
            debounce: {
                type: Number,
                default: 250
            },
            text: {
                type: String,
                default: 'Select Option'
            },
            label: {
                type: String,
                default: 'name'
            },
            value: {
                type: String,
                default: 'id'
            },
            selectedOptions: {
                //type: Array,
                default: null
            },
            options: {
                type: Array,
                required: true
            },
            autoSelectFirst: {
                type: Boolean,
                default: false
            },
            api: {
                type: Boolean,
                default: false
            },
            searchRoute: {
                type: String,
            },
            event: {
                type: String,
                required: true
            }
        },
        data(){
            return{
            }
        },
        watch: {
            selectedOptions(val){
                this.$root.$emit(this.event, val);
            }
        },
        methods: {
            execute(val){
                this.$root.$emit(this.event, val);
            },
            getOptions (search, loading) {
                loading ? loading(true) : void 0;

                if (this.api) {
                    return this.$http.get(this.searchRoute, { params: {search: search} }).then(function (response) {
                        this.options = response.body.data;
                        if (loading) {
                            loading(false);
                        } else {
                            return response.body.data;
                        }
                    });
                } else {
                    let newOptions = _.filter(this.options, function (option) {
                        return option.name.indexOf(search) !== -1;
                    });
                    loading(false);
                    return newOptions;
                }
            }
        },
        ready() {
            if (this.api) {
                if (!this.searchRoute) {
                    throw 'A search-route is needed if the api is being used.'
                }

                this.getOptions().then(function () {
                    if (this.autoSelectFirst)
                        this.selectedOptions = this.options[0];
                });

                this.$root.$on('update-select-options', function (val) {
                    this.getOptions()
                }.bind(this));

                this.$root.$on('select-select-option', function (val, property) {
                    this.selectedOptions = _.findWhere(this.options, { id: val });
                }.bind(this));

                this.$root.$on('select-select-options', function (val, property) {
                    this.selectedOptions = _.some()
                }.bind(this));

            }
        }
    }
</script>

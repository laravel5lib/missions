<template>
    <div>
        <template v-if="normal">
            <select class="form-control" @change="execute" :multiple="multiple" v-model="selectedOptions">
                <option value="" class="select-placeholder">{{text}}</option>
                <option :value="option" v-for="option in options">{{option[label]}}</option>
            </select>
        </template>
        <template v-else>
            <v-select @keydown.enter.prevent="" :multiple="multiple" class="form-control" :debounce="debounce" :on-search="getOptions"
                      :value="selectedOptions" :options="options" :label="label" :on-change="execute"
                      :placeholder="text"></v-select>
        </template>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import vSelect from 'vue-select';
    export default{
        name: 'action-select',
        components: {vSelect},
        props: {
            multiple: {
                type: Boolean,
                default: false
            },
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
            normal: {
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
                if (this.normal) {
                    $('.select-placeholder').remove();
                }
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
                    return this.$http.get(this.searchRoute, { params: {search: search} }).then((response) => {
                        this.options = response.data.data;
                        if (loading) {
                            loading(false);
                        } else {
                            return response.data.data;
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
        mounted() {
            if (this.api) {
                if (!this.searchRoute) {
                    throw 'A search-route is needed if the api is being used.'
                }

                this.getOptions().then(() => {
                    if (this.autoSelectFirst)
                        this.selectedOptions = this.options[0];
                });

            }
        }
    }
</script>

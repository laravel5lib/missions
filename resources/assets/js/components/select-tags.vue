<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        
        <template v-if="horizontal">
            <slot name="label">
                <label class="col-sm-4 control-label">Select Tag(s)</label>
            </slot>
            <div :class="classes">
                <v-select @keydown.enter.prevent="null"
                          multiple
                          class="form-control"
                          :id="name"
                          v-model="tagsCollection"
                          :options="tagOptions"
                          :on-search="getTags"
                          :placeholder="placeholder"
                          label="name"
                          :name="name">
                </v-select>
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>
        </template>
        <template v-else>
            <slot name="label">
                <label class="control-label">Select Tag(s)</label>
            </slot>
            <v-select @keydown.enter.prevent="null"
                      multiple
                      class="form-control"
                      :id="name"
                      v-model="tagsCollection"
                      :options="tagOptions"
                      :on-search="getTags"
                      :placeholder="placeholder"
                      label="name"
                      :name="name">
            </v-select>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="($parent.form ? $parent.form.errors.has(name) : false)">
            </span>
            <slot name="help-text" v-else></slot>
        </template>

    </div>
</template>
<script>
import _ from 'underscore';
import vSelect from "vue-select";

export default {
    name: 'select-tags',

    components: {vSelect},

    data() {
        return {
            tagsCollection: [],
            tagOptions: []
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Array,
            default: []
        },
        'placeholder': {
            type: String,
            default: null
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-8'
        },
        'type': {
            type: String,
            default: 'trip'
        }
    },

    computed: {
        tags() {
            return _.pluck(this.tagsCollection, 'name');
        }
    },

    watch: {
        'tags'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    methods: {
        getTags(search = null, loading){
            loading ? loading(true) : void 0;
            let params = {filter: {name: search}};
            this.$http.get('tags/' + this.type, {
                    params,
                    paramsSerializer: function(params) {
                        return decodeURIComponent($.param(params));
                    }
                }).then((response) => {
                this.tagOptions = response.data.data;
                loading ? loading(false) : void 0;
            });
        },
    },

    mounted() {
        this.getTags();

        this.$parent.form[this.name] = this.tags;
        this.$parent.form.set(this.name, this.tags);

        if (this.value) {
            this.tagsCollection = this.value;
        }

        this.$root.$on('form:reset', () => {
            this.tagsCollection = null;
        });
    }
}
</script>

<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"></slot>
            <textarea class="form-control"
                v-autosize="content" 
                v-model="content"
                :name="name"
                :value="value"
                :placeholder="placeholder"
                :rows="rows"
                v-html="descriptionHTML">
            </textarea>
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
        </div>
    </div>
</template>
<script>
let marked = require('marked');
export default {
    name: 'admin-trip-description',

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: String,
            default: null
        },
        'placeholder': {
            type: String,
            default: null
        },
        'rows': {
            type: Number,
            default: 10
        }
    },

    data() {
        return {
            content: null,
        }
    },

    computed: {
        descriptionHTML() {
            return this.content;
        }
    },

    watch: {
        'descriptionHTML'(value) {
            this.$parent.form[this.name] = value;
        }
    },

    methods: {
        marked: marked
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        if (this.value) {
            this.content = this.value;
        }

        this.$root.$on('form:reset', () => {
            this.content = null;
        });
    }
}
</script>
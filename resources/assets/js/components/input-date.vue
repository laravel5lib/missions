<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 

        <slot name="label"></slot>

        <template v-if="horizontal">

            <div :class="classes">
                <input class="form-control" 
                    v-model="date"
                    v-mask="format" 
                    :readonly="readonly"
                    :name="name"
                    :placeholder="placeholder">
                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="$parent.form.errors.has(name)">
                </span>
                <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
            </div>

        </template>
        <template v-else>

            <input class="form-control" 
                v-model="date"
                v-mask="format" 
                :readonly="readonly"
                :name="name"
                :placeholder="placeholder">
            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="$parent.form.errors.has(name)">
            </span>
            <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>

        </template>
    </div>
</template>
<script>
export default {
    name: 'input-date',

    data() {
        return {
            date: null
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: String,
            default: null
        },
        'format': {
            type: String,
            default: '##/##/####'
        },
        'placeholder': {
            type: String,
            default: 'mm/dd/yyyy'
        },
        'horizontal': {
            type: Boolean,
            default: false
        },
        'classes': {
            type: String,
            default: 'col-sm-9'
        },
        'readonly': {
            type: Boolean
        }
    },

    watch: {
        'date'(value) {
            this.$emit('input', moment(value).format('YYYY-MM-DD'));
        }
    },

    mounted() {
        this.$nextTick(() =>  {
            if (this.value) {
                this.date = moment(this.value).format('MM/DD/YYYY');
            }
        });

        this.$root.$on('form:reset', () => {
            this.date = null;
        });
    }
}
</script>

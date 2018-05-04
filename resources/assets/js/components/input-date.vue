<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <slot name="label"></slot>
        <input class="form-control" 
            v-model="date"
            v-mask="format" 
            :name="name"
            :placeholder="placeholder">
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
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

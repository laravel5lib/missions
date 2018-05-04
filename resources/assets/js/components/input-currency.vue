<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <slot name="label"></slot>
        <money v-model.lazy="amount" 
            v-bind="money" 
            class="form-control">
        </money>
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
    </div>
</template>
<script>
export default {
    name: 'input-currency',

    data() {
        return {
            amount: 0,
            money: {
                decimal: '.',
                thousands: ',',
                prefix: '$ ',
                suffix: ' USD',
                precision: 2,
                masked: false
            }
        }
    },

    props: {
        'name': {
            type: String,
            required: true
        },
        'value': {
            type: Number,
            default: null
        }
    },

    watch: {
        'amount'(value) {
            this.$emit('input', value);
        }
    },

    mounted() {
        this.$nextTick(() =>  {
            this.amount = this.value
        })

        this.$root.$on('form:reset', () => {
            this.amount = 0;
        });
    }
}
</script>

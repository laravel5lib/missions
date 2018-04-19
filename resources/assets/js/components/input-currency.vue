<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
         <div class="col-xs-12">
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
            this.$parent.form[this.name] = value;
        }
    },

    methods: {
        updateInput(value) {
            this.$parent.form[this.name] = value;
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);
        this.amount = this.value;

        this.$root.$on('form:reset', () => {
            this.amount = null;
        });
    }
}
</script>

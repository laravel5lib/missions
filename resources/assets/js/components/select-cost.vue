<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <slot name="label"><label>Cost</label></slot>
        <select class="form-control" v-model="cost" :value="value" @change="updateValue($event.target.value)">
            <option v-for="cost in costs" :value="cost" :key="cost.id">{{ cost.name }}</option>
        </select>
        <span class="help-block" 
                v-text="$parent.form.errors.get(name)" 
                v-if="$parent.form.errors.has(name)">
        </span>
        <slot name="help-text" v-if="!$parent.form.errors.has(name)"></slot>
    </div>
</template>
<script>
export default {
    name: 'select-cost',

    data() {
        return {
            cost: null,
            costs: []
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
        'url': {
            type: String,
            default: 'costs'
        }
    },

    watch: {
        'cost'(value) {
            this.$parent.form[this.name] = value.id;
        }
    },

    methods: {
        getCosts() {
            return this.$http.get(this.url).then(response => {
                this.costs = response.data.data;
            }).catch(error => {
                console.log('error');
            });
        },
        updateValue(value) {
            this.$emit('input', value);
            this.$root.$emit('cost-change', this.cost);
            this.$parent.form ? this.$parent.form.errors.clear(this.name) : null;
        }
    },

    mounted() {
        let promise = this.getCosts();

        promise.then((values) => {
            if (this.value) {
                this.cost = _.findWhere(this.costs, { id: this.value });
                this.$root.$emit('cost-change', this.cost)
            }
        });

        this.$root.$on('form:reset', () => {
            this.value = null;
        });
    }
}
</script>

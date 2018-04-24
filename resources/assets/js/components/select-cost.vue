<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"><label>Cost</label></slot>
            <select class="form-control" v-model="cost" :value="value" @change="$root.$emit('cost-change', cost)">
                <option v-for="cost in costs" :value="cost" :key="cost.id">{{ cost.name }}</option>
            </select>
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
        }
    },

    watch: {
        'cost'(value) {
            this.$parent.form[this.name] = value.id;
        }
    },

    methods: {
        getCosts() {
            return this.$http.get('costs').then(response => {
                this.costs = response.data.data;
            }).catch(error => {
                console.log('error');
            });
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

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

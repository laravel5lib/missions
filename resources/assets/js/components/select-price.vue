<template>
    <div class="form-group" :class="{'has-error' : $parent.form.errors.has(name)}"> 
        <div class="col-xs-12">
            <slot name="label"><label>Price</label></slot>
            <select class="form-control" v-model="price" :value="value" @change="updateValue($event.target.value)">
                <option v-for="price in prices" :value="price" :key="price.id">{{ price.cost.name }}</option>
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
    name: 'select-price',

    data() {
        return {
            price: null,
            prices: []
        }
    },

    props: {
        'url': {
            type: String,
            required: true
        },
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
        'price'(value) {
            this.$parent.form[this.name] = value.id;
        }
    },

    method: {
        updateValue(value) {
            this.$emit('input', parseInt(value));
            this.$root.$emit('price-change', price);
        }
    },

    mounted() {
        this.$parent.form[this.name] = this.value;
        this.$parent.form.set(this.name, this.value);

        this.$http.get(this.url).then(response => {
            this.prices = response.data.data;
        }).catch(error => {
            console.log('error');
        });

        this.$root.$on('form:reset', () => {
            this.value = null;
        });
    }
}
</script>

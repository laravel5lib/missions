<template>
    <div class="form-group" :class="{'has-error' : ($parent.form ? $parent.form.errors.has(name) : false)}"> 
        <template v-if="horizontal">

            <slot name="label"><label class="col-sm-3 control-label">Price</label></slot>
            
            <div :class="classes">
                <select class="form-control" v-model="price" :value="value" @change="updateValue($event.target.value)">
                    <option v-for="price in prices" :value="price" :key="price.id">{{ price.cost.name }}</option>
                </select>

                <span class="help-block" 
                        v-text="$parent.form.errors.get(name)" 
                        v-if="($parent.form ? $parent.form.errors.has(name) : false)">
                </span>
                <slot name="help-text" v-else></slot>
            </div>

        </template>
        <template v-else>

            <slot name="label"><label>Price</label></slot>
            
            <select class="form-control" v-model="price" :value="value" @change="updateValue($event.target.value)">
                <option v-for="price in prices" :value="price" :key="price.id">{{ price.cost.name }}</option>
            </select>

            <span class="help-block" 
                    v-text="$parent.form.errors.get(name)" 
                    v-if="($parent.form ? $parent.form.errors.has(name) : false)">
            </span>
            <slot name="help-text" v-else></slot>

        </template>
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
        },
        'classes': {
            type: String,
            default: 'col-sm-9'
        },
        'horizontal': {
            type: Boolean,
            default: false
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

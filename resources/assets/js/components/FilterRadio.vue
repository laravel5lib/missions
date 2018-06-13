<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div v-for="option in config.options" :key="option.value" style="margin-left: 1em;">
                    <label style="cursor: pointer">
                        <input type="radio" :value="option.value" v-model="picked" style="margin-right: 1em">
                        {{ option.label }}
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <hr class="divider">
                <button class="btn btn-primary" @click="apply" :disabled="!picked">Apply</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        callback: Function,
        config: Object
    },
    data() {
        return {
            picked: null
        }
    },
    methods: {
        apply() {
            this.callback(this.config.field, this.picked);
            this.$emit('apply:filter', {
                key: this.config.field, 
                text: this.config.title, 
                value: _.findWhere(this.config.options, {value: this.picked}).label
            });
        }
    }
}
</script>

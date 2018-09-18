<template>
    <div>
        <div class="row">
            <div class="col-xs-6" v-for="(option, index) in options">
                <label>
                    <input type="checkbox" v-model="selected" :value="option.value"> {{ option.label }}
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <hr class="divider">
                <button class="btn btn-primary" @click="apply" :disabled="!selected">Apply</button>
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
            options: [],
            selected: []
        }
    },
    methods: {
        apply() {
            this.callback(this.config.field, this.selected.join());

            this.$emit('apply:filter', {
                key: this.config.field, 
                text: this.config.title, 
                value: _.isArray(this.selected) ? this.selected.join() : _.findWhere(this.options, {value: this.selected}).label
            });
        },
        fetchOptions() {
            let that = this;
            this.$http
                .get(this.config.ajax.url)
                .then(response => {
                    this.options = _.map(response.data.data, function (item) {
                        return { value: item[that.config.ajax.value], label: item[that.config.ajax.label] }
                    });
                })
                .catch(error => {
                    console.log(error);
                });
        }
    },
    mounted() {
        if (this.config.ajax) {

            this.fetchOptions();

        } else {

           this.options = this.config.options;

        }
    }
}
</script>

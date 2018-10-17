<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <select class="form-control" v-model="selected">
                    <option v-for="(option, index) in options" 
                            :key="index" 
                            :value="option.value" 
                    >{{ option.label }}
                    </option>
                </select>
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
            selected: null
        }
    },
    methods: {
        apply() {
            this.callback(this.config.field, this.selected);

            this.$emit('apply:filter', {
                key: this.config.field, 
                text: this.config.title, 
                value: _.findWhere(this.options, {value: this.selected}).label
            });
        },
        fetchOptions() {
            let that = this;
            this.$http
                .get(this.config.ajax.url)
                .then(response => {
                    let data = _.map(response.data.data, function (item) {
                        return { value: item[that.config.ajax.value], label: item[that.config.ajax.label] }
                    });

                    if (this.config.staticOptions) {
                        this.options = _.union(this.config.staticOptions, data);
                    } else {
                        this.options = data;
                    }
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

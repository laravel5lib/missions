<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <input type="text" class="form-control" v-model="search" placeholder="Search for...">
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 text-right">
                <hr class="divider">
                <button class="btn btn-primary" @click="apply">Search</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        callback: Function,
        field: String
    },
    data() {
        return {
            search: null
        }
    },
    methods: {
        apply() {
            this.callback(this.field, this.search);
            this.$emit('apply:filter', this.search);
        }
    }
}
</script>

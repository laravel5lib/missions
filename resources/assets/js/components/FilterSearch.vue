<template>
    <div>
        <input class="form-control" name="search" v-model="search">
        <button class="btn btn-primary" @click="apply">Search</button>
    </div>
</template>
<script>
export default {
    data() {
        return {
            search: null
        }
    },
    methods: {
        apply() {
            this.$emit('apply:filter', search);
        }
    }
}
</script>

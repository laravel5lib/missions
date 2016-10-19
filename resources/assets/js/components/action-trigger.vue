<template>
    <a class="btn btn-primary" @click="execute">{{text}} <i v-if="icon" :class="icon"></i></a>
</template>
<script>
    export default{
        name: 'action-trigger',
        props: {
            text: {
                type: String,
                default: null
            },
            event: {
                type: String,
                default: null
            },
            icon: {
                type: String,
                default: null
            }
        },
        data(){
            return{}
        },
        methods: {
            execute(){
                this.$root.$emit(this.event);
            }
        }
    }
</script>

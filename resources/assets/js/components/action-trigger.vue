<template>
    <a class="btn" :class="type + ' ' + size" @click="execute">{{text}} <i v-if="icon" :class="icon"></i></a>
</template>
<script type="text/javascript">
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
            },
            size: {
                type: String,
                default: null
            },
            type: {
                type: String,
                default: 'btn-primary'
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

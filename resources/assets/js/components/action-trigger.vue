<template>
    <a class="btn btn-primary" :class="size" @click="execute">{{text}} <i v-if="icon" :class="icon"></i></a>
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

<template>
    <span>{{text}}</span>
</template>
<script type="text/javascript">
    export default{
        name: 'listen-text',
        props: {
            text: {
                type: String,
                default: ''
            },
            event: {
                type: String,
                required: true
            }
        },
        data(){
            return{}
        },
        mounted(){
            this.$root.$on(this.event, (val) =>  {
                this.text = val;
            });

        }
    }
</script>

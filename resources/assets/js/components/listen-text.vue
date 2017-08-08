<template>
    {{text}}
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
            this.$root.$on(this.event, function (val) {
                this.text = val;
            }.bind(this));

        }
    }
</script>

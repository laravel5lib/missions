<template>
    <span>{{displayedText}}</span>
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
        computed: {
            displayedText: {
                get() {
                    return this.text;
                },
                set() {}
            }
        },
        mounted(){
            this.$root.$on(this.event, (val) =>  {
                this.displayedText = val;
            });

        }
    }
</script>

<template>
    <progressbar :now="displayNow" label type="success" striped animated style="max-width: 100%;border-radius: 4px;"></progressbar>
</template>
<script type="text/javascript">
    export default{
        name: 'user-profile-fundraisers-progress',
        props: {
            now: {
                type: Number,
                default:0
            }
        },
        data(){
            return{

            }
        },
        computed: {
            displayNow: {
                get() {
                    return Number(this.now);
                },
                set() {}
            }
        },
        mounted() {
            this.$root.$on('Fundraiser::percent', (val) => {
                this.$emit('update:now', Number(val));
                this.displayNow = Number(val);
            });
        }
    }
</script>

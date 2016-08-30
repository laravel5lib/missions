<template>
    <progressbar :now="now" label type="success" striped animated style="max-width: 100%;"></progressbar>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'user-profile-fundraisers-progress',
        components:{ 'progressbar': VueStrap.progressbar },
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
    }
</script>

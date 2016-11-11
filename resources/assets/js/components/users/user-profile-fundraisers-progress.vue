<template>
    <progressbar :now="now" label type="success" striped animated style="max-width: 100%;border-radius: 4px;"></progressbar>
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
    }
</script>

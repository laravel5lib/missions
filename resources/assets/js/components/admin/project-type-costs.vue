<template>
    <div>this is template body</div>
</template>
<script>
    import HeaderComponent from './components/header.vue'
    import OtherComponent from './components/other.vue'
    export default{
        name: 'project-type-costs',
        data() {
            return{
                costs: {}
            }
        },
        components:{
            'other-component':OtherComponent,
            HeaderComponent,
        }
    }
</script>
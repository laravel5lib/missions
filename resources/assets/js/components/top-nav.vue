<template>
    <div>
        <button class="btn btn-success btn-xs" @click="showRight = true">Show Aside on right</button>

        <aside :show.sync="showRight" placement="right" header="Title" :width="350">
            ...
        </aside>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'top-nav',
        components: {'aside': VueStrap.aside},
        data(){
            return{
                showRight: false,
            }
        },
        ready(){

        }
    }
</script>

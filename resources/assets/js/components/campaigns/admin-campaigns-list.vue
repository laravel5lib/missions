<template>
    <div> 
        <div>this is template body</div>
    </div>
</template>
<script type="text/javascript">
    import vSelect from "vue-select";
    export default{
    	name: 'admin-campaigns-list',
        components: {vSelect},
        data(){
            return{
                campaigns: [],
                orderByField: 'surname',
                direction: 1,
                page: 1,
                per_page: 10,
                perPageOptions: [5, 10, 25, 50, 100],
                pagination: {current_page: 1},
                search: '',

            }
        },
        methods: {

        },
        ready(){
            
        }
    }
</script>
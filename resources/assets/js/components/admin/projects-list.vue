<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Projects</h5>
        </div>
        <div class="panel-body">
            ...
        </div>
    </div>
</template>
<script>
    import vSelect from 'vue-select';
    export default {
        name: 'projects-list',
        data() {
            return{
                projects: []
            }
        },
        prop: {
            'id': {
                type: String,
                required: true
            }
        },
        components:{
            'v-select': vSelect,
        }
    }
</script>
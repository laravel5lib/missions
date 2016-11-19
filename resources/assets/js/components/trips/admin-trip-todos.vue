<template>
    <div class="list-group">
        @foreach($trip->todos as $todo)
        <li class="list-group-item">{{$todo}}</li>
        @endforeach
    </div>
</template>
<script type="text/javascript">
    export default{
    	name: 'admin-trip-todos',
        props: ['id'],
        data(){
            return{
                todos:[],
                resource: this.$resource('trips/' + this.id + '/todos'),
            }
        },
        methods:{
            getTodos(){
                this.resource.get().then(function (response) {
                    this.todos = response.data.data;
                })
            }
        },
        ready(){
            
        }
    }
</script>
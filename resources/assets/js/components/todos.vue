<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>ToDos</h5>
        </div>
        <div class="list-group">
            <a href="#" class="list-group-item" v-for="todo in todos">
                <span v-if="todo.completed_at">
                    <i class="fa fa-check-square-o fa-lg" style="margin-right: 10px"></i>
                    <s>{{ todo.task }}</s>
                    <br /><small class="text-muted">Completed on {{ todo.completed_at | moment 'llll' }} by {{ todo.user.data.name }}</small>
                </span>
                <span v-else>
                    <i class="fa fa-square-o fa-lg" style="margin-right: 10px"></i>
                    {{ todo.task }}
                </span>
            </a>
        </div>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'todos',
        components: {
            'alert': VueStrap.alert,
            'modal': VueStrap.modal
        },
        props: {
            'type': {
                type: String
            },
            'id': {
                type: String
            },
            'user_id': {
                type: String
            },
            'per_page': {
                type: Number,
                default: 10
            }
        },
        data() {
            return {
                todos:{},
                selectedTodo: {},
                page: 1,
                pagination: {},
                search: null,
                newMode: false,
                editMode: false,
                showSuccess: false,
                showError: false,
                deleteModal: false,
                message: null
            }
        },
        watch : {
            'page': function (val, oldVal) {
                this.fetch();
            },
            'per_page': function (val, oldVal) {
                this.fetch();
            },
            'search': function (val, oldVal) {
                this.page = 1;
                this.fetch();
            }
        },
        methods: {
            fetch() {
                var params = '?sort=task|desc&include=user&per_page=' + this.per_page + '&page=' + this.page;

                if (this.search) {
                    params = params + '&search=' + this.search;
                }

                if (this.type) {
                    params = params + '&type=' + this.type;
                }

                if (this.type && this.id) {
                    params = params + '|' + this.id;
                }

                this.$http.get('todos' + params).then(function (response) {
                    this.todos = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        ready() {
            this.fetch();
        }
    }
</script>
<template>
<div>
    <alert :show="showSuccess"
           placement="top-right"
           :duration="3000"
           type="success"
           width="400px"
           dismissable>
        <span class="icon-ok-circled alert-icon-float-left"></span>
        <strong>Good job!</strong>
        <p>{{ message }}</p>
    </alert>

    <alert :show="showError"
           placement="top-right"
           :duration="6000"
           type="danger"
           width="400px"
           dismissable>
        <span class="icon-info-circled alert-icon-float-left"></span>
        <strong>Oh No!</strong>
        <p>{{ message }}</p>
    </alert>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>ToDos</h5>
        </div>
        <div class="list-group">
            <div class="list-group-item" v-if="canModify">
                <div class="row">
                    <div class="col-xs-2 col-sm-1 text-muted">
                        <i class="fa fa-lg fa-plus-square-o" style="margin-right: 10px"></i>
                    </div>
                    <div class="col-xs-10 col-sm-11">
                        <input type="text"
                               class="form-control"
                               v-model="newTodo.task"
                               placeholder="What needs to be done?"
                               @keyup.enter="createTodo">
                    </div>
                </div>
            </div>
            <div class="list-group-item" v-if="todos.length < 1">
                <p class="text-center text-muted lead">No tasks found.</p>
            </div>
            <div class="list-group-item todo-item" v-for="todo in todos">
                    <div class="row">
                        <div class="col-xs-2 col-sm-1 text-muted todo-item-checkbox" @click="completeTodo(todo)">
                            <i class="fa fa-lg"
                               :class="{ 'fa-check-square-o' : todo.completed_at, 'fa-square-o' : !todo.completed_at }">
                            </i>
                        </div>
                        <div class="col-xs-9 col-sm-10" v-if="selectedTodo.id == todo.id && editMode">
                            <input type="text"
                                   class="form-control"
                                   v-model="selectedTodo.task"
                                   @blur="updateTodo"
                                   @keyup.enter="updateTodo">
                        </div>
                        <div class="col-xs-9 col-sm-10" @dblclick="editTodo(todo)" v-else>
                            <span :class="{ 'text-strike' : todo.completed_at }">{{ todo.task }}</span>
                            <small class="text-muted" v-if="todo.completed_at"><br />
                                Completed on {{ todo.completed_at | moment('llll') }} by {{ todo.user.data.name }}
                            </small>
                        </div>
                        <div class="col-xs-1 col-sm-1 text-right" v-if="canModify">
                            <i class="fa fa-times fa-lg text-muted remove-todo"
                               @click="selectedTodo = todo,deleteModal = true">
                            </i>
                        </div>
                    </div>
            </div>
        </div>
        <div class="panel-body text-center">
            <nav>
                <ul class="pager">
                    <li :class="{ 'disabled': pagination.current_page == 1 }" class="previous">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo; <span class="hidden-xs">Previous</span></span>
                        </a>
                    </li>
                    <button class="btn btn-default-hollow btn-xs"
                            :class="{ 'btn-primary-hollow' : filterBy == 'all'}"
                            @click="changeFilter('all')">
                        All
                    </button>
                    <button class="btn btn-default-hollow btn-xs"
                            :class="{ 'btn-primary-hollow' : filterBy == 'active'}"
                            @click="changeFilter('active')">
                        Active
                    </button>
                    <button class="btn btn-default-hollow btn-xs"
                            :class="{ 'btn-primary-hollow' : filterBy == 'completed'}"
                            @click="changeFilter('completed')">
                        Completed
                    </button>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }" class="next">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true"><span class="hidden-xs">Next</span> &raquo; </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <p class="text-center"><small class="text-muted">Double-click to edit a todo.</small></p>

    <modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Todo" small="true">
        <div slot="modal-body" class="modal-body text-center">Delete this Todo?</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Keep</button>
            <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedTodo)'>Delete</button>
        </div>
    </modal>
</div>
</template>
<script type="text/javascript">
    export default{
        name: 'todos',
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
            },
            'canModify': {
                type: Number,
                default: 0
            }
        },
        data() {
            return {
                todos: {},
                selectedTodo: {},
                newTodo: {
                    'task': null,
                    'todoable_type': this.type,
                    'todoable_id': this.id
                },
                page: 1,
                pagination: { current_page: 1 },
                search: null,
                newMode: false,
                editMode: false,
                completeMode: false,
                showSuccess: false,
                showError: false,
                deleteModal: false,
                message: null,
                filterBy: 'all'
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
            },
            'filterBy': function (val, oldVal) {
                this.page = 1;
                this.fetch();
            }
        },
        methods: {
            editTodo(todo) {
                if ( ! this.canModify) return;
                this.editMode = true;
                this.selectedTodo = todo;
            },
            completeTodo(todo) {
                this.completeMode = true;

                if (todo.completed_at) {
                    todo.complete = false;
                    this.selectedTodo = todo;
                } else {
                    todo.complete = true;
                    todo.user_id = this.user_id;
                    this.selectedTodo = todo;
                }

                this.updateTodo();
            },
            changeFilter(status) {
                this.filterBy = status;
            },
            createTodo() {
                if (! this.newTodo.task) return;

                this.$http.post('todos', this.newTodo).then(() => {
                    this.newTodo.task = null;
                    this.message = 'Todo created successfully.';
                    this.showSuccess = true;
                    this.fetch();
                },function () {
                    this.message = 'Unable to add the todo.';
                    this.showError = true;
                });
            },
            updateTodo() {
                if (! this.selectedTodo.task) return;
                if (! this.editMode && ! this.completeMode) return;

                this.$http.put('todos/' + this.selectedTodo.id, this.selectedTodo).then(() => {
                    this.editMode = false;
                    this.completeMode = false;
                    this.selectedTodo = {};
                    this.fetch();
                },function () {
                    this.message = 'Unable to update the todo.';
                    this.showError = true;
                });
            },
            remove(todo) {
                this.$http.delete('todos/' + todo.id).then(() => {
                    this.fetch();
                    this.selectedTodo = {};
                    this.message = 'Todo deleted.';
                    this.showSuccess = true;
                },function () {
                    this.message = 'Unable to delete todo.';
                    this.showError = true;
                });
            },
            fetch() {
                var params = '?sort=task&include=user&per_page=' + this.per_page + '&page=' + this.page;

                if (this.filterBy == 'active') {
                    params = params + '&status=incomplete';
                }

                if (this.filterBy == 'completed') {
                    params = params + '&status=complete';
                }

                if (this.search) {
                    params = params + '&search=' + this.search;
                }

                if (this.type) {
                    params = params + '&type=' + this.type;
                }

                if (this.type && this.id) {
                    params = params + '|' + this.id;
                }

                this.$http.get('todos' + params).then((response) => {
                    this.todos = response.data.data;
                    this.pagination = response.data.meta.pagination;
                });
            }
        },
        mounted() {
            this.fetch();
        }
    }
</script>
<style scoped>
    div.list-group-item {
        cursor: pointer;
    }

    .remove-todo {
        display: none;
    }

    div.todo-item:hover i.remove-todo {
        display: inline;
    }

    i.remove-todo:hover {
        color: #d8262e;
    }

    .todo-item-checkbox:hover {
        color: #000;
    }

    .todo-item-checkbox i {
        margin-right: 10px
    }
</style>
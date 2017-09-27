<template>
	<div>
		<spinner ref="spinner" size="md" text="Loading"></spinner>
		<alert v-model="showError"
		       placement="top-right"
		       :duration="6000"
		       type="danger"
		       width="400px"
		       dismissable>
			<span class="icon-info-circled alert-icon-float-left"></span>
			<strong>Oh No!</strong>
			<p>{{ errorMessage }}</p>
		</alert>
		<div class="list-group">
			<div class="list-group-item" v-if="newMode">
				<div class="row">
					<div class="col-xs-2 col-sm-1 text-muted">
						<i class="fa fa-lg fa-plus-square-o" style="margin-right: 10px"></i>
					</div>
					<div class="col-xs-10 col-sm-11">
						<input type="text"
						       class="form-control input-sm"
						       v-model="newTodo"
						       placeholder="What needs to be done?"
						       @keyup.enter="createTodo">
					</div>
				</div>
			</div>
			<div v-for="todo in todos" class="list-group-item">
				<div class="row">
					<div class="col-xs-10">
						{{ todo }}
					</div>
					<div class="col-xs-2 text-right">
						<!--<tooltip content="Edit">
							<i class="fa fa-pencil fa-lg text-muted remove-todo"
							   @click="selectedTodo = todo,editMode = true">
							</i>
						</tooltip>-->
						<tooltip content="Delete">
							<i class="fa fa-trash fa-lg text-muted remove-todo"
							   @click="selectedTodo = todo,deleteModal = true">
							</i>
						</tooltip>
					</div>
				</div>
			</div>
		</div>
		<modal class="text-center" :value="deleteModal" @closed="deleteModal=false" title="Delete Todo" :small="true">
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
    	name: 'admin-trip-todos',
        props: ['id'],
        data(){
            return{
				newTodo: '',
                todos:[],
                resource: this.$resource('trips/' + this.id + '/todos'),
				newMode: false,
				// editMode: false,
				showError: false,
				selectedTodo:null,
				deleteModal: false,
				errorMessage: null
            }
        },
        methods:{
			reset(){
				this.newMode = false;
				this.editMode = false;
				this.newTodo = '';
			},
			createTodo() {
				if (! this.newTodo) return;
				this.todos.push(this.newTodo);
				// this.$refs.spinner.show();
				this.resource.post({}, {todos: this.todos}).then((response) => {
					this.todos = response.data.data;
					this.reset();
					// this.$refs.spinner.hide();
				},() =>  {
					this.errorMessage = 'Unable to delete todo.';
					this.showError = true;
					// this.$refs.spinner.hide();
				});
			},
			remove() {
				this.todos.$remove(this.selectedTodo);
				// this.$refs.spinner.show();
				this.resource.post({}, {todos: this.todos}).then((response) => {
					this.todos = response.data.data;
					this.reset();
					// this.$refs.spinner.hide();
				},() =>  {
					this.errorMessage = 'Unable to delete todo.';
					this.showError = true;
					// this.$refs.spinner.hide();
				});
			},
            getTodos(){
				// this.$refs.spinner.show();
				this.resource.get().then((response) => {
                    this.todos = response.data.data;
					// this.$refs.spinner.hide();
				});
            }
        },
        mounted(){
            this.getTodos();

			var self = this;
			this.$root.$on('NewTodo', () =>  {
				self.newMode = true;
			});

		}
    }
</script>
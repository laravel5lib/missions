<template>
    <div style="position: relative;">
        <spinner ref="spinner" size="md" text="Loading"></spinner>
        <div class="panel-body" v-for="deadline in deadlines">
            <div class="row">
                <div class="col-xs-12 text-right hidden-xs">
                    <a class="btn btn-xs btn-default-hollow small" @click="editDeadline(deadline)"><i class="fa fa-pencil"></i> Edit</a>
                    <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(deadline)"><i class="fa fa-trash"></i> Delete</a>
                </div>
                <div class="col-xs-12 text-center visible-xs">
                    <a class="btn btn-xs btn-default-hollow small" @click="editDeadline(deadline)"><i class="fa fa-pencil"></i> Edit</a>
                    <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(deadline)"><i class="fa fa-trash"></i> Delete</a>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <h5><a href="#">{{ deadline.name|capitalize }}</a></h5>
                    <h6><small>Enforced: {{ deadline.enforced ? 'Yes' : 'No' }}</small></h6>
                </div>
                <div class="col-xs-4 text-right">
                    <h5><i class="fa fa-calendar"></i> {{ deadline.date | moment('lll') }}</h5>
                    <h6><small>Grace Period: {{ deadline.grace_period }} {{ deadline.grace_period > 1 ? 'days' : 'day' }}</small></h6>
                </div>
            </div><!-- end row -->
            <hr class="divider">
        </div>
        <modal class="text-center" :value="showAddModal" @closed="showAddModal=false" title="Add Deadline">
            <div slot="modal-body" class="modal-body">

                    <form name="AddDeadline" data-vv-scope="deadline-add" class="form" novalidate @submit.prevent="addDeadline">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': errors.has('name', 'deadline-add')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="newDeadline.name" class="form-control input-sm"
                                           name="name" v-validate="'required'">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': errors.has('grace', 'deadline-add') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': errors.has('grace', 'deadline-add') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="newDeadline.grace_period"
                                                       name="grace" v-validate="'required|min:0'">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': errors.has('due', 'deadline-add')}">
                                            <label for="date">Due</label>
                                            <date-picker input-sm v-model="newDeadline.date" :view-format="['YYYY-MM-DD HH:mm:ss']" name="due" v-validate="'required'"></date-picker>
                                            <!--<input type="datetime" id="date" class="form-control input-sm hidden"
                                                   v-model="newDeadline.date">-->
                                        </div>

                                    </div>
                                </div>

                                <br>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="newDeadline.enforced">
                                        Enforced?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetDeadline()'>Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" form="AddDeadline">Add</button>
            </div>
        </modal>
        <modal class="text-center" :value="showEditModal" @closed="showEditModal=false" title="Edit Deadline">
            <div slot="modal-body" class="modal-body">

                    <form class="form" novalidate v-if="selectedDeadline" data-vv-scope="deadline-edit">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': errors.has('name', 'deadline-edit')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="selectedDeadline.name" class="form-control input-sm"
                                           name="name" v-validate="'required'">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': errors.has('grace', 'deadline-edit') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': errors.has('grace', 'deadline-edit') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="selectedDeadline.grace_period"
                                                       name="grace" v-validate="'required|min:0'">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': errors.has('due', 'deadline-edit')}">
                                            <label for="date">Due</label>
                                            <date-picker input-sm v-model="selectedDeadline.date" :view-format="['YYYY-MM-DD HH:mm:ss']" name="due" v-validate="'required'"></date-picker>
                                            <!--<input type="datetime" id="date" class="form-control input-sm hidden"
                                                   v-model="selectedDeadline.date">-->
                                        </div>

                                    </div>
                                </div>

                                <br>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" v-model="selectedDeadline.enforced">
                                        Enforced?
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, resetDeadline()'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='updateDeadline'>Update</button>
            </div>
        </modal>
        <modal class="text-center" :value="showDeleteModal" @closed="showDeleteModal=false" title="Delete Deadline" :small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedDeadline">Delete {{ selectedDeadline.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showDeleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='showDeleteModal = false,remove(selectedDeadline)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    import _ from 'underscore'
    export default{
    	name: 'deadlines-manager',
        props: ['id', 'assignment'],
        data(){
            return{
                deadlines:[],
                selectedDeadline: null,
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                attemptedAddDeadline: false,
                attemptedEditDeadline: false,
                search: '',
                newDeadline: {
                    deadline_assignable_id: this.id,
                    deadline_assignable_type: this.assignment,
                    name: '',
                    item_type: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                sort: 'date',
                direction: 'asc'
            }
        },
        methods:{
            resetDeadline(){
                this.newDeadline = {
                    deadline_assignable_id: this.id,
                    deadline_assignable_type: this.assignment,
                    item: '',
                    item_type: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                };
            },
            addDeadline(){
                this.attemptedAddDeadline = true;
                this.$validator.validateAll('deadline-add').then(result => {
                    if (result) {
                        // this.$refs.spinner.show();
                        this.$http.post(`deadlines`, this.newDeadline).then((response) => {
                            this.deadlines.push(response.data.data);
                            this.resetDeadline();
                            this.attemptedAddDeadline = false;
                            this.showAddModal = false;
                            this.searchDeadlines();
                        });
                    }
                })
            },
            updateDeadline(){
                this.attemptedEditDeadline = true;
                this.$validator.validateAll('deadline-edit').then(result => {
                    if(result) {
                        // this.$refs.spinner.show();
                        this.$http.put(`deadlines/${this.selectedDeadline.id}`, this.selectedDeadline).then((response) => {
                            this.attemptedEditDeadline = false;
                            this.showEditModal = false;
                            this.searchDeadlines();
                        })
                    }
                })

            },
            editDeadline(deadline){
                this.selectedDeadline = deadline;
                this.selectedDeadline.date = moment(deadline.date).format('YYYY-MM-DD');
                this.showEditModal = true;
            },
            confirmRemove(deadline) {
                this.selectedDeadline = deadline;
                this.showDeleteModal = true;
            },
            remove(deadline){
                // this.$refs.spinner.show();
                this.$http.delete(`deadlines/${deadline.id}`).then((response) => {
                    this.deadlines.$remove(deadline);
                    this.selectedDeadline = null;
                    this.searchDeadlines();
                });
            },
            debouncedSearch: _.debounce(function() { this.searchDeadlines() }, 250),
            searchDeadlines(){
                // this.$refs.spinner.show();
                this.$http.get(`deadlines`, {
                    assignment: this.assignment + '|' + this.id,
                    search: this.search,
                    sort: this.sort + '|' + this.direction,
                }).then((response) => {
                    this.deadlines = response.data.data;
                    this.$refs.spinner.hide()
                });
            },

        },
        mounted(){
            this.searchDeadlines();
            var self = this;
            this.$root.$on('NewDeadline', () =>  {
                self.showAddModal = true;
            })
        }
    }
</script>
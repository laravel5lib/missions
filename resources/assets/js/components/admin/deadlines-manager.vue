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
                    <h5><a href="#">{{ deadline.name ? deadline.name[0].toUpperCase() + deadline.name.slice(1) : '' }}</a></h5>
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

                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForAddError('name')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="newDeadline.name" class="form-control input-sm"
                                           name="name" v-validate="'required'">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForAddError('grace') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': checkForAddError('grace') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="newDeadline.grace_period"
                                                       name="grace" v-validate="{required: true, min:0}">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForAddError('due')}">
                                            <label for="date">Due</label>
                                            <date-picker :input-sm="true" :model.sync="newDeadline.date|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                            <input type="datetime" id="date" class="form-control input-sm hidden"
                                                   v-model="newDeadline.date" name="due" v-validate="'required'">
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
                <button type="button" class="btn btn-primary btn-sm" @click='addDeadline'>Add</button>
            </div>
        </modal>
        <modal class="text-center" :value="showEditModal" @closed="showEditModal=false" title="Edit Deadline">
            <div slot="modal-body" class="modal-body">

                    <form class="form" novalidate v-if="selectedDeadline">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditError('name')}">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" v-model="selectedDeadline.name" class="form-control input-sm"
                                           name="name" v-validate="'required'">
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForEditError('grace') }">
                                            <label for="grace_period">Grace Period</label>
                                            <div class="input-group input-group-sm" :class="{'has-error': checkForEditError('grace') }">
                                                <input id="grace_period" type="number" class="form-control" number v-model="selectedDeadline.grace_period"
                                                       name="grace" v-validate="{required: true, min:0}">
                                                <span class="input-group-addon">Days</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group" :class="{'has-error': checkForEditError('due')}">
                                            <label for="date">Due</label>
                                            <date-picker :input-sm="true" :model.sync="selectedDeadline.date|moment 'YYYY-MM-DD HH:mm:ss'"></date-picker>
                                            <input type="datetime" id="date" class="form-control input-sm hidden"
                                                   v-model="selectedDeadline.date" name="due" v-validate="'required'">
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
        <modal class="text-center" :value="showDeleteModal" @closed="showDeleteModal=false" title="Delete Deadline" small="true">
            <div slot="modal-body" class="modal-body text-center" v-if="selectedDeadline">Delete {{ selectedDeadline.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showDeleteModal = false'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='showDeleteModal = false,remove(selectedDeadline)'>Delete</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
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
                newDeadline: {
                    deadline_assignable_id: this.id,
                    deadline_assignable_type: this.assignment,
                    name: '',
                    item_type: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                resource: this.$resource('deadlines{/id}'),
                sort: 'date',
                direction: 'asc'
            }
        },
        methods:{
            checkForAddError(field){
                return this.$TripDeadlinesCreate[field.toLowerCase()].invalid && this.attemptedAddDeadline;
            },
            checkForEditError(field){
                return this.$TripDeadlinesEdit[field.toLowerCase()].invalid && this.attemptedEditDeadline;
            },
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
                if(this.$TripDeadlinesCreate.valid) {
                    // this.$refs.spinner.show();
                    this.resource.save({}, this.newDeadline).then(function (response) {
                        this.deadlines.push(response.body.data);
                        this.resetDeadline();
                        this.attemptedAddDeadline = false;
                        this.showAddModal = false;
                        this.searchDeadlines();
                    });
                }
            },
            updateDeadline(){
                this.attemptedEditDeadline = true;
                if(this.$TripDeadlinesEdit.valid) {
                    // this.$refs.spinner.show();
                    this.resource.update({ id: this.selectedDeadline.id}, this.selectedDeadline).then(function (response) {
                        this.attemptedEditDeadline = false;
                        this.showEditModal = false;
                        this.searchDeadlines();
                    })
                }
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
                this.resource.delete({ id: deadline.id }).then(function (response) {
                    this.deadlines.$remove(deadline);
                    this.selectedDeadline = null;
                    this.searchDeadlines();
                });
            },
            searchDeadlines(){
                // this.$refs.spinner.show();
                this.resource.get({
                    assignment: this.assignment + '|' + this.id,
                    search: this.search,
                    sort: this.sort + '|' + this.direction,
                }).then(function (response) {
                    this.deadlines = response.body.data;
                    this.$refs.spinner.hide()
                });
            },

        },
        mounted(){
            this.searchDeadlines();
            var self = this;
            this.$root.$on('NewDeadline', function () {
                self.showAddModal = true;
            })
        }
    }
</script>
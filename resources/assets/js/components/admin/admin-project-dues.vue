<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <!--<button class="btn btn-primary btn-xs" @click="add"><span
					   class="fa fa-plus"></span> Add Existing
			   </button>-->
        <!--<button class="btn btn-primary btn-xs" @click="addNew"><span
                class="fa fa-plus"></span> Create New
        </button>-->

        <!--<hr class="divider sm">-->
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Status</th>
                <th>Cost Name</th>
                <th>Outstanding Balance</th>
                <th>Grace Period</th>
                <th>Due</th>
                <th></th>
            </tr>
            </thead>
            <tbody v-if="project">
            <template v-for="due in project.dues.data">
                <tr>
                    <td class="text-center">
                        <small class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending', }">{{due.status ? due.status[0].toUpperCase() + due.status.slice(1) : ''}}</small>
                    </td>
                    <td>{{ due.cost }}</td>
                    <td>{{ due.balance | currency }}</td>
                    <td>{{ due.grace_period }}</td>
                    <td>{{ due.due_at | moment('ll') }}</td>
                    <td>
                        <a class="btn btn-default btn-xs" @click="edit(due)"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger btn-xs" @click="remove(due)"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <modal title="Add Dues" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800" :callback="addDues">
            <div slot="modal-body" class="modal-body">

                    <form class="for" novalidate>
                        <div class="form-group" :class="{ 'has-error': errors.has('dues') }">
                            <label class="control-label">Available Dues</label>
                            <v-select @keydown.enter.prevent=""  class="form-control" id="user" multiple :value="selectedDues" :options="availableDues"
                                      label="name"></v-select>
                            <select hidden="" v-model="user_id" name="dues" v-validate="'required'" multiple>
                                <option :value="due.id" v-for="due in dues">{{due.name}}</option>
                            </select>
                        </div>
                    </form>

            </div>
        </modal>

        <modal title="Edit Due" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800" :callback="updateDue">
            <div slot="modal-body" class="modal-body">

                    <form class="form" novalidate>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group" :class="{'has-error': checkForEditDueError('grace') }">
                                    <label for="grace_period">Grace Period</label>
                                    <div class="input-group input-group-sm" :class="{'has-error': checkForEditDueError('grace') }">
                                        <input id="grace_period" type="number" class="form-control" number v-model="editedDue.grace_period"
                                               name="grace" v-validate="'required'">
                                        <span class="input-group-addon">Days</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input id="due_date" type="datetime-local" class="form-control" v-model="editedDue.due_at">
                                </div>
                            </div>
                        </div>
                    </form>

            </div>
        </modal>
    </div>
</template>

<script type="text/javascript">
    import vSelect from 'vue-select';
    export default{
        name: 'admin-project-dues',
        props: ['id'],
        components:{ vSelect},
        data(){
            return{
                project: null,
                projectsDues:[],
                dues:[],
                selectedDues: [],
                availableDues: [],
                editedDue: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                newDue: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                resource: this.$resource('projects/' + this.id, { include: 'dues,costs.payments,initiative' }),
                showAddModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,

                preppedProject : {}
            }
        },
        computed:{},
        methods: {
            dateIsBetween(a, b){
                let start = b === 0 ? moment().startOf('month') : moment().add(1, 'month').startOf('month');
                let stop = b === 0 ? moment().endOf('month') : moment().add(1, 'month').endOf('month');
                console.log(moment(a).isBetween(start, stop));
                return moment(a).isBetween(start, stop);
            },
            errors.has(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddDue[field].invalid && this.attemptSubmit;
            },
            checkForEditDueError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditDue[field].invalid && this.attemptSubmit;
            },
            checkForNewDueError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$NewDue[field].invalid && this.attemptSubmit;
            },
            resetDue(){
                this.newDue = {
                    item: '',
                    item_type: '',
                    due_at: null,
                    grace_period: 0,
                    enforced: false,

                };
            },
            isPast(date){
                return moment().isAfter(date);
            },
            add(){
                this.attemptSubmit = false;
                this.showAddModal = true;
            },
            addNew(){
                this.attemptSubmit = false;
                this.showNewModal = true;

            },
            edit(due){
                due.due_at = moment(due).format('YYYY-MM-DDTHH:MM:SS');
                this.editedDue = due;
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            updateDue(){
                console.log(this.editedDue.due_at);
                // prep current dues
                let dues = [];
                _.each(this.project.dues.data, (due) => {
                    if (due.cost_id === this.editedDue.cost_id) {
                        dues.push({ id: this.editedDue.cost_id, grace_period: this.editedDue.grace_period, due_at: this.editedDue.due_at });
                    } else {
                        dues.push({id: due.cost_id, grace_period: due.grace_period});
                    }
                });

                let project = this.preppedProject;
                project.dues = dues;

                return this.doUpdate(project);
            },
            remove(due){
                let project = this.preppedProject;
                project.dues = [];
                _.each(this.project.dues.data, (cs) => {
                    if (cs.due_id !== due.due_id) {
                        project.dues.push({ id: cs.due_id/*, locked: cs.locked*/})
                    }
                });
                console.log(project.dues);

                return this.doUpdate(project);
            },
            addDues(){
                // prep current dues
                let currentDueIds = [];
                _.each(this.project.dues.data, (due) => {
                    currentDueIds.push({ id: due.id || due.due_id, locked: due.locked })
                });

                // prep added dues
                let selectedDueIds = [];
                _.each(this.selectedDues, (due) => {
                    selectedDueIds.push({ id: due.id })
                });

                // merge arrays
                let newDues = _.union(currentDueIds, selectedDueIds);
                // filter possible duplicates
                newDues = _.uniq(newDues);

                let project = this.preppedProject;
                project.dues = newDues;

                return this.doUpdate(project);
            },
            addNew(){
                // get initiative object
                let initiative = this.project.initiative.data;

                // get only ids of current dues so we don't change anything
                initiative.dues = [];
                _.each(this.project.initiative.data.dues.data, (dl) => {
                    initiative.dues.push({id: dl.id});
                });
                initiative.dues.push(this.newDeadline);
                // remove tranformer modified values
                delete initiative.difficulty;
                delete initiative.rep_id;

                // this.$refs.spinner.show();
                this.$http.put('initiatives/' + initiative.id, initiative).then((response) => {
                    let thisTrip = response.data.data;
                    this.selecteddues = new Array(this.newDeadline);
                    // this.$refs.spinner.hide();
                    return this.adddues();

                });
            },

            getDues(search, loading){
                loading(true);

            },
            doUpdate(project){
                // this.$refs.spinner.show();
                return this.resource.update(project).then((response) => {
                    this.setProjectData(response.data.data);
                    this.selectedDues = [];
                    // this.$refs.spinner.hide();
                });
            },
            setProjectData(project){
                this.project = project;
                this.preppedProject = {
                    name: this.project.name,
                    project_initiative_id: this.project.project_initiative_id,
                    sponsor_id: this.project.sponsor_id,
                    sponsor_type: this.project.sponsor_type,
                    plaque_prefix: this.project.plaque_prefix,
                    plaque_message: this.project.plaque_message,
                };

                // get available costs intersect with current
                this.availableCosts = _.filter(project.initiative.data.costs.data, (cost) => {
                    return !_.findWhere(project.costs.data, {cost_id: cost.id, type: 'incremental' || 'optional'})
                });

                // Extend dues data

            }
        },
        mounted(){
            // this.$refs.spinner.show();
            this.resource.get().then((response) => {
                this.setProjectData(response.data.data)
                // this.$refs.spinner.hide();
            });

            //Listen to Event Bus
            this.$root.$on('AdminProject:CostsUpdated', function (data) {
                this.setProjectData(data)
            });

        }
    }
</script>
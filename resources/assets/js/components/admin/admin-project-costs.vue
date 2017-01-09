<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div style="position:relative;">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

        <button class="btn btn-primary btn-xs" @click="add">
            <span class="fa fa-plus"></span> Add Existing
        </button>
        <!--<button class="btn btn-primary btn-xs" @click="addNew"><span
                class="fa fa-plus"></span> Create New
        </button>-->

        <hr class="divider sm">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Locked</th>
                <th>Name</th>
                <th>Type</th>
                <th>Amount</th>
                <th><i class="fa fa-cog"></i></th>
            </tr>
            </thead>
            <tbody v-if="project">
            <template v-for="cost in project.costs.data">
                <tr>
                    <!--<td class="text-center">
                        <small class="badge" :class="{'badge-success': due.status === 'paid', 'badge-danger': due.status === 'late', 'badge-info': due.status === 'extended', 'badge-warning': due.status === 'pending', }">{{due.status|capitalize}}</small>
                    </td>-->
                    <td class="text-muted">

                        <i class="fa fa-lock" v-if="cost.locked" @click="costLocking(cost, false)"></i>
                        <i class="fa fa-unlock" v-else @click="costLocking(cost, true)"></i>
                    </td>
                    <td>{{ cost.name || cost.cost }}</td>
                    <td>{{ cost.type|capitalize}}</td>
                    <td>{{ cost.amount| currency }}</td>
                    <td>
                        <a class="btn btn-danger btn-xs" @click="confirmRemove(cost)"><i class="fa fa-times"></i></a>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>

        <modal title="Add Costs" :show.sync="showAddModal" effect="fade" width="800" :callback="addCosts">
            <div slot="modal-body" class="modal-body">
                <validator name="AddCost">
                    <form class="for" novalidate>
                        <div class="form-group" :class="{ 'has-error': checkForError('costs') }">
                            <label class="control-label">Available Costs</label>
                            <v-select class="form-control" id="user" multiple :value.sync="selectedCosts" :options="availableCosts"
                                      label="name"></v-select>
                            <select hidden="" v-model="user_id" v-validate:costs="{ required: true }" multiple>
                                <option :value="cost.id" v-for="cost in costs">{{cost.name}}</option>
                            </select>
                        </div>
                    </form>
                </validator>
            </div>
        </modal>

        <modal class="text-center" :show.sync="deleteModal" title="Delete Cost" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete {{ selectedCost.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Confirm</button>
            </div>
        </modal>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>{{successMessage}}</p>
        </alert>
    </div>
</template>

<script type="text/javascript">
    import vSelect from 'vue-select';
    export default{
        name: 'admin-project-costs',
        props: ['id'],
        components:{ vSelect },
        data(){
            return{
                project: null,
                projectsCosts:[],
                costs:[],
                selectedCosts: [],
                availableCosts: [],
                editedCost: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                newCost: {
                    name: '',
                    date: null,
                    grace_period: 0,
                    enforced: false,
                },
                selectedCost: null,
                resource: this.$resource('projects/' + this.id, { include: 'dues,costs.payments,initiative.costs.payments' }),
                showAddModal: false,
                deleteModal: false,
                showNewModal: false,
                showEditModal: false,
                attemptSubmit: false,
                showSuccess: false,
                successMessage: '',

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
            checkForError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$AddCost[field].invalid && this.attemptSubmit;
            },
            checkForEditCostError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$EditCost[field].invalid && this.attemptSubmit;
            },
            checkForNewCostError(field) {
                // if user clicked submit button while the field is invalid trigger error styles
                return this.$NewCost[field].invalid && this.attemptSubmit;
            },
            resetCost(){
                this.newCost = {
                    item: '',
                    item_type: '',
                    due_at: null,
                    grace_period: 0,
                    enforced: false,

                };
            },
            costLocking(cost, status) {
                cost.locked = status;
                let costs = [];
                _.each(this.project.costs.data, function (c) {
                    costs.push({id: c.cost_id, locked: c.locked});
                });

                let project = this.preppedProject;
                project.costs = costs;

                return this.doUpdate(project, 'Cost' + (status ? ' locked ' : ' unlocked ') + 'successfully');

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
            edit(cost){
                cost.due_at = moment().format('YYYY-MM-DDTHH:mm:ss');
                this.editedCost = cost;
                this.attemptSubmit = false;
                this.showEditModal = true;
            },
            updateCost(){
                // prep current costs
                let costs = [];
                _.each(this.project.costs.data, function (cost) {
                    if (cost.cost_id === this.editedCost.cost_id) {
                        costs.push({
                            id: this.editedCost.cost_id,
                            locked: this.editedCost.locked,
                            due_at: moment(this.editedCost.due_at).format('YYYY-MM-DD HH:mm:ss'),
                            due_date: moment(this.editedCost.due_at).format('YYYY-MM-DD HH:mm:ss'),
                            grace_period: 10
                        });
                    } else {
                        costs.push({id: cost.cost_id, locked: cost.locked});
                    }
                }.bind(this));

                let project = this.preppedProject;
                project.costs = costs;

                return this.doUpdate(project);
            },
            confirmRemove(cost) {
                this.selectedCost = cost;
                this.deleteModal = true;
            },
            remove(cost){
                let project = this.preppedProject;
                project.costs = [];
                _.each(this.project.costs.data, function (cs) {
                    if (cs.cost_id !== cost.cost_id) {
                        project.costs.push({ id: cs.cost_id, locked: cs.locked})
                    }
                });
                console.log(project.costs);

                return this.doUpdate(project);
            },
            addCosts(){
                // prep current costs
                let currentCostIds = [];
                _.each(this.project.costs.data, function (cost) {
                    currentCostIds.push({ id: cost.id || cost.cost_id, locked: cost.locked })
                });

                // prep added costs
                let selectedCostIds = [];
                _.each(this.selectedCosts, function (cost) {
                    selectedCostIds.push({ id: cost.id })
                });

                // merge arrays
                let newCosts = _.union(currentCostIds, selectedCostIds);
                // filter possible duplicates
                newCosts = _.uniq(newCosts);

                let project = this.preppedProject;
                project.costs = newCosts;

                return this.doUpdate(project);
            },
            addNew(){
                // get initiative object
                let initiative = this.project.initiative.data;

                // get only ids of current costs so we don't change anything
                initiative.costs = [];
                _.each(this.project.initiative.data.costs.data, function (dl) {
                    initiative.costs.push({id: dl.id});
                });
                initiative.costs.push(this.newDeadline);
                // remove transformer modified values
                delete initiative.difficulty;
                delete initiative.rep_id;

                // this.$refs.spinner.show();
                this.$http.put('initiatives/' + initiative.id, initiative).then(function (response) {
                    let thisInitiative = response.data.data;
                    this.selectedcosts = new Array(this.newDeadline);

                    return this.addCosts();

                });
            },

            getCosts(search, loading){
                loading(true);

            },
            doUpdate(project, success){

                // this.$refs.spinner.show();
                return this.resource.update(project).then(function (response) {
                    this.setProjectData(response.data.data);
                    this.selectedCosts = [];
                    this.$root.$emit('AdminProject:CostsUpdated', response.data.data);
                    this.successMessage = success || 'Costs updated Successfully';
                    this.showSuccess = true;
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
                this.availableCosts = _.filter(project.initiative.data.costs.data, function (cost) {
                    return !_.findWhere(project.costs.data, {cost_id: cost.id, type: 'incremental' || 'optional'})
                });
            }
        },
        ready(){
            // this.$refs.spinner.show();
            this.resource.get().then(function (response) {
                this.setProjectData(response.data.data);
                // this.$refs.spinner.hide();
            });

        }
    }
</script>
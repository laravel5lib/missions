<template xmlns:v-validate="http://www.w3.org/1999/xhtml">
    <div>
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="row">
            <div class="col-xs-4">
                <button class="btn btn-primary btn-sm" @click="add">
                    <span class="fa fa-plus"></span> Add <span class="hidden-xs">Optional</span> Costs
                </button>
            </div>
            <div class="col-xs-8 text-right" v-if="project && listedCosts !== project.costs.data">
                <button class="btn btn-default btn-sm" @click="revert">
                    <i class="fa fa-refresh"></i> <span class="hidden-xs">Revert Changes</span>
                </button>
                <button class="btn btn-primary btn-sm" @click="doUpdate">
                    <i class="fa fa-save"></i> <span class="hidden-xs">Save Changes</span>
                </button>
            </div>
        </div>

        <hr class="divider inv sm">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Applied Costs</h5>
            </div><!-- end panel-heading -->

            <div class="list-group">
                <div class="list-group-item" v-for="cost in listedCosts" :class="{'list-group-item-default': cost.unsaved}">
                    <div class="row" v-if="cost.type === 'optional'">
                        <div class="col-xs-12">
                            <a class="btn btn-xs btn-default-hollow pull-right" @click="confirmRemove(cost)"><i class="fa fa-trash"></i> <span class="hidden-xs">Remove</span></a>
                            <span v-if="cost.unsaved" class="label label-danger">Unsaved</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Name</label>
                            <p>{{ cost.name }}</p>
                            <hr class="divider inv hidden-lg">
                        </div>
                        <div class="col-md-3">
                            <label>Amount</label>
                            <p>{{ cost.amount|currency }}</p>
                            <hr class="divider inv hidden-lg">
                        </div>
                        <div class="col-md-6">
                            <label>Description</label>
                            <p>{{ cost.description }}</p>
                            <hr class="divider inv hidden-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- end panel -->

        <modal title="Add Costs" :show.sync="showAddModal" effect="fade" width="800" :callback="addCosts" ok-text="Add">
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
            <div slot="modal-body" class="modal-body text-center" v-if="selectedCost">Are you sure you want to delete {{ selectedCost.name }}?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,remove(selectedCost)'>Confirm</button>
            </div>
        </modal>

    </div>
</template>

<script type="text/javascript">
    import vSelect from 'vue-select';
    export default{
        name: 'project-costs',
        props: ['id'],
        components: {vSelect},
        data(){
            return {
                project: null,
                projectsCosts: [],
                costs: [],
                selectedCosts: [],
                availableCosts: [],
                selectedCost: null,
                resource: this.$resource('projects/' + this.id, {include: 'dues,costs.payments,initiative.costs.payments'}),
                showAddModal: false,
                deleteModal: false,
                showNewModal: false,
                attemptSubmit: false,

                listedCosts: [],
                originalCosts: [],
                temporaryCosts: [],

                preppedProject: {}
            }
        },
        watch: {
            'temporaryCosts': function (val) {
                //debugger;
            }
        },
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
            confirmRemove(cost) {
                this.selectedCost = cost;
                this.deleteModal = true;
            },
            remove(){
                let temporaryDues = [];
                let res = jQuery.extend(true, {}, this.project);

                // remove cost from temporary array
                if (this.selectedCost.unsaved) {
                    this.temporaryCosts = _.reject(this.temporaryCosts, function (tempCost) {
                        if (tempCost === this.selectedCost) {
                            return true;
                        } else {
                            // generate due based on added costs' payments
                            temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(this.selectedCost));
                        }
                    }.bind(this));

                    temporaryDues = _.uniq(temporaryDues);
                    this.temporaryCosts = _.uniq(this.temporaryCosts);
                } else {
                    this.project.costs.data = _.reject(this.project.costs.data, function (tempCost) {
                        if (tempCost === this.selectedCost) {
                            res.dues.data = _.reject(res.dues.data, function (due) {
                                return due.cost === tempCost.name;
                            });
                            return true;
                        }
                    }.bind(this));

                    temporaryDues = _.uniq(temporaryDues);
                    this.temporaryCosts = _.uniq(this.temporaryCosts);
                }

                // Merge lists and display
                this.listedCosts = _.union(this.temporaryCosts, this.project.costs.data);
                this.listedCosts = _.uniq(this.listedCosts);

                // cleanup Add Costs Modal
                this.getAvailableCosts();

                //Add temporary dues
                res.dues.data = _.union(res.dues.data, temporaryDues);
                this.$root.$emit('Project:CostsUpdated', res);

            },
            addCosts(){
                let temporaryDues = [];
                let res = jQuery.extend(true, {}, this.project);

                // Add selected costs to temporary list
                _.each(this.selectedCosts, function (cost) {
                    cost.unsaved = true;
                    this.temporaryCosts.push(cost);
                    // generate due based on added costs' payments
                    temporaryDues = _.union(temporaryDues, this.generatePaymentsFromCost(cost));
                }.bind(this));
                temporaryDues = _.uniq(temporaryDues);
                this.temporaryCosts = _.uniq(this.temporaryCosts);

                // Merge lists and display
                this.listedCosts = _.union(this.temporaryCosts, this.project.costs.data);
                this.listedCosts = _.uniq(this.listedCosts);

                // cleanup Add Costs Modal
                this.getAvailableCosts();

                //Add temporary dues
                res.dues.data = _.union(res.dues.data, temporaryDues);
                this.$root.$emit('Project:CostsUpdated', res);
            },
            generatePaymentsFromCost(cost){
                let temporaryDues = [];
                // generate due based on added costs' payments
                _.each(cost.payments.data, function (payment) {
                    temporaryDues.push({
                        amount: payment.amount_owed,
                        balance: payment.balance_due,
                        cost: cost.name,
                        due_at: payment.due_at,
                        grace_period: payment.grace_period,
                        status: 'pending',
                        unsaved: true
                    });
                });

                return temporaryDues;
            },
            doUpdate(){
                let costIds = [];

                _.each(this.listedCosts, function (cost) {
                    costIds.push({id: cost.id || cost.cost_id, locked: cost.locked})
                });

                let res = jQuery.extend(true, {}, this.project);
                res.costs = costIds

                return this.resource.update(res).then(function (response) {
                    this.setProjectData(response.data.data);
                    this.selectedCosts = [];
                    this.temporaryCosts = [];
                    this.$dispatch('showSuccess', 'Costs updated successfully.');
                });
            },
            setProjectData(project){
                this.project = project;
                this.$root.$emit('Project:CostsUpdated', project);
                this.preppedProject = {
                    name: this.project.name,
                    project_initiative_id: this.project.project_initiative_id,
                    sponsor_id: this.project.sponsor_id,
                    sponsor_type: this.project.sponsor_type,
                    plaque_prefix: this.project.plaque_prefix,
                    plaque_message: this.project.plaque_message,
                };

                this.getAvailableCosts();
                this.listedCosts = project.costs.data;
                this.originalCosts = jQuery.extend(true, {}, this.listedCosts);
            },
            getAvailableCosts(){
                this.selectedCosts = [];

                // get available optional costs intersect with current
                this.availableCosts = _.filter(this.project.initiative.data.costs.data, function (cost) {
                    if (!_.findWhere(this.project.costs.data, {cost_id: cost.id}) && !_.findWhere(this.temporaryCosts, {id: cost.id}) && cost.type === 'optional') {
                        cost.removal = false;
                        return true;
                    };
                }.bind(this));
            },
            revert(){
                this.temporaryCosts = [];
                this.resource.get().then(function (response) {
                    this.setProjectData(response.data.data);
                });
            },
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
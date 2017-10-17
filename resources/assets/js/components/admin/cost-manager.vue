<template>
    <section>
        <spinner ref="spinner" size="xl" :fixed="true" text="Loading..."></spinner>
        <form class="panel-body form-inline" novalidate>
            <div class="form-group">
                <select class="form-control input-sm" v-model="filters.type" style="width:100%;">
                    <option value="">Filter by any type...</option>
                    <option value="incremental">Incremental</option>
                    <option value="optional">Optional</option>
                    <option value="static">Static</option>
                </select>
            </div>
            <div class="form-group">
                <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for anything" style="min-width: 200px">
                </div>
            </div>
            <a class="btn btn-primary btn-sm pull-right"
               @click="showAddModal=true"
               v-if="app.user.can.create_costs">
               Add New Cost
           </a>
        </form>
        <hr class="divider sm">
        <template v-for="(cost, index) in costs">
            <div class="panel-body" :class="{ 'panel-warning': costsErrors[index] != false, 'panel-success': costsErrors[index] === false }">
                <div class="row">
                    <div class="col-sm-2 col-xs-12">
                        <h5 class="text-primary">{{ currency(cost.amount) }}</h5>
                    </div>
                    <div class="col-sm-6 col-xs-6">
                        <h5>{{ cost.name|capitalize }}</h5>
                    </div>
                    <div class="col-sm-4 col-xs-6 text-right">
                        <div style="padding: 0;">
                            <div class="btn-group btn-group-sm">
                              <button type="button" class="btn btn-sm btn-link dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-h"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-right">
                                <li><a @click="addPayment(cost)"
                                   v-if="app.user.can.create_costs || app.user.can.update_costs">
                                   Add New Payment
                                </a></li>
                                <li class="divider"></li>
                                <li><a @click="editCost(cost)"
                                   v-if="app.user.can.update_costs">
                                   Edit
                                </a></li>
                                <li><a @click="confirmRemove(cost)"
                                   v-if="app.user.can.delete_costs">
                                   Delete
                                </a></li>
                              </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <p><span class="label label-default">{{ cost.type|capitalize }}</span></p>
                    </div>
                    <div class="col-sm-10">
                        <p class="small"><i class="fa fa-calendar-o"></i> Effective Date: <em class="text-primary">{{ cost.active_at|moment('lll') }}</em></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="small">{{ cost.description }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="alert alert-info alert-dismissible" role="alert" v-if="isOutOfSync(cost)">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Note!</strong> Please, check payments.
                </div>
                <payment-manager v-if="cost.payments" :id="cost.id" :cost="cost" :payments="cost.payments.data"></payment-manager>
            </div>
            <hr class="divider">
        </template>
        <template v-if="costs.length < 1">
            <div class="panel-body">
                <p class="lead text-muted text-center">No costs have been assigned yet.</p>
            </div>
        </template>

        <modal title="Add New Cost" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <template v-if="!selectedCost">

                        <form class="form" novalidate data-vv-scope="cost-create">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group" :class="{'has-error': errors.has('costName', 'cost-create')}">
                                        <label for="cost_name">Name</label>
                                        <input type="text" class="form-control" id="cost_name"
                                               v-model="newCost.name" name="costName" v-validate="'required'"
                                               placeholder="Name" autofocus>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costType', 'cost-create')}">
                                        <label for="cost_type">Type</label>
                                        <select id="cost_type" class="form-control" v-model="newCost.type" name="costType" v-validate="'required'">
                                            <option value="">-- select --</option>
                                            <option value="static">Static</option>
                                            <option value="incremental">Incremental</option>
                                            <option value="optional">Optional</option>
                                        </select>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costAmount', 'cost-create')}">
                                        <label for="newCost_amount">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="number" number id="newCost_amount" class="form-control"
                                                   v-model="newCost.amount" name="costAmount" v-validate="'required'">
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costActive', 'cost-create')}">
                                        <label for="newCost_active_at">Effective Date</label>
                                        <br>
                                        <date-picker v-model="newCost.active_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="costActive" v-validate="'required'"></date-picker>
                                        <span class="help-block">This is when the cost goes into effect and will be applied. You should stagger this date for sets of incremental costs.</span>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costDescription', 'cost-create')}">
                                        <label for="cost_description">Short Description</label>
                                        <textarea class="form-control" id="cost_description"
                                                  v-model="newCost.description" name="costDescription" v-validate="'required|min:1'"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>

                </template>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-link" @click='showAddModal = false, resetCost()'>Cancel</button>
                <button type="button" class="btn btn-primary" @click='addCost(newCost)'>Add</button>
            </div>

        </modal>
        <modal title="Edit Cost" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <template v-if="selectedCost">

                        <form class="form" novalidate data-vv-scope="cost-edit">
                            <div class="row">
                                <div class="col-sm-10 col-sm-offset-1">
                                    <div class="form-group" :class="{'has-error': errors.has('costName', 'cost-edit')}">
                                        <label for="cost_name">Name</label>
                                        <input type="text" class="form-control" id="cost_name"
                                               v-model="selectedCost.name" name="costName" v-validate="'required'"
                                               placeholder="Name" autofocus>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costType', 'cost-edit')}">
                                        <label for="cost_type">Type</label>
                                        <select id="cost_type" class="form-control" v-model="selectedCost.type" name="costType" v-validate="'required'">
                                            <option value="">-- select --</option>
                                            <option value="static">Static</option>
                                            <option value="incremental">Incremental</option>
                                            <option value="optional">Optional</option>
                                        </select>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costActive', 'cost-edit')}">
                                        <label for="selectedCost_active_at">Effective Date</label>
                                        <br>
                                        <date-picker v-model="selectedCost.active_at" :view-format="['YYYY-MM-DD HH:mm:ss']" data-vv-value-path="model" name="costActive" v-validate="'required'"></date-picker>
                                        <span class="help-block">This is when the cost goes into effect and will be applied. You should stagger this date for sets of incremental costs.</span>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costAmount', 'cost-edit')}">
                                        <label for="selectedCost_amount">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="number" number id="selectedCost_amount" class="form-control"
                                                   v-model="selectedCost.amount" name="costAmount" v-validate="'required'">
                                        </div>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costDescription', 'cost-edit')}">
                                        <label for="cost_description">Description</label>
                                        <textarea class="form-control" id="cost_description"
                                                  v-model="selectedCost.description" name="costDescription" v-validate="'required|min:1'"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>


                </template>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, selectedCost = null'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='updateCost(selectedCost)'>Update</button>
            </div>

        </modal>
        <modal title="Delete Cost" :value="showDeleteModal" @closed="showDeleteModal=false" effect="fade" :small="true">
            <div slot="modal-body" class="modal-body">
                <p v-if="selectedCost" class="text-center">Delete {{ selectedCost.name }}?</p>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showDeleteModal = false,selectedCost = null'>Keep</button>
                <button type="button" class="btn btn-primary btn-sm" @click='doRemove(selectedCost)'>Delete</button>
            </div>
        </modal>

    </section>
</template>
<script type="text/javascript">
    import _ from 'underscore';
    import paymentManager from './payment-manager.vue'
    import importUtility from '../import-utility.vue'
    export default{
        name: 'cost-manager',
        props: ['id', 'assignment'],
        components: { paymentManager, importUtility },
        data(){
            return {
                app: MissionsMe,
                costs: [],
                selectedCost: null,
                showReminder: null,
                attemptedAddCost: false,
                costsErrors:[],
                unSyncedCosts: [],

                newCost: {
                    cost_assignable_id: this.id,
                    cost_assignable_type: this.assignment,
                    name: '',
                    description: '',
                    active_at: '',
                    amount: 0,
                    type: '',
                    payments: [],
                    toggleNewPayment: false
                },
                showFilters: false,
                showAddModal: false,
                showEditModal: false,
                showDeleteModal: false,
                filters: {
                    type: '',
                },
                search: '',
                sort: 'active_at',
                importRequiredFields: [
                    'name', 'amount', 'type', 'active_at'
                ],
                importOptionalFields: [
                    'description', 'created_at', 'updated_at', 'payments'
                ]
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler(val, oldVal) {
                    // console.log(val);
                    this.searchCosts();
                },
                deep: true
            },

            'search'(val, oldVal) {
                // this.searchCosts();
            },

            'showEditModal'(val, oldVal) {
                this.$nextTick(() =>  {
                    if (val !== oldVal && val === false) {
                        this.selectedCost = null;
                    }
                })
            },
            'showDeleteModal'(val, oldVal) {
                this.$nextTick(() =>  {
                    // overide modal close issue
                    if (val !== oldVal && val === false) {
                        $('body').css({'overflow-y': 'auto'});
                    }
                })
            }
        },
        methods: {
            checkCostsErrors(){
                var errors = [];
                this.costs.forEach(function (cost, index) {
                    // cost must have at least 1 payment
                    if (!cost.payments.length) {
                        errors.push('empty');
                    } else {
                        // cost payments must total full amount owed and percent owed
                        var amount = 0;
                        cost.payments.forEach(function (payment, index) {
                            amount += parseFloat(payment.amount_owed);
                        }, this);
                        // evaluate difference
                        if (amount != cost.amount) {
                            errors.push('incomplete');
                        }
                    }

                    // no errors
                    errors.push(false);
                }, this);
                this.costsErrors = errors;
            },
            resetCost(){
                this.newCost = {
                    cost_assignable_id: this.id,
                    cost_assignable_type: this.assignment,
                    name: '',
                    description: '',
                    active_at: '',
                    amount: 0,
                    type: '',
                    payments: [],
                    toggleNewPayment: false
                }
            },
            editCost(cost){
                this.selectedCost = cost;
                this.selectedCost.active_at = moment(cost.active_at).format('YYYY-MM-DD');
                this.showEditModal = true;
            },
            confirmRemove(cost) {
                this.selectedCost = cost;
                this.showDeleteModal = true;
            },
            doRemove(cost){
                this.$http.delete(`costs/${cost.id}`).then((response) => {
                    this.selectedCost = null;
                    this.showDeleteModal = false;
                    this.costs.$remove(cost);
                });
            },
            addCost(){
                this.attemptedAddCost = true;
                this.$validator.validateAll('cost-create').then(result => {
                    if (result) {
                        this.$http.post(`costs`, this.newCost, { include: 'payments'}).then((response) => {
                            this.costs.push(response.data.data);
                            this.resetCost();
                            this.showAddModal = false;
                            this.attemptedAddCost = false;
                            this.searchCosts();
                        }, (error) =>  {
                            console.log(error.data.errors);
                            // this.$refs.spinner.hide();
                        });
                    }
                });

                this.checkCostsErrors();
            },
            updateCost(){
                this.attemptedAddCost = true;
                this.$validator.validateAll('cost-edit').then(result => {
                    if (result) {
                        this.$http.put(`costs/${this.selectedCost.id}?include=payments`, this.selectedCost).then((response) => {
                            this.showReminder = response.data.data.id;
                            $.extend(this.costs, this.selectedCost);
                            this.selectedCost = null;
                            this.attemptedAddCost = false;
                            this.showEditModal = false;
                            this.searchCosts();
                        }, (error) => {
                            console.log(error.data.errors);
                            // this.$refs.spinner.hide();
                        });
                    }
                })
                this.checkCostsErrors();
            },
            resetFilter(){
                this.search = '';
                this.sort = 'active_at';
                this.filters = {
                    type: ''
                }
            },
            debouncedSearch: _.debounce(function () { this.searchCosts() }, 250),
            searchCosts(){
                // this.$refs.spinner.show();
                this.$http.get('costs', { params: {
                    include: 'payments',
                    assignment: this.assignment + '|' + this.id,
                    search: this.search,
                    sort: this.sort,
                    type: this.filters.type
                }}).then((response) => {
                    this.costs = response.data.data;
                    // this.$refs.spinner.hide();
                    this.checkPaymentsSync();
                });
            },
            addPayment(cost){
                this.$root.$emit('Cost:' + cost.id + ':NewPayment')
            },
            checkPaymentsSync(){
                let arr = [];
                _.each(this.costs, (cost) => {
                    let t = 0;
                    _.each(cost.payments.data, (payment) => {
                        t += parseFloat(payment.amount_owed);
                    });
                    if (parseFloat(cost.amount) !== parseFloat(t)) {
                        this.unSyncedCosts.push(cost.id);
                    }
                });
                this.unSyncedCosts = _.uniq(this.unSyncedCosts);
            },
            isOutOfSync(cost){
                return _.contains(this.unSyncedCosts, cost.id);
            }
        },
        mounted(){
            this.searchCosts();

            let self = this;
            this.$root.$on('CheckPaymentsSync', () =>  {
                self.checkPaymentsSync();
            });
            this.$root.$on('SpinnerOn', () =>  {
                self.$refs.spinner.show();
            });
            this.$root.$on('SpinnerOff', () =>  {
                self.$refs.spinner.hide();
            });
        }
    }
</script>
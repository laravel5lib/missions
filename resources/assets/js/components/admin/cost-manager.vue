<template>
    <section>
        <spinner ref="spinner" size="md" text="Loading"></spinner>
        <mm-aside :show="showFilters" @open="showFilters=true" @close="showFilters=false" placement="left" header="Filters" :width="375">
            <hr class="divider inv sm">
            <form class="col-sm-12">
                <div class="form-group">
                    <select class="form-control input-sm" v-model="filters.type" style="width:100%;">
                        <option value="">Any Type</option>
                        <option value="incremental">Incremental</option>
                        <option value="optional">Optional</option>
                        <option value="static">Static</option>
                    </select>
                </div>

                <hr class="divider inv sm">
                <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter"><i class="fa fa-times"></i> Reset Filters It!</button>
            </form>
        </mm-aside>
        <form class="panel-body form-inline text-right" novalidate>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" v-model="search" @keyup="debouncedSearch" placeholder="Search for anything">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
            <button class="btn btn-default btn-sm" type="button" @click="showFilters=true">Filters</button>
            <!-- <import-utility title="Import Costs" 
              url="costs/import"
              :required-fields="importRequiredFields"
              :optional-fields="importOptionalFields">
            </import-utility> -->
            <a class="btn btn-primary btn-sm" @click="showAddModal=true">New <i class="fa fa-plus"></i></a>
        </form>
        <hr class="divider sm">
        <template v-for="(cost, index) in costs">
            <div class="panel-body" :class="{ 'panel-warning': costsErrors[index] != false, 'panel-success': costsErrors[index] === false }">
                <div class="row">
                    <div class="col-sm-6">
                        <h4>{{ cost.name|capitalize }}</h4>
                    </div>
                    <div class="col-sm-6 text-right hidden-xs">
                        <div style="padding: 0;">
                            <div role="group" aria-label="...">
                                <a class="btn btn-xs btn-default-hollow small" @click="addPayment(cost)"><i class="fa fa-plus"></i> New Payment</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="editCost(cost)"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(cost)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 text-center visible-xs">
                        <div style="padding: 0;">
                            <div role="group" aria-label="...">
                                <a class="btn btn-xs btn-default-hollow small" @click="addPayment(cost)"><i class="fa fa-plus"></i> New Payment</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="editCost(cost)"><i class="fa fa-pencil"></i> Edit</a>
                                <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(cost)"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="small">{{ cost.description }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4 text-center">
                        <label>Cost Type</label>
                        <p>{{ cost.type|capitalize }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>Active Date</label>
                        <p>{{ cost.active_at|moment('lll') }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>Cost</label>
                        <p>{{ currency(cost.amount) }}</p>
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

        <modal title="New Cost" :value="showAddModal" @closed="showAddModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <template v-if="!selectedCost">

                        <form class="form" novalidate data-vv-scope="cost-create">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" :class="{'has-error': errors.has('costName', 'cost-create')}">
                                        <label for="cost_name">Name</label>
                                        <input type="text" class="form-control" id="cost_name"
                                               v-model="newCost.name" name="costName" v-validate="'required'"
                                               placeholder="Name" autofocus>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costDescription', 'cost-create')}">
                                        <label for="cost_description">Description</label>
                                        <textarea class="form-control" id="cost_description"
                                                  v-model="newCost.description" name="costDescription" v-validate="'required|min:1'"></textarea>
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
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('costActive', 'cost-create')}">
                                                <label for="newCost_active_at">Active</label>
                                                <br>
                                                <date-picker input-sm v-model="newCost.active_at" :view-format="['YYYY-MM-DD HH:mm:ss']" name="costActive" v-validate="'required'"></date-picker>
                                                <!--<input type="datetime" id="newCost_active_at" class="form-control hidden"
                                                       v-model="newCost.active_at" name="costActive" v-validate="'required'">-->
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('costAmount', 'cost-create')}">
                                                <label for="newCost_amount">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                    <input type="number" number id="newCost_amount" class="form-control"
                                                           v-model="newCost.amount" name="costAmount" v-validate="'required'">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                </template>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetCost()'>Cancel</button>
                <button type="button" class="btn btn-primary btn-sm" @click='addCost(newCost)'>Add</button>
            </div>

        </modal>
        <modal title="Edit Cost" :value="showEditModal" @closed="showEditModal=false" effect="fade" width="800">
            <div slot="modal-body" class="modal-body">
                <template v-if="selectedCost">

                        <form class="form" novalidate data-vv-scope="cost-edit">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group" :class="{'has-error': errors.has('costName', 'cost-edit')}">
                                        <label for="cost_name">Name</label>
                                        <input type="text" class="form-control" id="cost_name"
                                               v-model="selectedCost.name" name="costName" v-validate="'required'"
                                               placeholder="Name" autofocus>
                                    </div>
                                    <div class="form-group" :class="{'has-error': errors.has('costDescription', 'cost-edit')}">
                                        <label for="cost_description">Description</label>
                                        <textarea class="form-control" id="cost_description"
                                                  v-model="selectedCost.description" name="costDescription" v-validate="'required|min:1'"></textarea>
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
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('costActive', 'cost-edit')}">
                                                <label for="selectedCost_active_at">Active</label>
                                                <br>
                                                <date-picker input-sm v-model="selectedCost.active_at" :view-format="['YYYY-MM-DD HH:mm:ss']" data-vv-value-path="model" name="costActive" v-validate="'required'"></date-picker>
                                                <!--<input type="datetime" id="selectedCost_active_at" class="form-control hidden"
                                                       v-model="selectedCost.active_at" name="costActive" v-validate="'required'">-->
                                            </div>

                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group" :class="{'has-error': errors.has('costAmount', 'cost-edit')}">
                                                <label for="selectedCost_amount">Amount</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                                    <input type="number" number id="selectedCost_amount" class="form-control"
                                                           v-model="selectedCost.amount" name="costAmount" v-validate="'required'">
                                                </div>
                                            </div>
                                        </div>
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

        <!--<div class="modal fade" tabindex="-1" role="deleteDialog" :show="showDeleteModal">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" @click="showDeleteModal = false,selectedCost = null"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Delete Cost</h4>
                    </div>
                    <div class="modal-body">
                        <p v-if="selectedCost" class="text-center">Delete {{ selectedCost.name }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm" @click='showDeleteModal = false,selectedCost = null'>Keep</button>
                        <button type="button" class="btn btn-primary btn-sm" @click='doRemove(selectedCost)'>Delete</button>
                    </div>
                </div>&lt;!&ndash; /.modal-content &ndash;&gt;
            </div>&lt;!&ndash; /.modal-dialog &ndash;&gt;
        </div>&lt;!&ndash; /.modal &ndash;&gt;-->

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
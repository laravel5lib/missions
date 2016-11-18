<template>
    <spinner v-ref:spinner size="md" text="Loading"></spinner>
    <aside :show.sync="showFilters" placement="left" header="Filters" :width="375">
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
            <button class="btn btn-default btn-sm btn-block" type="button" @click="resetFilter()"><i class="fa fa-times"></i> Reset Filters</button>
        </form>
    </aside>
    <form class="form-inline text-right" novalidate>
        <div class="input-group input-group-sm">
            <input type="text" class="form-control" v-model="search" debounce="250" placeholder="Search for anything">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
        </div>
        <button class="btn btn-default btn-sm" type="button" @click="showFilters=true">Filters</button>
        <a class="btn btn-primary btn-sm" @click="showAddModal=true">New <i class="fa fa-plus"></i></a>
    </form>
    <hr class="divider sm">
    <template v-for="cost in costs">
        <div class="panel-body" :class="{ 'panel-warning': costsErrors[$index] != false, 'panel-success': costsErrors[$index] === false }">
            <div class="row">
                <div class="col-sm-8">
                    <h4>{{ cost.name|capitalize }}</h4>
                </div>
                <div class="col-sm-4 text-right hidden-xs">
                    <div style="padding: 0;">
                        <div role="group" aria-label="...">
                            <a class="btn btn-xs btn-default-hollow small" @click="editCost(cost)"><i class="fa fa-pencil"></i> Edit</a>
                            <a class="btn btn-xs btn-default-hollow small" @click="confirmRemove(cost)"><i class="fa fa-trash"></i> Delete</a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 text-center visible-xs">
                    <div style="padding: 0;">
                        <div role="group" aria-label="...">
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
                    <p>{{ cost.active_at|moment 'll' }}</p>
                </div>
                <div class="col-sm-4 text-center">
                    <label>Cost</label>
                    <p>{{ cost.amount|currency }}</p>
                </div>
            </div>
            <hr class="divider">
            <admin-trip-costs-payments :id="cost.id" :payments.sync="cost.payments.data"></admin-trip-costs-payments>
        </div>
        <hr class="divider">
    </template>

    <modal title="New Cost" :show.sync="showAddModal" effect="fade" width="800">
        <div slot="modal-body" class="modal-body">
            <validator name="TripPricingCost" v-if="!selectedCost">
                <form class="form" novalidate>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costName')}">
                                <label for="cost_name">Name</label>
                                <input type="text" class="form-control" id="cost_name"
                                       v-model="newCost.name" v-validate:costName="{required: true}"
                                       placeholder="Name" autofocus>
                            </div>
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costDescription')}">
                                <label for="cost_description">Description</label>
                                <textarea class="form-control" id="cost_description"
                                          v-model="newCost.description" v-validate:costDescription="{required: true, minlength:1}"></textarea>
                            </div>
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costType')}">
                                <label for="cost_type">Type</label>
                                <select id="cost_type" class="form-control" v-model="newCost.type" v-validate:costType="{ required: true }">
                                    <option value="">-- select --</option>
                                    <option value="static">Static</option>
                                    <option value="incremental">Incremental</option>
                                    <option value="optional">Optional</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForErrorCost('costActive')}">
                                        <label for="newCost_active_at">Active</label>
                                        <br>
                                        <datepicker :value.sync="newCost.active_at" format="yyyy-MM-dd" :clear-button="true">
                                        </datepicker>
                                        <input type="date" id="newCost_active_at" class="form-control hidden"
                                               v-model="newCost.active_at" v-validate:costActive="{required: true}">
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForErrorCost('costAmount')}">
                                        <label for="newCost_amount">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="number" number id="newCost_amount" class="form-control"
                                                   v-model="newCost.amount" v-validate:costAmount="{required: true, min: 1}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </validator>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='showAddModal = false, resetCost()'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='addCost(newCost)'>Add</button>
        </div>

    </modal>
    <modal title="Edit Cost" :show.sync="showEditModal" effect="fade" width="800">
        <div slot="modal-body" class="modal-body">
            <validator name="TripPricingCost" v-if="selectedCost">
                <form class="form" novalidate>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costName')}">
                                <label for="cost_name">Name</label>
                                <input type="text" class="form-control" id="cost_name"
                                       v-model="selectedCost.name" v-validate:costName="{required: true}"
                                       placeholder="Name" autofocus>
                            </div>
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costDescription')}">
                                <label for="cost_description">Description</label>
                                <textarea class="form-control" id="cost_description"
                                          v-model="selectedCost.description" v-validate:costDescription="{required: true, minlength:1}"></textarea>
                            </div>
                            <div class="form-group" :class="{'has-error': checkForErrorCost('costType')}">
                                <label for="cost_type">Type</label>
                                <select id="cost_type" class="form-control" v-model="selectedCost.type" v-validate:costType="{ required: true }">
                                    <option value="">-- select --</option>
                                    <option value="static">Static</option>
                                    <option value="incremental">Incremental</option>
                                    <option value="optional">Optional</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForErrorCost('costActive')}">
                                        <label for="selectedCost_active_at">Active</label>
                                        <br>
                                        <datepicker :value.sync="selectedCost.active_at" format="yyyy-MM-dd" :clear-button="true">
                                        </datepicker>
                                        <input type="date" id="selectedCost_active_at" class="form-control hidden"
                                               v-model="selectedCost.active_at" v-validate:costActive="{required: true}">
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{'has-error': checkForErrorCost('costAmount')}">
                                        <label for="selectedCost_amount">Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                                            <input type="number" number id="selectedCost_amount" class="form-control"
                                                   v-model="selectedCost.amount" v-validate:costAmount="{required: true, min: 1}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </validator>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='showEditModal = false, selectedCost = null'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='updateCost(selectedCost)'>Update</button>
        </div>

    </modal>

    <modal class="text-center" :show.sync="deleteCostModal" title="Delete Cost" small="true">
        <div slot="modal-body" class="modal-body text-center" v-if="selectedCost">Are you sure you want to delete {{ selectedCost.name }}?</div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-default btn-sm" @click='deleteCostModal = false'>Cancel</button>
            <button type="button" class="btn btn-primary btn-sm" @click='deleteCostModal = false,remove(selectedCost)'>Confirm</button>
        </div>
    </modal>

</template>
<script type="text/javascript">
    import adminTripCostsPayments from './admin-trip-costs-payments.vue'
    export default{
        name: 'admin-trip-costs',
        props: ['id', 'assignment'],
        components: { adminTripCostsPayments },
        data(){
            return {
                costs: [],
                selectedCost: null,
                attemptedAddCost: false,
                costsErrors:[],

                newCost: {
                    cost_assignable_id: this.id,
                    cost_assignable_type: 'trips',
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
                deleteCostModal: false,
                filters: {
                    type: '',
                },
                search: '',
                sort: 'active_at',
                resource: this.$resource('costs{/id}'),
            }
        },
        watch: {
            // watch filters obj
            'filters': {
                handler: function (val) {
                    // console.log(val);
                    this.searchCosts();
                },
                deep: true
            },

            'search': function (val) {
                this.searchCosts();
            },

            'showEditModal': function (val, oldVal) {
                this.$nextTick(function () {
                    if (val !== oldVal && val === false) {
                        this.selectedCost = null;
                    }
                })
            }
        },
        methods: {
            checkForErrorCost(field){
                return this.$TripPricingCost && this.$TripPricingCost[field.toLowerCase()].invalid && this.attemptedAddCost;
            },
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
                            amount += payment.amount_owed;
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
                    cost_assignable_type: 'trips',
                    name: '',
                    description: '',
                    active_at: '',
                    amount: 0,
                    type: '',
                    payments: [],
                    toggleNewPayment: false
                }
            },
            calculateMaxAmount(cost){
                var max = cost.amount;
                if (cost.payments.length) {
                    cost.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if(this.newPayment !== payment) {
                            max -= payment.amount_owed;
                        }
                    }, this);
                }
                return max;
            },
            calculateMaxPercent(cost){
                var max = 100;
                if (cost.payments.length) {
                    cost.payments.forEach(function (payment) {
                        // must ignore current payment in editMode
                        if(this.newPayment !== payment) {
                            max -= payment.percent_owed;
                        }
                    }, this);
                }
                return max;
            },
            editCost(cost){
                this.selectedCost = cost;
                this.selectedCost.active_at = moment(cost.active_at).format('YYYY-MM-DD');
                this.showEditModal = true;
            },
            confirmRemove(cost) {
                this.selectedCost = cost;
                this.deleteCostModal = true;
            },
            remove(cost){
                this.resource.delete({ id: cost.id }).then(function (response) {
                    this.costs.$remove(cost);
                    this.selectedCost = null;
                });
            },
            addCost(){
                this.attemptedAddCost = true;
                if (this.$TripPricingCost.valid) {
                    this.resource.save(this.newCost).then(function (response) {
                        this.costs.push(response.data.data);
                        this.resetCost();
                        this.showAddModal = false;
                        this.attemptedAddCost = false;
                    });
                }
                this.checkCostsErrors();
            },
            updateCost(){
                this.attemptedAddCost = true;
                if (this.$TripPricingCost.valid) {
                    this.resource.update({id: this.selectedCost.id}, this.selectedCost).then(function (response) {
                        $.extend(this.costs, this.selectedCost);
                        this.selectedCost = null;
                        this.attemptedAddCost = false;
                        this.showEditModal = false;
                    })
                }
                this.checkCostsErrors();
            },
            resetFilter(){
                this.search = '';
                this.sort = 'active_at';
                this.filters = {
                    type: ''
                }
            },
            searchCosts(){
                this.$refs.spinner.show();
                this.resource.get({
                    include: 'payments',
                    assignment: this.assignment + '|' + this.id,
                    search: this.search,
                    sort: this.sort,
                    type: this.filters.type
                }).then(function (response) {
                    this.costs = response.data.data;
                    this.$refs.spinner.hide()
                });
            }
        },
        ready(){
            this.searchCosts();
        }
    }
</script>
<template>
    <fetch-json :url="url" 
                :parameters="{ filter: {}, sort: '-created_at' }" 
                ref="list"
                @filter:removed="removeActiveFilter"
                :cache-key="cacheKey+'.fetchJson'"
    >
        <div class="panel panel-default"
             style="border-top: 5px solid #f6323e"
             slot-scope="{ json:transactions, changePage, addFilter, removeFilter, filters, pagination, changePerPage, sortBy, loading }"
        >
            <div class="panel-heading">
                <h5>All Transactions</h5>
            </div>
            <div class="panel-heading">
                <span v-for="(label, index) in activeFilters" 
                    :key="index" 
                    class="label label-filter">
                    <a type="button" @click="openFilterModal(filterConfiguration[label.key])">{{ label.text | capitalize }}: "{{ label.value | capitalize}}"</a> 
                    <a type="button" @click="removeFilter(label.key)"><i class="fa fa-times"></i></a>
                </span>
                <div class="dropdown" style="display: inline-block; margin-left: 1em;">
                    <a role="button" class="dropdown-toggle" data-toggle="dropdown">+ Add a filter</a>
                    <ul class="dropdown-menu">
                        <li class="dropdown-header">Filter By</li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.amount)"
                            >Amount</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.fund_name)"
                            >Fund</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.donor_name)"
                            >Donor Name</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.donor_email)"
                            >Donor Email</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.type)"
                            >Transaction Type</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.payment_method)"
                            >Payment Method</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.card_last_four)"
                            >Card Last Four</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.created_between)"
                            >Date</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.accounting_class)"
                            >Accounting Class</a>
                        </li>
                        <li>
                            <a type="button" 
                                @click="openFilterModal(filterConfiguration.accounting_item)"
                            >Accounting Item</a>
                        </li>
                    </ul>

                    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Filter By {{ filterModal.title }}</h4>
                                </div>
                                <div class="modal-body">
                                <component :is="filterModal.component" 
                                            :callback="addFilter" 
                                            :config="filterModal"
                                            @apply:filter="closeFilterModal"
                                    ></component>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <em class="text-muted small">
                    Showing {{ transactions.length || 0 }} of {{ pagination.total || 0 }} results
                </em>

                <export-list type="transactions" 
                            :filters="filters"
                ></export-list>

            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
                <table class="table table-striped table-condensed" v-if="transactions && transactions.length">
                    <thead>
                        <tr class="active">
                            <th>#</th>
                            <th class="text-right">Amount</th>
                            <th v-if="!filters.filter.type">Type</th>
                            <th>Fund</th>
                            <th @click="sortBy('created_at')" style="cursor: pointer">
                                Date <i class="pull-right fa" 
                                        :class="[{ 
                                            'fa-sort-up': filters.sort === 'created_at', 
                                            'fa-sort-down': filters.sort === '-created_at' 
                                        }, 'fa-sort']"
                                    ></i>
                            </th>
                            <template v-if="filters.filter.type === 'donation' || filters.filter.payment_method || filters.filter.donor_name || filters.filter.donor_email">
                                <th>Payment Method</th>
                                <th>Donor First Name</th>
                                <th>Donor Last Name</th>
                                <th>Donor Email</th>
                                <th>Donor Phone</th>
                            </template>
                            <th>Accounting Class</th>
                            <th>Accounting Item</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(transaction, index) in transactions" :key="transaction.id">
                            <td>{{ index+1 }}</td>
                            <td class="text-right" :class="{ 'text-danger' : transaction.amount < 0, 'text-success' : transaction.amount > 0 }">
                                {{ currency(transaction.amount) }}
                            </td>
                            <td v-if="!filters.filter.type"><em>{{ transaction.type }}</em></td>
                            <td>
                                <strong>
                                    <a :href="`/admin/transactions/${transaction.id}`">
                                        {{ transaction.fund.name }}
                                    </a>
                                </strong>
                            </td>
                            <td>{{ transaction.created_at | moment('lll') }}</td>
                            <template v-if="filters.filter.type === 'donation' || filters.filter.payment_method || filters.filter.donor_name || filters.filter.donor_email">
                                <td>{{ transaction.details.type }}</td>
                                <td>{{ transaction.donor.first_name }}</td>
                                <td>{{ transaction.donor.last_name }}</td>
                                <td>{{ transaction.donor.email }}</td>
                                <td>{{ transaction.donor.phone }}</td>
                            </template>
                            <td>{{ transaction.fund.class.name }}</td>
                            <td>{{ transaction.fund.item.name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-body text-center" v-if="!transactions.length && !loading">
                <span class="lead">No Transactions</span>
                <p>Try adjusting the filters, or create some transactions.</p>
            </div>
            <div class="panel-footer" v-if="pagination.total > pagination.per_page">
                <pager :pagination="pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>
</template>

<script>
import state from '../state.mixin';
import dates from '../dates.mixin';
import activeFilter from '../activeFilter.mixin';
import FilterSearch from './FilterSearch';
import FilterRadio from './FilterRadio';
export default {
    props: {
        url: {
            type: String,
            default: 'transactions?include=fund.accounting-class,fund.accounting-item,donor'
        },
        cacheKey: {
            type: String,
            default: `${window.location.host}${window.location.pathname}.admin.transactionList`
        }
    },

    components: {
        'filter-search': FilterSearch,
        'filter-radio': FilterRadio
    },

    mixins: [state, dates, activeFilter],

    data() {
        return {
            filterModal: {
                component: null,
                title: null
            },
            filterConfiguration: {
                fund_name: {
                    component: 'filter-search',
                    title: 'Fund Name', 
                    field: 'fund_name',
                },
                amount: {
                    component: 'filter-search',
                    title: 'Amount', 
                    field: 'amount',
                },
                donor_name: {
                    component: 'filter-search',
                    title: 'Donor Name', 
                    field: 'donor_name',
                },
                donor_email: {
                    component: 'filter-search',
                    title: 'Donor Email',
                    field: 'donor_email'
                },
                type: {
                    component: 'filter-radio',
                    title: 'Transaction Type', 
                    field: 'type',
                    options: [
                        {value: 'credit', label: 'Credit'},
                        {value: 'donation', label: 'Donation'},
                        {value: 'refund', label: 'Refund'},
                        {value: 'transfer', label: 'Transfer'},
                    ]
                },
                payment_method: {
                    component: 'filter-radio',
                    title: 'Payment Method', 
                    field: 'payment_method',
                    options: [
                        {value: 'card', label: 'Credit Card'},
                        {value: 'check', label: 'Check'},
                        {value: 'cash', label: 'Cash'}
                    ]
                },
                card_last_four: {
                    component: 'filter-search',
                    title: 'Card Last Four',
                    field: 'card_last_four'
                },
                created_between: {
                    component: 'filter-radio',
                    title: 'Date', 
                    field: 'created_between',
                    options: []
                },
                accounting_class: {
                    component: 'filter-search',
                    title: 'Accounting Class',
                    field: 'accounting_class'
                },
                accounting_item: {
                    component: 'filter-search',
                    title: 'Accounting Item',
                    field: 'accounting_item'
                }
            }
        }
    },

    computed: {
        createdBetween() {
            return [
                {value: `${this.startOfToday},${this.endOfToday}`, label: 'Today'},
                {value: `${this.startOfYesterday},${this.endOfYesterday}`, label: 'Yesterday'},
                {value: `${this.startOfWeek},${this.endOfWeek}`, label: 'This Week'},
                {value: `${this.startOfMonth},${this.endOfMonth}`, label: 'This Month'},
                {value: `${this.startOfLastMonth},${this.endOfLastMonth}`, label: 'Last Month'}
            ];
        }
    },

    methods: {
        openFilterModal(filter) {
            this.filterModal = filter;
            $('#filterModal').modal('show');
        },
        closeFilterModal(data) {
            this.addActiveFilter(data);
            this.filterModal = {
                component: null,
                title: null
            }
            $('#filterModal').modal('hide');
        }
    },

    watch: {
        activeFilters() {
            this.saveState(['activeFilters']);
        }
    },

    mounted() {
        let that = this;
        this.$root.$on('refreshTransactions', function () {
            that.$refs.list.fetch();
        });

        this.filterConfiguration.created_between.options = this.createdBetween;

        var previousState = this.restoreState();
        if (previousState) {
            this.activeFilters = previousState.activeFilters;
        }
    }
}
</script>

<style>
    tr.selected, tr:hover {
        background-color: #fcf8e3 !important;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>

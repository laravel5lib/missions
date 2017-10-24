<template>
    <section>
        <div class="col-md-4">
            <fund-editor :id="id"></fund-editor>
        </div>
        <div class="col-md-8" v-if="app.user.can.view_transactions">
            <div class="collapse" id="createTransaction">
                <transaction-form :fund-id="id" @transactionCreated="transactionCreated"></transaction-form>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Transactions</h5>
                </div><!-- end panel-heading -->
                <div class="panel-body">
                    <admin-transactions-list :fund="id"
                                             storage-name="AdminFundTransactionsConfig">
                    </admin-transactions-list>
                </div><!-- end panel-body -->
            </div>
            <template v-if="app.user.can.view_notes">
                <slot></slot>
            </template>
        </div>
    </section>
</template>
<script type="text/javascript">
    import fundEditor from '../funds/fund-editor.vue';
    import transactionForm from '../transactions/transaction-form.vue';
    import adminTransactionsList from '../transactions/admin-transactions-list.vue';
    export default{
        name: 'fund-manager',
        props: {
            id: {
                type: String,
                required: true
            },
            stripeKey: {
                type: String,
                default: null
            },
        },
        data(){
            return{
                app: MissionsMe
            }
        },
        components:{
            fundEditor,
            transactionForm,
            adminTransactionsList
        },
        methods: {
            transactionCreated() {
                this.$root.$emit('reconcileFund');
                this.$root.$emit('refreshTransactions');
            }
        }
    }
</script>
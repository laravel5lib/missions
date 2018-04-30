<template>
    <section>
        <fund-editor :id="id"></fund-editor>

        <div v-if="app.user.can.view_transactions">
            
            <transaction-form :fund-id="id" @transactionCreated="transactionCreated"></transaction-form>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Transactions</h5>
                </div><!-- end panel-heading -->
                <div class="panel-body">
                    <admin-transactions-list :fund="id"
                                             storage-name="AdminFundTransactionsConfig">
                    </admin-transactions-list>
                </div>
            </div>
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
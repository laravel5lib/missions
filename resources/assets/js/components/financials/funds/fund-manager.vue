<template>
    <section>
        <fund-editor :id="id"></fund-editor>

        <div v-if="app.user.can.view_transactions">
            
            <transaction-form :fund-id="id" @transactionCreated="transactionCreated"></transaction-form>

            <admin-transactions-list 
                :url="`transactions?filter[fund_id]=${id}&include=fund.accounting-class,fund.accounting-item,donor`" 
                :cache-key="`admin.fund.${id}.transactionsList`"
            ></admin-transactions-list>
        </div>

    </section>
</template>
<script type="text/javascript">
    import fundEditor from '../funds/fund-editor.vue';
    import transactionForm from '../transactions/transaction-form.vue';
    import adminTransactionsList from '../../AdminTransactionList.vue';
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
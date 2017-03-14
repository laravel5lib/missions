<template>
    <div class="panel panel-default panel-body">
        <spinner v-ref:transactionspinner size="xl" :fixed="false" text="Processing..."></spinner>
        <div class="row">
            <div class="col-xs-12">
                <label>Amount</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-usd"></i></span>
                    <input type="number" class="form-control" v-model="amount" placeholder="0.00">
                </div>
                <span class="help-block">Enter an amount you wish to refund. Full or partial amounts are valid.</span>
                <label>Reason</label>
                <textarea class="form-control"
                          v-model="reason"
                          placeholder="Please enter a reason for the refund.">
                </textarea>
            </div>
        </div>
        <hr class="divider inv">
        <div class="row">
            <div class="col-xs-12 text-right">
                <button class="btn btn-md btn-default" @click="cancel">Cancel</button>
                <button class="btn btn-md btn-primary" @click="refund">Refund</button>
            </div>
        </div>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'refund-form',
        props: {
            "transactionId": {
                type: String,
                required: true
            }
        },
        data(){
            return{
                amount: null,
                reason: null
            }
        },
        methods: {
            cancel() {
                this.amount = null;
                this.reason = null;
                $('#refund').modal('hide');
            },
            refund() {
                this.$refs.transactionspinner.show();
                this.$http.post('transactions', {
                    amount: this.amount,
                    reason: this.reason,
                    transaction_id: this.transactionId,
                    type: 'refund'
                }).then(function (response) {
                    this.$refs.transactionspinner.hide();
                    this.$root.$emit('showSuccess', 'Transaction successfully refunded.');
                    window.location = '/admin/transactions/' + response.body.data.id;
                },function (response) {
                    this.$root.$emit.transactionspinner.hide();
                    this.$root.$emit('showError', 'There are errors on the form.');
                });
            }
        }
    }
</script>
<template>
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete Transaction</h4>
                </div>
                <div class="modal-body">
                    <p>Delete this transaction?</p>
                    <div class="row">
                        <div class="col-xs-6"><a class="btn btn-sm btn-block btn-link" data-dismiss="modal">Keep</a></div>
                        <div class="col-xs-6"><a @click="remove" class="btn btn-sm btn-block btn-primary">Delete</a></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'transaction-delete',
        props: ['transactionId'],
        methods: {
            remove(){
                this.$http.delete('transactions/' + this.transactionId).then((response) => {
                    this.$root.$emit('showSuccess', 'Transaction deleted.');
                    window.location.href = '/admin/transactions';
                }, (error) =>  {
                    console.log(error);
                });
            }
        }
    }
</script>

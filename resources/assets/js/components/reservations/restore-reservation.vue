<template>
    <div class="modal fade" id="restoreConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="restoreConfirmationModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Restore Reservation?</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">Keep</a></div>
                        <div class="col-xs-6"><a @click="restore()" class="btn btn-sm btn-block btn-primary">Restore</a></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'restore-reservation',
        props: {
            'id': {
                type: String,
                required: true,
            },
        },
        data(){
            return{

            }
        },
        methods: {
            restore(){
                this.$http.put('reservations/' + this.id + '/restore').then((response) => {
                    window.location.href = '/admin/reservations/' + this.id;
                }, (error) =>  {
                    this.dispatch('showError', 'Unable to restore');
                })
            }
        }
    }
</script>

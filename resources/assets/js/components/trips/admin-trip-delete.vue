<template>
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Delete Trip</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this Trip?</p>
                    <div class="row">
                        <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
                        <div class="col-xs-6"><a @click="deleteTrip()" class="btn btn-sm btn-block btn-primary">Yes</a></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
    export default{
        name: 'admin-trip-delete',
        props: ['tripId'],
        data(){
            return{

            }
        },
        methods: {
            deleteTrip(){
                this.$http.delete('trips/' + this.tripId).then(function (response) {
                    console.log(response);
                    window.location.href = window.location.href.split('/trips/b41ba11e-71a8-4683-9fdf-a49f5e70a92b')[0];
                }, function (error) {
                    console.log(error);
                })
            }
        }
    }
</script>

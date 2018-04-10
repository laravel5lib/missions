<template>
    <div class="modal fade" id="duplicationModal" tabindex="-1" role="dialog" aria-labelledby="duplicationModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Duplicate Trip</h4>
                    </div>
                    <div class="modal-body">
                        <p>Duplicate this trip?</p>
                        <div class="row">
                            <div class="col-xs-6"><a class="btn btn-sm btn-block btn-default" data-dismiss="modal">No</a></div>
                            <div class="col-xs-6"><a @click="duplicateTrip" class="btn btn-sm btn-block btn-primary">Yes</a></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
</template>

<script type="text/javascript">
    export default{
        name: 'admin-trip-duplicate',
        props: ['tripId'],
        methods:{
            duplicateTrip(){
                this.$http.post('trips/duplicate/' + this.tripId).then((response) => {
                    window.location.href = '/admin' + response.data.data.links[0].uri;
                }, (error) =>  {
                    console.log(error);
                });
            }
        }
    }
</script>

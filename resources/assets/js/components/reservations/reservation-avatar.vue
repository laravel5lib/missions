<template>
    <div class="col-md-12">
        <div class="media col-md-12">
            <div class="media-left">
                <a href="#">
                    <img class="media-object img-rounded" :src="avatar" :alt="name" width="64">
                </a>
            </div>
            <div class="media-body">
                <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button><br>
                <small>Max file size: 2MB</small>
            </div>

            <hr class="divider inv sm"/>
        </div>

        <div class="col-sm-12">
            <div class="collapse" id="avatarCollapse">
                <div class="well">
                    <upload-create-update type="avatar" :name="'reservation-' + id" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['User']"></upload-create-update>
                </div>
            </div>

            <hr class="divider inv sm"/>
        </div>

        <alert :show.sync="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Well Done!</strong>
            <p>Reservation Avatar Updated!</p>
        </alert>

    </div>
</template>
<script>
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    export default{
        name: 'reservation-avatar',
        components: {'upload-create-update': uploadCreateUpdate},
        props: ['id'],
        data(){
            return{
                reservation: null,
                avatar: null,
                avatar_upload_id: null,
                showSuccess: false,
                resource: this.$resource('reservations{/id}')
            }
        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'avatar':
                        this.avatar = data;
                        this.avatar_upload_id = data.id;
                        jQuery('#avatarCollapse').collapse('hide');
                        break;
                }
                this.submit();
            }
        },
        methods: {
            submit(){
                this.reservation.avatar_upload_id = this.avatar_upload_id;
                this.resource.update({id: this.id}, this.reservation).then(function(response) {
                    this.reservation = response.data.data;
                    this.avatar = this.reservation.avatar
                    this.showSuccess = true;
                });
            },
        },
        ready(){
            this.resource.get({id: this.id}).then(function(response) {
                this.reservation = response.data.data;
                this.avatar = this.reservation.avatar
            });
        }
    }
</script>

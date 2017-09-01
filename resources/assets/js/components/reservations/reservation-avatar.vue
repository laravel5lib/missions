<template>
    <div>
        <spinner ref="spinner" size="sm" text="Loading"></spinner>
        <div class="media col-md-12 tour-step-avatar">
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
        <div>
            <div class="collapse" id="avatarCollapse">
                <div class="well">
                    <upload-create-update type="avatar" :name="'reservation-' + id" lock-type ui-locked :ui-selector="2" is-child :tags="['User']"  @uploads-complete="uploadsComplete"></upload-create-update>
                </div>
            </div>
            <hr class="divider inv sm"/>
        </div>
        <alert v-model="showSuccess" placement="top-right" :duration="3000" type="success" width="400px" dismissable>
            <span class="icon-ok-circled alert-icon-float-left"></span>
            <strong>Good job!</strong>
            <p>Reservation Avatar Updated!</p>
        </alert>
    </div>
</template>
<script type="text/javascript">
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
        computed: {
            name() {
                return this.reservation ? this.reservation.given_names + ' ' + this.reservation.surname : '';
            }
        },
        methods: {
            uploadsComplete(data){
                switch(data.type){
                    case 'avatar':
                        this.avatar = data;
                        this.avatar_upload_id = data.id;
                        $('#avatarCollapse').collapse('hide');
                        break;
                }
                this.submit();
            },
            submit(){
                // this.$refs.spinner.show();
                this.reservation.avatar_upload_id = this.avatar_upload_id;

                if(this.reservation.tags)
                    delete this.reservation.tags;

                if(_.isObject(this.reservation.desired_role))
                    this.reservation.desired_role = this.reservation.desired_role.code;

                this.resource.put({id: this.id}, this.reservation).then((response) => {
                    this.reservation = response.data.data;
                    this.avatar = this.reservation.avatar;
                    this.showSuccess = true;
                    // this.$refs.spinner.hide();
                });
            },
        },
        mounted(){
            // this.$refs.spinner.show();
            this.resource.get({id: this.id}).then((response) => {
                this.reservation = response.data.data;
                this.avatar = this.reservation.avatar
                // this.$refs.spinner.hide();
            });
        }
    }
</script>

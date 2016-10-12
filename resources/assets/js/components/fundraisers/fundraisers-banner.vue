<template>
    <template v-if="isUser()">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h5>Share your amazing stories with the world!</h5>
            </div>
            <div class="col-sm-4 text-right">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse">Add Media <i class="fa fa-plus"></i></button>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-12 text-center">
                <h5>Share your amazing stories with the world!</h5>
            </div>
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse">Add Media <i class="fa fa-plus"></i></button>
            </div>
        </div>
        <hr class="divider inv">
        <div class="collapse" id="bannerCollapse">
            <div class="well">
                <upload-create-update type="video" :name="id" :lock-type="true" :ui-locked="true" :ui-selector="2" :is-child="true" :tags="['Fundraiser']"></upload-create-update>
            </div>
        </div>
    </template>
    <img :src="banner" class="img-responsive">
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    import uploadCreateUpdate from '../uploads/admin-upload-create-update.vue';
    export default{
        name: 'fundraisers-banner',
        props: ['id', 'sponsorId', 'authId', 'banner'],
        components: {'upload-create-update': uploadCreateUpdate, 'alert': VueStrap.alert, 'modal': VueStrap.modal},
        data(){
            return{
                fundraiser: null
            }
        },
        events:{
            'uploads-complete'(data){
                switch(data.type){
                    case 'video':
                        debugger;
                        var arr = _.pluck(this.fundraiser.uploads.data, 'id');
                        arr.push(data.id);
                        arr = _.uniq(arr);
                        this.fundraiser.upload_ids = arr;
                        jQuery('#bannerCollapse').collapse('hide');
                        break;
                }
                this.submit();
            }
        },
        methods:{
            isUser(){
                return this.sponsorId === this.authId;
            },
            submit(){
                console.log(this);
                this.$http.put('fundraisers/' + this.id + '?include=uploads', this.fundraiser).then(function (response) {
                    this.fundraiser = response.data.data;
                })
            }
        },
        ready(){
            this.$http.get('fundraisers/' + this.id, { include: 'uploads'}).then(function (response) {
                this.fundraiser = response.data.data;
            });
        }
    }
</script>

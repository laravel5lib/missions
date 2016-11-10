<template>
    <div class="row">
        <div class="col-sm-12" v-if="loaded && !essays.length">
            <div class="alert alert-info" role="alert">No records found</div>
        </div>

        <div class="col-md-4 col-sm-6" v-for="essay in essays">
            <div class="panel panel-default">
                <div class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-file"></i> {{essay.subject}}</h6>
                    <a role="button" :href="'/dashboard/records/essays/' + essay.id">
                        <h4 style="text-transform:capitalize;" class="text-primary">
                            {{essay.author_name}}
                        </h4>
                    </a>
                    <hr class="divider lg">
                    <!--<p class="small">-->
                        <!--<b>ID:</b> {{passport.number}}-->
                        <!--<br>-->
                        <!--<b>BIRTH COUNTRY:</b> {{passport.citizenship_name}}-->
                        <!--<br>-->
                        <!--<b>ISSUED ON:</b> {{passport.issued_at|moment 'll'}}-->
                        <!--<br>-->
                        <!--<b>EXPIRES ON:</b> {{passport.expires_at|moment 'll'}}-->
                    <!--</p>-->
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-info" :href="'/dashboard/records/essays/' + essay.id + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" @click="selectedEssay = essay,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <pagination :pagination.sync="pagination" :callback="searchEssays"></pagination>
        </div>
        <modal :show.sync="deleteModal" title="Remove Essay" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Essay?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeEssay(selectedPassport)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'essays-list',
        props: {
            'userId': {
                type: String,
                required: true
            }
        },
        data(){
            return{
                essays: [],
                //logic vars
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                },
                loaded: false,
                deleteModal: false,
            }
        },
        methods:{
            removeEssay(essay){
                if(essay) {
                    this.$http.delete('essays/' + essay.id).then(function (response) {
                        this.essays = _.reject(this.essays, function (item) {
                            return item.id === essay.id;
                        });
                    });
                }
            },
            searchEssays(){
                this.$http('essays?user=' + this.userId).then(function (response) {
                    this.essays = response.data.data;
                    this.pagination = response.data.meta.pagination;
                    this.loaded = true;
                });
            }
        },
        ready(){
            this.searchEssays();
        }
    }
</script>

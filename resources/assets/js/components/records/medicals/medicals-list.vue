<template>
    <div class="row">
        <div class="col-sm-12" v-if="loaded && !medical_releases.length">
            <div class="alert alert-info" role="alert">No records found</div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="medical-releases/create" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Add Medical Release</a>
                <hr>
            </div>
        </div>


        <div class="col-sm-4" v-for="medical_release in paginatedMedical_releases">
            <div class="panel panel-default">
                <div style="min-height:120px;" class="panel-body">
                    <a role="button" :href="'/dashboard' + medical_release.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{medical_release.name}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>Medication:</b> {{medical_release.medication ? 'Yes' : 'No'}}
                        <br>
                        <b>Diagnosed:</b> {{medical_release.diagnosed ? 'Yes' : 'No'}}
                    </p>
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                            <a class="btn btn-info" :href="'/dashboard' + medical_release.links[0].uri + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" @click="selectedMedicalRelease = medical_release,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 text-center">
            <nav>
                <ul class="pagination pagination-sm">
                    <li :class="{ 'disabled': pagination.current_page == 1 }">
                        <a aria-label="Previous" @click="page=pagination.current_page-1">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li :class="{ 'active': (n+1) == pagination.current_page}" v-for="n in pagination.total_pages"><a @click="page=(n+1)">{{(n+1)}}</a></li>
                    <li :class="{ 'disabled': pagination.current_page == pagination.total_pages }">
                        <a aria-label="Next" @click="page=pagination.current_page+1">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <modal :show.sync="deleteModal" title="Remove Medical Release" small="true">
            <div slot="modal-body" class="modal-body">Are you sure you want to delete this Medical Release?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeMedicalRelease(selectedMedicalRelease)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'medicals-list',
        components: {'modal': VueStrap.modal},
        data(){
            return{
                medical_releases: [],
                paginatedMedical_releases: [],
                selectedMedicalRelease: null,
                //logic vars
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                    total_pages: 0
                },
                loaded: false,
                deleteModal: false,
            }
        },
        watch:{
            'page': function (val, oldVal) {
                this.pagination.current_page = val;
                this.paginate();
            },
            'medical_releases':function (val) {
                if(val.length) {
                    this.paginate();
                }
            }
        },
        methods:{
            // emulate pagination
            paginate(){
                var array = [];
                var start = (this.pagination.current_page - 1) * this.per_page;
                var end   = start + this.per_page;
                var range = _.range(start, end);
                _.each(range, function (index) {
                    if (this.medical_releases[index])
                        array.push(this.medical_releases[index]);
                }, this);
                this.paginatedMedical_releases = array;
            },
            removeMedicalRelease(medical_release){
                if(medical_release) {
                    this.$http.delete('medical/releases/' + medical_release.id).then(function (response) {
                        this.medical_releases = _.reject(this.medical_releases, function (item) {
                            return item.id === medical_release.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.medical_releases.length / this.per_page);
                    });
                }
            }
        },
        ready(){
            this.$http('users/me?include=medical_releases').then(function (response) {
                this.medical_releases = response.data.data.medical_releases.data;
                this.pagination.total_pages = Math.ceil(this.medical_releases.length / this.per_page);
                this.loaded = true;
            });
        }
    }
</script>

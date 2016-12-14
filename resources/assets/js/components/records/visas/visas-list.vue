<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>

        <div class="col-sm-12" v-if="loaded && !visas.length">
            <div role="alert"><p class="text-center text-muted"><em>No records found</em></p></div>
        </div>

        <div class="col-sm-4" v-for="visa in paginatedVisas">
            <div class="panel panel-default">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{visa.country_name}}</h6>
                    <a role="button" :href="'/dashboard' + visa.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{visa.given_names}} {{visa.surname}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>ID:</b> {{visa.number}}
                        <br>
                        <b>ISSUED ON:</b> {{visa.issued_at|moment 'll'}}
                        <br>
                        <b>EXPIRES ON:</b> {{visa.expires_at|moment 'll'}}
                    </p>
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                            <a class="btn btn-info" :href="'/dashboard' + visa.links[0].uri + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" @click="selectedVisa = visa,deleteModal = true"><i class="fa fa-times"></i></a>
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
        <modal :show.sync="deleteModal" title="Remove Visa" small="true">
            <div slot="modal-body" class="modal-body">Are you sure you want to delete this Visa?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removeVisa(selectedVisa)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'visas-list',
        data(){
            return{
                visas: [],
                paginatedVisas: [],
                selectedVisa: null,
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
            'visas':function (val) {
                if(val.length) {
                    this.paginate();
                }
            }
        },
        methods:{
            // emulate pagination
            paginate(){
                let array = [];
                let start = (this.pagination.current_page - 1) * this.per_page;
                let end   = start + this.per_page;
                let range = _.range(start, end);
                _.each(range, function (index) {
                    if (this.visas[index])
                        array.push(this.visas[index]);
                }, this);
                this.paginatedVisas = array;
            },
            removeVisa(visa){
                if(visa) {
                    // this.$refs.spinner.show();
                    this.$http.delete('visas/' + visa.id).then(function (response) {
                        this.visas = _.reject(this.visas, function (item) {
                            return item.id === visa.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.visas.length / this.per_page);
                        // this.$refs.spinner.hide();
                    });
                }
            }
        },
        ready(){
            // this.$refs.spinner.show();
            this.$http('users/me?include=visas').then(function (response) {
                this.visas = response.data.data.visas.data;
                this.pagination.total_pages = Math.ceil(this.visas.length / this.per_page);
                this.loaded = true;
                // this.$refs.spinner.hide();
            });
        }
    }
</script>

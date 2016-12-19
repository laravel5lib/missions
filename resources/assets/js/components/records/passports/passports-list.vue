<template>
    <div class="row">
        <spinner v-ref:spinner size="sm" text="Loading"></spinner>
        <div class="col-sm-12" v-if="loaded && !passports.length">
            <p class="text-center text-muted" role="alert"><em>No records found</em></p>
        </div>

        <div class="col-sm-6 col-md-4" v-for="passport in paginatedPassports">
            <div class="panel panel-default">
                <div class="panel-body">
                    <a role="button" :href="'/dashboard' + passport.links[0].uri">
                        <h5 class="text-primary text-capitalize" style="margin-top:0px;margin-bottom:5px;">
                            {{passport.given_names}} {{passport.surname}}
                        </h5>
                    </a>
                    <div style="position:absolute;right:25px;top:12px;">
                        <a style="margin-right:3px;" :href="'/dashboard' + passport.links[0].uri + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a @click="selectedPassport = passport,deleteModal = true"><i class="fa fa-times"></i></a>
                    </div>
                    <hr class="divider">
                    <label>ID</label>
                    <p class="small">{{passport.number}}</p>
                    <label>BIRTH COUNTRY</label>
                    <p class="small">{{passport.citizenship_name}}</p>
                    <div class="row">
                        <div class="col-sm-6">
                            <label>ISSUED ON</label>
                            <p class="small">{{passport.issued_at|moment 'll'}}</p>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                            <label>EXPIRES ON</label>
                            <p class="small">{{passport.expires_at|moment 'll'}}</p>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end panel-body -->
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
        <modal :show.sync="deleteModal" title="Remove Passport" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Passport?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removePassport(selectedPassport)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'passports-list',
        data(){
            return{
                passports: [],
                paginatedPassports: [],
                selectedPassport: null,
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
            'passports':function (val) {
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
                    if (this.passports[index])
                        array.push(this.passports[index]);
                }, this);
                this.paginatedPassports = array;
            },
            removePassport(passport){
                if(passport) {
                    // this.$refs.spinner.show();
                    this.$http.delete('passports/' + passport.id).then(function (response) {
                        this.passports = _.reject(this.passports, function (item) {
                            return item.id === passport.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.passports.length / this.per_page);
                        // this.$refs.spinner.hide();
                    });
                }
            }
        },
        ready(){
            // this.$refs.spinner.show();
            this.$http('users/me?include=passports').then(function (response) {
                this.passports = response.data.data.passports.data;
                this.pagination.total_pages = Math.ceil(this.passports.length / this.per_page);
                this.loaded = true;
                // this.$refs.spinner.hide();
            });
        }
    }
</script>

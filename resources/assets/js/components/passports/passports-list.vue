<template>
    <div class="row">
        <div class="col-sm-12" v-if="loaded && !passports.length">
            <div class="alert alert-info" role="alert">No records found</div>
        </div>

        <div class="row">
            <div class="col-sm-12 text-right">
                <a href="passports/create" class="btn btn-md btn-primary"><i class="fa fa-plus"></i> Add Passport</a>
                <hr>
            </div>
        </div>


        <div class="col-sm-4" v-for="passport in paginatedPassports">
            <div class="panel panel-default">
                <div style="min-height:220px;" class="panel-body">
                    <h6 class="text-uppercase"><i class="fa fa-map-marker"></i> {{passport.citizenship_name}}</h6>
                    <a role="button" :href="'/dashboard' + passport.links[0].uri">
                        <h5 style="text-transform:capitalize;" class="text-primary">
                            {{passport.given_names}} {{passport.surname}}
                        </h5>
                    </a>
                    <hr class="divider lg">
                    <p class="small">
                        <b>ID:</b> {{passport.number}}
                        <br>
                        <b>BIRTH COUNTRY:</b> {{passport.citizenship_name}}
                        <br>
                        <b>ISSUED ON:</b> {{passport.issued_at|moment 'll'}}
                        <br>
                        <b>EXPIRES ON:</b> {{passport.expires_at|moment 'll'}}
                    </p>
                </div><!-- end panel-body -->
                <div class="panel-footer" style="padding: 0;">
                    <div class="btn-group btn-group-justified btn-group-sm" role="group" aria-label="...">
                        <a class="btn btn-info" :href="'/dashboard' + passport.links[0].uri + '/edit'"><i class="fa fa-pencil"></i></a>
                        <a class="btn btn-danger" @click="selectedPassport = passport,deleteModal = true"><i class="fa fa-times"></i></a>
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
        <modal :show.sync="deleteModal" title="Remove Passport" small="true">
            <div slot="modal-body" class="modal-body text-center">Are you sure you want to delete this Passport?</div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" @click='deleteModal = false'>Exit</button>
                <button type="button" class="btn btn-primary btn-sm" @click='deleteModal = false,removePassport(selectedPassport)'>Confirm</button>
            </div>
        </modal>
    </div>
</template>
<script>
    import VueStrap from 'vue-strap/dist/vue-strap.min';
    export default{
        name: 'passports-list',
        components: {'modal': VueStrap.modal},
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
                var array = [];
                var start = (this.pagination.current_page - 1) * this.per_page;
                var end   = start + this.per_page;
                var range = _.range(start, end);
                _.each(range, function (index) {
                    if (this.passports[index])
                        array.push(this.passports[index]);
                }, this);
                this.paginatedPassports = array;
            },
            removePassport(passport){
                if(passport) {
                    this.$http.delete('passports/' + passport.id).then(function (response) {
                        this.passports = _.reject(this.passports, function (item) {
                            return item.id === passport.id;
                        });
                        this.pagination.total_pages = Math.ceil(this.passports.length / this.per_page);
                    });
                }
            }
        },
        ready(){
            this.$http('users/me?include=passports').then(function (response) {
                this.passports = response.data.data.passports.data;
                this.pagination.total_pages = Math.ceil(this.passports.length / this.per_page);
                this.loaded = true;
            });
        }
    }
</script>

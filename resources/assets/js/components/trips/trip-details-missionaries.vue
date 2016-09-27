<template>
    <div>
        <template v-if="reservations.length">
            <div class="col-xs-6 col-md-4" v-for="reservation in paginatedReservations">
                <div class="panel panel-default">
                    <img class="img-responsive" :src="'/api/' + reservation.avatar.source + '?q=25'" alt="{{ reservation.name }}">
                    <div class="panel-body text-center">
                        <h6>{{ reservation.given_names }} {{ reservation.surname }}</h6>
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
        </template>
        <template v-if="!paginatedReservations.length">
            <hr class="divider inv">
            <p class="text-center text-muted">No missionaries registered yet. Be the first!</p>
            <hr class="divider inv">
        </template>

    </div>
</template>

<script>
    export default{
        name: 'trip-details-missionaries',
        props: {
            reservations: {
                type: Array,
                default(){
                    return [];
                }
            }
        },
        data(){
            return{
                //logic vars
                page: 1,
                per_page: 3,
                pagination: {
                    current_page:1,
                    total_pages: 0
                },
                paginatedReservations: [],
            }
        },
        watch:{
            'page': function (val, oldVal) {
                this.pagination.current_page = val;
                this.paginate();
            },
            'reservations':function (val) {
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
                    if (this.reservations[index])
                        array.push(this.reservations[index]);
                }, this);
                this.paginatedReservations = array;
            },

        },
        ready(){
            console.log(this.reservations);
            this.pagination.total_pages = Math.ceil(this.reservations.length / this.per_page);
            this.paginate();
        }
    }
</script>

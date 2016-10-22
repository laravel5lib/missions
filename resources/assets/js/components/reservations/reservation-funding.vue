<template>
    <div class="panel panel-default panel-primary" v-if="reservation">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>{{ reservation.fund.data.name }}</h5>
                </div>
                <div class="col-xs-4 text-right">
                    <label>Transaction Type</label>
                    <select class="form-control" v-model="type">
                        <option value=''>All</option>
                        <option value='donation'>Donation</option>
                        <option value='fee'>Fee</option>
                        <option value='payment'>Payment</option>
                        <option value='refund'>Refund</option>
                        <option value='transfer'>Transfer</option>
                    </select>
                </div>
            </div>
        </div>
        <ul class="list-group">
            <li class="list-group-item" :class="reservation.fund.data.balance > 0 ? 'list-group-item-success' : 'list-group-item-danger'">
                <h4 class="text-center text-white">{{ reservation.fund.data.balance|currency }} <small class="text-white">Total In Fund</small></h4>
            </li>
            <li class="list-group-item" v-for="transaction in transactions|filterBy type">
                <hr class="divider inv sm">
                <h4 class="list-group-item-heading" :class="transaction.amount > 0 ? 'text-success' : 'text-danger'">{{ transaction.amount|currency }} {{ transaction.description }}</h4>
                <template v-if="transaction.comment">
                <hr class="divider inv sm">
                <div class="well">
                    <!--<div class="list-group-item-text">
                        <p class="small"><b>Description:</b> </p>
                    </div>-->
                        <!--<hr class="divider">-->
                        <div class="list-group-item-text">
                            <p class="small"><b>Comment:</b> {{ transaction.comment }}</p>
                        </div>
                </div>
                </template>
            </li>
        </ul>
    </div>
</template>
<script type="text/javascript">
    export default{
        name: 'reservation-funding',
        props: ['id'],
        data(){
            return{
                reservation:null,
                transactions:null,
                type: ''
            }
        },
        ready(){
            this.$http.get('reservations/' + this.id + '?include=fund.transactions').then(function (response) {
                this.reservation = response.data.data;

                this.$http.get('transactions?fund=' + this.reservation.fund.data.id).then(function (response) {
                    this.transactions = response.data.data;
                })
            });
        }
    }
</script>

<template>
    <table class="table">
        <thead>
        <tr>
            <th>Amount</th>
            <th>Percent</th>
            <th>Due</th>
            <th>Grace</th>
        </tr>
        </thead>
        <tbody>

        <tr v-for="payment in payments|orderBy 'due_at'">
            <td>{{ payment.amount_owed|currency }}</td>
            <td>{{ payment.percent_owed|number }}%</td>
            <td>{{ payment.upfront ? 'Upfront' : payment.due_at|moment 'll' }}</td>
            <td>{{ payment.upfront ? 'N/A' : payment.grace_period }} {{ payment.upfront ? '' : (payment.grace_period > 1 ? 'days' : 'day') }}</td>
            <td>
                <a class="btn btn-default btn-xs" @click="editPayment(payment)"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-danger btn-xs" @click="confirmRemove(payment)"><i class="fa fa-times"></i></a>
            </td>
        </tr>
        </tbody>
    </table>
</template>
<script type="text/javascript">
    export default{
        props: ['id', 'payments'],
    	name: 'admin-trip-costs-payments',
        data(){
            return{
                resource: this.$resource('costs{/id}/payments')
            }
        },
        ready(){
            
        }
    }
</script>
<template>
<div class="panel panel-default" style="border-top: 5px solid #f6323e">
    <div class="panel-heading">
        <ul class="nav nav-pills nav-justified">
            <li :class="{ 'active': flightList === 'flights-booked'}">
                <a role="button" @click="changeView('flights-booked')">
                    Booked <span class="badge badge-default">{{ bookedTotal }}</span>
                </a>
            </li>
            <li :class="{ 'active': flightList === 'flights-not-booked'}">
                <a role="button" @click="changeView('flights-not-booked')">
                    To be Booked <span class="badge badge-default">{{ notBookedTotal }}</span>
                </a>
            </li>
            <li :class="{ 'active': flightList === 'flights-none'}">
                <a href="#">
                    No Flight <span class="badge badge-default">0</span>
                </a>
            </li>
        </ul>
    </div>
    <component :is="flightList" 
               :campaign-id="campaignId" 
               @update:booked="updateBooked" 
               @update:notBooked="updateNotBooked"
    ></component>
</div> 
</template>
<script>
import FlightsBooked from '../components/FlightsBooked.vue';
import FlightsNotBooked from '../components/FlightsNotBooked.vue';
export default {
    props: ['campaignId'],
    components: {
        'flights-booked': FlightsBooked,
        'flights-not-booked': FlightsNotBooked,
    },
    data() {
        return {
            flightList: 'flights-not-booked',
            bookedTotal: 0,
            notBookedTotal: 0
        }
    },
    methods: {
        changeView(component) {
            this.flightList = component;
        },
        updateBooked(total) {
            this.bookedTotal = total;
        },
        updateNotBooked(total) {
            this.notBookedTotal = total;
        }
    }
}
</script>

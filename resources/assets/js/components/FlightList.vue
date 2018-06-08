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
                    No Flight <span class="badge badge-default">{{ noFlightTotal }}</span>
                </a>
            </li>
        </ul>
    </div>
    <keep-alive>
        <component :is="flightList" 
                :campaign-id="campaignId"
                @update:bookedTotal="updateBookedCount"
        ></component>
    </keep-alive>
</div> 
</template>
<script>
import FlightsBooked from '../components/FlightsBooked.vue';
import FlightsNotBooked from '../components/FlightsNotBooked.vue';
export default {
    props: {
        campaignId: String,
        totals: {
            type: Object,
            required: true
        }
    },
    components: {
        'flights-booked': FlightsBooked,
        'flights-not-booked': FlightsNotBooked,
    },
    data() {
        return {
            flightList: 'flights-booked',
            bookedTotal: this.totals.booked,
            notBookedTotal: this.totals.not_booked,
            noFlightTotal: this.totals.no_flight
        }
    },
    methods: {
        changeView(component) {
            this.flightList = component;
        },
        updateBookedCount(total) {
            this.notBookedTotal = this.notBookedTotal - total;
            this.bookedTotal = this.bookedTotal + total;
        }
    }
}
</script>
<style>
    tr.selected, tr:hover {
        background-color: #fcf8e3;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
</style>
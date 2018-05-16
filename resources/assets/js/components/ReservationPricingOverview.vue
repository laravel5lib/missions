<template>
<div class="panel panel-default" style="border-top: 5px solid #f6323e">
    <div class="panel-body" v-if="loading">
        <p>Loading...</p>
    </div>
    <div class="panel-body" v-else>
        <div class="row">
            <div class="col-sm-3">
                <h4 class="text-primary">${{ reservation.total_cost }}</h4>
                <p class="small text-muted">Current Goal</p>
            </div>
            <div class="col-sm-4">
                <h4>{{ reservation.current_rate }}</h4>
                <p class="small text-muted">
                    Current Registration
                    <i class="fa fa-lock" v-if="reservation.rate_locked"></i>
                </p>
            </div>
            <div class="col-sm-5">
                <h4>
                    {{ reservation.upcoming_deadline }}
                    <small>+{{ reservation.grace_days }} days</small>
                </h4>
                <p class="small text-muted">Upcoming Deadline</p>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <span class="help-block" v-if="reservation.rate_locked"><strong><a type="button" @click="unlock"><i class="fa fa-unlock"></i> Unlock this reservation</a></strong> from the current registration. If the user is late on payment, their pricing will change.</span>
        <span class="help-block" v-else><strong><a type="button" @click="lock"><i class="fa fa-lock"></i> Lock this reservation</a></strong> into the current registration. Even if the user is late on payment, their pricing will not change.</span>
    </div>
</div>
</template>
<script>
export default {
    name: 'reservation-pricing-overview',

    props: ['id'],

    data() {
        return {
            loading: true,
            reservation: {}
        }
    },
    
    methods: {
        fetch() {
            this.$http.get(`reservations/${this.id}`).then(response => {
                this.reservation = response.data.data;
                this.loading = false;
            }).catch(error => {
                console.log('Oops! Something went wrong.');
            });
        },
        lock() {
            this.loading = true;
            this.$http.post(`reservations/${this.id}/prices/lock`).then(response => {
                this.fetch();
                swal("Locked!", "The reservation has been locked into the current registration.", "success");
            }).catch(error => {
                this.loading = false;
                swal("Oops!", "Someting went wrong. The reservation could not be locked.", "error");
            });
        },
        unlock() {
            this.loading = true;
            this.$http.delete(`reservations/${this.id}/prices/lock`).then(response => {
                this.fetch();
                swal("Unlocked!", "The reservation has been unlocked from the current registration.", "success");
            }).catch(error => {
                this.loading = false;
                swal("Oops!", "Someting went wrong. The reservation could not be unlocked.", "error");
            });
        }
    },

    mounted() {
        this.fetch();

        this.$root.$on('form:success', () => {
            this.fetch();
        });
    }
}
</script>


<template>
    <fetch-json :url="url" ref="tripList">
        <div class="panel panel-default" 
                style="border-top: 5px solid #f6323e" 
                slot-scope="{ json: reservations, loading, pagination, changePage, filters, addFilter, removeFilter, }"
        >
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-6">
                        <h5>Reservations <span class="badge badge-default">{{ pagination.pagination.total }}</span></h5>
                    </div>
                    <div class="col-xs-6 text-right">
                       <hr class="divider inv sm">
                       <a role="button" class="text-muted" @click="download"><i class="fa fa-file-excel-o"></i> Export</a>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                        <input class="form-control" 
                               placeholder="Search by name..." 
                               v-model="keywords" 
                               @keypress.enter="search"
                        >
                    </div>
                    <span class="help-block" v-if="keywords.length">Press enter to search. <a role="button" @click="reset">Clear search</a></span>
                </div>
            </div>
            <div class="panel-body" v-if="loading">
                <p class="lead text-center text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</p>
            </div>
            <div class="table-responsive" v-if="!loading">
            <table class="table" v-if="reservations && reservations.length">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Funded</th>
                        <th>Requirements</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                        <td>{{ index+1 }}</td>
                        <td class="col-sm-4">
                            <strong><a :href="'/dashboard/reservations/' + reservation.id">{{ reservation.surname | capitalize }}, {{ reservation.given_names | capitalize }}</a></strong>
                        </td>
                        <td>
                            {{ reservation.desired_role.name }}
                        </td>
                        <td class="col-sm-1 text-right">
                            <strong>{{ reservation.percent_raised }}%</strong>
                        </td>
                        <td>
                            <span class="label label-success">{{ complete(reservation) }}</span>
                            <span class="label label-info">{{ attention(reservation) }}</span>
                            <span class="label label-default">{{ reviewing(reservation) }}</span>
                            <span class="label label-danger">{{ getIncomplete(reservation) }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="panel-body text-center" v-else>
                <span class="lead">No reservations yet.</span>
                <p>Make sure your trip's registration form is public and accessible. Be sure to get the word out!</p>
            </div>
            </div>
            <div class="panel-footer" v-if="pagination.pagination.total > pagination.pagination.per_page">
                <pager :pagination="pagination.pagination" :callback="changePage"></pager>
            </div>
        </div>
    </fetch-json>
</template>
<script>
export default {
    props: ['url', 'tripId'],

    data() {
        return {
            keywords: '',
            exportOptions: {
                fields: {
                    managing_user: 'Managing User',
                    user_email: 'User Email',
                    user_primary_phone: 'User Primary Phone',
                    user_secondary_phone: 'User Secondary Phone',
                    group: 'Group',
                    trip_type: 'Trip Type',
                    campaign: 'Campaign',
                    country_located: 'Country Located',
                    start_date: 'Trip Start Date',
                    end_date: 'Trip End Date',
                    given_names: 'Given Names',
                    surname: 'Surname',
                    gender: 'Gender',
                    marital_status: 'Marital Status',
                    shirt_size: 'Shirt Size',
                    age: 'Age',
                    birthday: 'Birthday',
                    email: 'Email',
                    primary_phone: 'Primary Phone',
                    secondary_phone: 'Secondary Phone',
                    street_address: 'Street Address',
                    city: 'City',
                    state_providence: 'State/Providence',
                    zip_postal: 'Zip/Postal Code',
                    country: 'Country',
                    companions: 'Companions',
                    requirements: 'Travel Requirements',
                    percent_raised: 'Percent Raised',
                    amount_raised: 'Amount Raised',
                    outstanding: 'Amount Outstanding',
                    current_registration: 'Current Registration',
					upcoming_payment: 'Upcoming Payment',
					upcoming_deadline: 'Upcoming Deadline',
                    desired_role: 'Role',
                    registered_at: 'Register Date',
                    updated_at: 'Last Updated'
                }
            },
        }
    },

    methods: {
        search() {
            this.$refs.tripList.addFilter('name', this.keywords);
        },
        reset() {
            this.$refs.tripList.removeFilter('name');
            this.keywords = '';
        },
        download() {

        swal({
            title: 'Export List',
            text: 'Do you want export a list of reservations? It will be available from the "my reports" screen.',
            buttons: {
                cancel: true,
                confirm: {
                    text: 'Export',
                    className: 'btn-primary',
                    value: true
                }
            }
        }).then(value => {
            if (value) {
                let fields = this.exportOptions.fields;
                this.$http.post('reservations/export', {'trip': [this.tripId], 'author_id': this.$root.user.id, fields}).then((response) => {
                    this.$root.$emit('showSuccess', response.data.message);
                }, (error) =>  {
                    this.$root.$emit('showError', 'The server is unable to create the export.');
                })
            }
        });
        },
        getIncomplete(reservation) {
            return _.where(reservation.requirements.data, {status: 'incomplete'}).length;
        },
        complete(reservation) {
            return _.where(reservation.requirements.data, {status: 'complete'}).length;
        },
        attention(reservation) {
            return _.where(reservation.requirements.data, {status: 'attention'}).length;
        },
        reviewing(reservation) {
            return _.where(reservation.requirements.data, {status: 'reviewing'}).length;
        }
    }
}
</script>

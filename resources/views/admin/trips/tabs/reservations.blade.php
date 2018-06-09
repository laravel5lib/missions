<fetch-json url="/reservations?filter[trip_id]={{ $trip->id }}" v-cloak>
<div class="panel panel-default" 
     style="border-top: 5px solid #f6323e" 
     slot-scope="{ json: reservations, loading, pagination, changePage }">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h5>Reservations <span class="badge badge-default">@{{ pagination.total }}</span></h5>
            </div>
            <div class="col-xs-6 text-right">
                <h5 v-if="loading" class="text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                <a href="{{ url('/admin/trips/'.$trip->id.'/reservations/create') }}" class="btn btn-sm btn-primary">Add New Reservation</a>
            </div>
        </div>
    </div>
    <table class="table" v-if="reservations && reservations.length">
        <thead>
            <tr class="active">
                <th>#</th>
                <th>Name</th>
                <th>Funds</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(reservation, index) in reservations" :key="reservation.id">
                <td>@{{ index+1 }}</td>
                <td>
                    <strong><a :href="'/admin/reservations/' + reservation.id">@{{ reservation.given_names | capitalize }} @{{ reservation.surname | capitalize }}</a></strong>
                </td>
                <td>
                    <strong>@{{ currency(reservation.total_raised) }}</strong> / @{{ currency(reservation.total_cost) }}
                </td>
            </tr>
        </tbody>
    </table>
    <div class="panel-body text-center" v-else>
        <span class="lead">No Reservations</span>
        <p>Create a reservation to get started.</p>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
        <pager :pagination="pagination" :callback="changePage"></pager>
    </div>
</div>
</fetch-json>

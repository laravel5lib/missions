@component('panel')
    @slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">${{ number_format($trip->starting_cost, 2, '.', ',') }}</h4>
            <p class="small text-muted">Current Starting Cost</p>
        </div>
        <div class="col-sm-4">
            <h4>{{ $trip->getCurrentRate() ? $trip->getCurrentRate()->cost->name : 'N/A' }}</h4>
            <p class="small text-muted">Current Rate</p>
        </div>
        <div class="col-sm-5">
            <h4>{{ $trip->getUpcomingDeadline() ? $trip->getUpcomingDeadline()->format('M j, h:i a') : 'N/A' }}</h4>
            <p class="small text-muted">Upcoming Deadline</p>
        </div>
    </div>
    @endslot
@endcomponent


<fetch-json url="/trips/{{ $trip->id }}/prices">
<div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json: prices, loading, pagination }">
     <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h5>Assigned Pricing</h5>
            </div>
            <div class="col-xs-6 text-right text-muted">
                <h5 v-if="loading"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table" v-if="prices && prices.length > 0">
            <thead>
                <tr class="active">
                    <th>#</th>
                    <th>Cost</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(price, index) in prices">
                <tr :key="price.id">
                    <td>@{{ index+1 }}</td>
                    <td>@{{ price.cost.name | capitalize }}</td>
                    <td class="col-sm-1 text-right">
                        <strong class="text-primary">@{{ currency(price.amount) }}</strong>
                    </td>
                    <td>
                        Effective Date: <em class="text-primary">@{{ price.active_at|mFormat('ll') }}</em>
                    </td>
                </tr>
                <tr :key="price.id" v-if="price.payments.length > 0">
                    <td colspan="4">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th class="col-xs-4">Percent</th>
                                    <th class="col-xs-4">Due</th>
                                    <th class="col-xs-4">Grace</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="payment in price.payments" :key="payment.id">
                                    <td class="col-xs-4">@{{ payment.percentage_due.toFixed(2) }}%</td>
                                    <td class="col-xs-4">@{{ payment.due_at|mFormat('ll') }}</td>
                                    <td class="col-xs-4">@{{ payment.grace_days}} days</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </template>
            </tbody>
        </table>
        <div class="panel-body text-center" v-else>
            <span class="lead">No Pricing Available</span>
            <p>Please contact Missions.Me staff to configure your trip costs.</p>
        </div>
    </div>
    <div class="panel-footer" v-if="pagination.total > pagination.per_page">
        <pager :pagination="pagination"></pager>
    </div>
</div>
</fetch-json>
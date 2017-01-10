<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Registration</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                <label>Spots Available</label>
                <h4>{{ $trip->spots }}</h4>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                <label>Registration Closes</label>
                <h4>{{ date('F d, Y', strtotime($trip->ended_at)) }}</h4>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                <label>Starting Cost</label>
                <h4>${{ number_format($trip->starting_cost, 2, '.', ',') }}</h4>
            </div>
        </div>
    </div>
</div>

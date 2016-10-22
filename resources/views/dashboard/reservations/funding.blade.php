@extends('dashboard.reservations.show')

@section('tab')
    <div class="panel panel-default">
            <div class="panel-heading">
                <h5>Funding</h5>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>
                            Funding Progress
                            {{--<span class="pull-right">${{ number_format($$reservation->getTotalOwed(),2) }}</span>--}}
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="{{ $reservation->getPercentRaised() }}" aria-valuemin="0" aria-valuemax="100" style="min-width: 30%; width: {{ $reservation->getPercentRaised() }}%;">
                                {{ $reservation->getPercentRaised() }}% of ${{ $reservation->getTotalCost() }} Raised
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <h4>Fund</h4>
                        <hr>
                    </div>
                    <div class="col-sm-12">
                        <reservation-funding id="{{ $reservation->id }}"></reservation-funding>
                    </div>
                </div>
            </div><!-- end panel-body -->
        </div><!-- end panel -->
@endsection
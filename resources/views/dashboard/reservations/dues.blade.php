@extends('dashboard.reservations.show')

@section('tab')
    <reservation-costs id="{{ $reservation->id }}"></reservation-costs>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments</h5>
        </div><!-- end panel-heading -->
        <div class="list-group">
            @foreach($reservation->dues as $due)
                <div class="list-group-item">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Balance Due</label>
                            <p>$ {{ $due->outstanding_balance }}</p>
                            <hr class="divider inv hidden-lg">
                        </div>
                        <div class="col-md-6">
                            <label>Cost</label>
                            <p>{{ $due->payment->cost->name }}</p>
                            <hr class="divider inv hidden-lg">
                        </div>
                        <div class="col-md-3">
                            <label>{{ $due->due_at->toFormattedDateString() }}</label>
                            @if ($due->getStatus() == 'late')
                                <p><span class="badge badge-danger">Past Due</span></p>
                            @elseif($due->getStatus() == 'paid')
                                <p><span class="badge badge-success">Paid</span></p>
                            @else
                                <p><span class="badge badge-info">Due in {{ $due->due_at->diffForHumans(null, true) }}</span></p>
                            @endif
                            <hr class="divider inv hidden-lg">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div><!-- end panel -->
@stop
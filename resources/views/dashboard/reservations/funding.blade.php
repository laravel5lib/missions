@extends('dashboard.reservations.show')

@section('tab')

@include('dashboard.reservations._funding_progress')

<div class="panel panel-body">
    <div class="row">
        <div class="col-sm-6">
        @if($reservation->fund->fundraisers->count())
            <a class="btn btn-block btn-default-hollow" href="/dashboard/fundraisers/{{ $reservation->fund->fundraisers->first()->uuid }}/edit">Manage Fundraiser</a>
        @else
            <a class="btn btn-block btn-default-hollow" href="/dashboard/funds/{{ $reservation->fund->id }}/fundraisers/create">Start a Fundraiser</a>
        @endif
        </div>
        <div class="col-sm-6">
            <a href="/donate/{{ $reservation->fund->slug }}" target="_blank" class="btn btn-primary btn-block">
                Make a Donation
            </a>
        </div>
    </div>
</div>

@component('panel')
    @slot('title')
        <h5>Shareable Links</h5>
    @endslot
    @slot('body')
        <div class="alert alert-warning" style="margin-bottom: 0">
            <div class="row">
                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                <div class="col-xs-11"><strong>Get the word out!</strong> Copy and paste these links to share with friends.</div>
            </div>
        </div>
        <hr class="divider inv">
        <label>Fundraising Page (Recommended)</label>
        @if($reservation->fund->fundraisers()->count())
            <pre>{{ url($reservation->fund->fundraisers->first()->url) }}</pre>
        @else
            <pre>No fundraising page. Create one to get a link!</pre>
        @endif
        <label>Direct Donation Link</label>
        <pre>{{ url('/donate/'.$reservation->fund->slug) }}</pre>
    @endslot
@endcomponent

<funding fund-id="{{ $reservation->fund->id }}"></funding>

@endsection
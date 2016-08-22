@extends('site.layouts.default')

@section('content')
    <div>
        @if($fundraiser->banner)
            <img src="{{ image($fundraiser->banner->source) }}" class="img-responsive">
        @endif
    </div>
    <h1>{{ $fundraiser->name }}</h1>
    <div>
        <ul>
            <li>Goal Amount: ${{ $fundraiser->goal_amount }}</li>
            <li>Raised Amount: ${{ $fundraiser->fundable->getTotalRaised() }}</li>
            <li>Percent Raised: {{ $fundraiser->fundable->getPercentRaised() }}%</li>
            <li>Deadline: {{ $fundraiser->expires_at->format('F j, Y h:i a') }}</li>
            <li>Time Left: {{ $fundraiser->expires_at->diffForHumans() }}</li>
        </ul>
    </div>
    <div>
        {% $fundraiser->description %}
    </div>
@stop
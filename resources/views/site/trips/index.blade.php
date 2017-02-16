@extends('site.layouts.default')

@section('content')
    <div class="container">
        <h3>Quick Trips List for details</h3>
        <ul class="list-group">
            @foreach($trips as $trip)
                <a href="/trips/{{ $trip->id }}" class="list-group-item">
                    <h5 class="list-group-item-heading">{{ $trip->campaign->name }}</h5>
                    <p class="list-group-item-text">
                        {{ $trip->type }}
                        <span class="badge primary">{{$trip->difficulty}}</span>
                    </p>
                    
                </a>
            @endforeach
        </ul>
    </div>
@endsection
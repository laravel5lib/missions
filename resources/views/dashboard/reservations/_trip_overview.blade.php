@component('panel')
    <div class="panel-body text-center">
        <img class="img-responsive" src="{{ image($reservation->trip->campaign->getAvatar()->source) }}" />
        <h4>{{ $reservation->trip->campaign->name }} <br />
        <small><i class="fa fa-map-marker"></i> {{ country($reservation->trip->country_code) }}</small>
        </h4>

        <hr class="divider">
            <img src="{{ image($reservation->trip->group->getAvatar()->source)}}" class="img-circle img-xs">
            <h5>{{ $reservation->trip->group->name }}</h5>
        <hr class="divider">
    
        <h4 class="text-primary">{{ $reservation->trip->started_at->format('F j'). ' - ' .$reservation->trip->ended_at->format('F j, Y') }}</h4>

        <hr class="divider">
        
        <span class="label label-filter">{{ ucwords($reservation->trip->type) }}</span>
        <span class="label label-filter">{{ teamRole($reservation->desired_role) }}</span>
        @foreach($reservation->trip->tags as $tag)
            <span class="label label-filter">{{ ucwords($tag->name) }}</span>
        @endforeach
    </div>
@endcomponent
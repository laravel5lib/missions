@component('panel')
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4">
                <img class="img-responsive" src="{{ image($reservation->trip->campaign->getAvatar()->source) }}" />
            </div>
            <div class="col-sm-8">
                <h3>{{ $reservation->trip->campaign->name }} <br />
                <small><i class="fa fa-map-marker"></i> {{ country($reservation->trip->country_code) }}</small>
                </h3>
                
                <h5>{{ $reservation->trip->group->name }}</h5>
                <h4 class="text-primary">{{ $reservation->trip->started_at->format('F j'). ' - ' .$reservation->trip->ended_at->format('F j, Y') }}</h4>
                <span class="label label-primary">{{ ucwords($reservation->trip->type) }}</span>
                <span class="label label-default">{{ teamRole($reservation->desired_role) }}</span>
            </div>
        </div>
    </div>
@endcomponent
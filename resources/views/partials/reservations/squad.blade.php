<div class="panel panel-default">
    <div class="panel-heading">
        <h5>{{ $squad->callsign }}</h5>
    </div>
    <div class="list-group">
        @forelse($squad->members->sortBy('given_names') as $member)
            <div class="list-group-item">
                <div class="media">
                    <div class="media-left">
                        @if($member->public)
                            <a href="{{ url($member->slug->url) }}" target="_blank">
                                <img class="media-object" src="{{ image($member->getAvatar()->source.'?w=50&h=50') }}">
                            </a>
                        @else
                            <img class="media-object" src="{{ image($member->getAvatar()->source.'?w=50&h=50') }}">
                        @endif
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            @if($member->user->public && ($member->user->email == $member->email))
                                <a href="{{ url($member->user->slug->url) }}" target="_blank">
                                    {{ $member->name }}
                                </a>
                            @else
                                {{ $member->name }}
                            @endif
                        </h4>
                        <p>{{ teamRole($member->desired_role) }} &middot;
                            @if($member->trip->group->public)
                                <a href="{{ url($member->trip->group->slug->url) }}" target="_blank">
                                    {{ $member->trip->group->name }}
                                </a>
                            @else
                                {{ $member->trip->group->name }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @empty
            <div class="list-group-item text-muted text-center">
                This group does not have any members.
            </div>
        @endforelse
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="text-capitalize">{{ $trip->campaign->name }}</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-3 text-center">
                        <label>Status</label>
                        <p>{{ ucfirst($trip->status) }}</p>
                    </div>
                    <div class="col-sm-3 text-center">
                        <label>Visibility</label>
                        <p>{{ $trip->public ? 'Public' : 'Private' }}</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Publish Date</label>
                        <p>{{ date('F d, Y', strtotime($trip->updated_at)) }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Group</label>
                        <p><a href="/admin/groups/{{ $trip->group->id }}">{{ $trip->group->name }}</a></p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Country</label>
                        <p>{{ country($trip->country_code) }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Start Date</label>
                        <p>{{ $trip->started_at->format('F d, Y') }}</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>End Date</label>
                        <p>{{ $trip->ended_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Updated</label>
                        <p>{{ $trip->updated_at->format('F d, Y h:i a') }}</p>
                    </div>
                    <div class="col-sm-6 text-center">
                        <label>Created</label>
                        <p>{{ $trip->created_at->format('F d, Y h:i a') }}</p>
                    </div>
                </div>
                <hr class="divider">
                <label>Perfect For</label>
                @if($trip->prospects)
                <ul class="list-unstyled">
                    @foreach($trip->prospects as $prospect)
                        <li class="badge">{{ $prospect }}</li>
                    @endforeach
                </ul>
                @else
                <p>None listed</p>
                @endif
                <label>Team Roles</label>
                 @if($trip->team_roles)
                <ul class="list-unstyled">
                    @foreach($trip->team_roles as $role)
                        <li class="badge">{{ teamRole($role) }}</li>
                    @endforeach
                </ul>
                @else
                <p>None listed</p>
                @endif
            </div>
            <div class="col-sm-12 col-md-4 text-center">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <label>Type</label>
                        <p class="text-capitalize">{{ $trip->type }}</p>
                        <hr class="divider">
                        @if($trip->rep_id)
                        <label>Trip Rep</label>
                        <p><a href="/admin/users/{{ $trip->rep_id }}">{{ $trip->rep->name or ''}}</a></p>
                        <hr class="divider">
                        @endif
                        <label>Tags</label>
                        <ul class="list-unstyled">
                            @forelse($trip->tags as $tag)
                                <li class="badge">{{ $tag }}</li>
                            @empty
                                None
                            @endforelse
                        </ul>
                        <hr class="divider">
                        <label>Difficulty</label>
                        <h4>{{ $trip->difficulty }}</h4>
                        <hr class="divider">
                        <label>Companion Limit</label>
                        <h4>{{ $trip->companion_limit }}</h4>
                    </div>
                </div>
            </div>
        </div>
        </dl>
    </div>
</div>

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
                <h4>{{ $trip->closes_at->format('M j, Y h:i a') }}</h4>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-4 text-center">
                <label>Starting Cost</label>
                <h4>${{ number_format($trip->startingCostInDollars(), 2, '.', ',') }}</h4>
            </div>
        </div>
    </div>
</div>
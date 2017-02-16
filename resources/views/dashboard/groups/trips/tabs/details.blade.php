<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="text-capitalize">{{ $trip->campaign->name }}</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-8">
                <div class="row">
                    <div class="col-sm-6 text-center">
                        <label>Status</label>
                        <p>{{ ucfirst($trip->status) }}</p>
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
                    <div class="col-sm-4 text-center">
                        <label>Start Date</label>
                        <p>{{ $trip->started_at->format('F d, Y') }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>End Date</label>
                        <p>{{ $trip->ended_at->format('F d, Y') }}</p>
                    </div>
                    <div class="col-sm-4 text-center">
                        <label>Updated Date</label>
                        <p>{{ $trip->updated_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <hr class="divider">
                <label>Perfect For</label>
                <ul class="list-unstyled">
                    @foreach($trip->prospects as $prospect)
                        <li class="badge">{{ $prospect }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-12 col-md-4 text-center">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <label>Type</label>
                        <p class="text-capitalize">{{ $trip->type }}</p>
                        <hr class="divider">
                        <label>Trip Rep</label>
                        <p><a href="/admin/users/{{ $trip->rep_id }}">{{ $trip->rep->name or ''}}</a></p>
                        <hr class="divider">
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
    </div>
</div>

<admin-trip-facilitators trip-id="{{ $trip->id }}"></admin-trip-facilitators>
<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Name</label>
                                    <p>{{ $release->name }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Takes Medication</label>
                                    @if($release->takes_medication)
                                        <p class="text-primary"><strong>Yes</strong></p>
                                    @else
                                        <p>No</p>
                                    @endif
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Height</label>
                                    <p>
                                        {{ $release->height_standard }} 
                                        <small class="text-muted">({{ $release->height }} cm)</small>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Weight</label>
                                    <p>
                                        {{ $release->weight_standard }} 
                                        <small class="text-muted">({{ $release->weight }} kg)</small>
                                    </p>
                                </div>
                            </div>
                            <hr class="divider">
                            @if ($release->ins_provider)
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Insurance Provider</label>
                                    <p>{{ $release->ins_provider }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Policy Number</label>
                                    <p>{{ $release->ins_policy_no }}</p>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-sm-12">
                                    <label>No Medical Insurance</label>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default panel-body text-center">
                                <label>Managing User</label>
                                <p>{{ $release->user->name }}</p>
                                <label>Email</label>
                                <p>{{ $release->user->email }}</p>
                                <label>Phone</label>
                                <p>{{ $release->user->phone_one ? $release->user->phone_one : $release->user->phone_two }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Medical Conditions</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($release->conditions as $condition)
                            <div class="col-sm-6">
                                @if($release->pregnant)
                                    <p class="text-primary">Pregnant</p>
                                @endif
                                <p>
                                    {{  $condition->name }}
                                    <small class="text-info">
                                        {{ $condition->diagnosed ? '(Diagnosed)' : '' }}
                                    </small>

                                    <small class="text-danger">
                                        {{ $condition->medication ? '(Medication)' : '' }}
                                    </small>
                                </p>
                            </div>
                        @endforeach
                        @if(! $release->conditions->count())
                            <p class="text-center">No conditions listed.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Medical Allergies</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        @foreach($release->allergies as $allergy)
                            <div class="col-sm-6">
                                <p>
                                    {{  $allergy->name }}
                                    <small class="text-info">
                                        {{ $allergy->diagnosed ? '(Diagnosed)' : '' }}
                                    </small>

                                    <small class="text-danger">
                                        {{ $allergy->medication ? '(Medication)' : '' }}
                                    </small>
                                </p>
                            </div>
                        @endforeach
                        @if(! $release->allergies->count())
                            <p class="text-center">No allergies listed.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Doctor Notes</h5>
                </div>
                <div class="panel-body">
                    @if($release->uploads->count())
                        <ul class="list-group">
                            @foreach($release->uploads as $upload)
                            <li class="list-group-item">
                                <a href="{{ display_file($upload->source) }}" target="_blank">
                                    <i class="fa fa-file-pdf-o"></i> {{ $upload->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center">No notes found</p>
                    @endif
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Emergency Contact</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Name</label>
                            <p>{{ isset($release->emergency_contact['name']) ? $release->emergency_contact['name'] : 'n/a' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>Email</label>
                            <p>{{ isset($release->emergency_contact['email']) ? $release->emergency_contact['email'] : 'n/a' }}</p>
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Phone</label>
                            <p>{{ isset($release->emergency_contact['phone']) ? $release->emergency_contact['phone'] : 'n/a' }}</p>
                        </div>
                        <div class="col-sm-6">
                            <label>Relationship</label>
                            <p>{{ isset($release->emergency_contact['relationship']) ? $release->emergency_contact['relationship'] : 'n/a' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
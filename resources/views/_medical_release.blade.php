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
                                <div class="col-sm-12">
                                    <label>Name</label>
                                    <p>{{ $release->name }}</p>
                                </div>
                            </div>
                            <hr class="divider">
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
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Doctor Notes</h5>
                </div>
                <div class="panel-body">
                    <p>No notes found.</p>
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
                            {{ $release->emergency_contact['name'] }}
                        </div>
                        <div class="col-sm-6">
                            <label>Email</label>
                            {{ $release->emergency_contact['email'] }}
                        </div>
                    </div>
                    <hr class="divider">
                    <div class="row">
                        <div class="col-sm-6">
                            <label>Phone</label>
                            {{ $release->emergency_contact['phone'] }}
                        </div>
                        <div class="col-sm-6">
                            <label>Relationship</label>
                            {{ $release->emergency_contact['relationship'] }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
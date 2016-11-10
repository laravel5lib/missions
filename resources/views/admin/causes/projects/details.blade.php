@extends('admin.causes.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Details</h5>
        </div>
        <div class="panel-body">
            <div class="col-md-7">
                <label>Project ID</label>
                <p>{{ $project->id }}</p>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-12">
                        <label>Project Name</label>
                        <p>{{ $project->name }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Cause</label>
                        <p>{{ $project->type->cause->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Type</label>
                        <p>{{ $project->type->name }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Sponsor Name</label>
                        <p>{{ $project->sponsor->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Sponsor Type</label>
                        <p>{{ $project->sponsor_type == 'users' ? 'Individual' : 'Group' }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-12">
                        <label>Plaque Message</label>
                        <p>{{ $project->plaque_prefix . ' ' . $project->plaque_message }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Launch Date</label>
                        <p>{{ $project->launched_at->format('F j Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Funded At</label>
                        <p>{{ $project->funded_at ? $project->funded_at->format('F j Y') : 'Not fully funded.' }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Started At</label>
                        <p>{{ $project->created_at->toFormattedDateString() }}</p>
                    </div>
                    <div class="col-md-6">
                        <label>Completed At</label>
                        <p>{{ $project->completed_at ? $project->completed->format('F j Y') : 'In progress.' }}</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-md-6">
                        <label>Last Updated</label>
                        <p>{{ $project->updated_at->diffInDays() }} days ago</p>
                    </div>
                </div>
            </div>
            <div class="col-md-5 panel panel-default panel-body text-center">
                <label>Email</label>
                <p>{{ $project->sponsor->email }}</p>
                <label>Home Phone</label>
                <p>{{ $project->sponsor->phone_one }}</p>
                <label>Mobile Phone</label>
                <p>{{ $project->sponsor->phone_two }}</p>
                <label>Address</label>
                <p>{{ $project->sponsor->address }}</p>
                <label>City</label>
                <p>{{ $project->sponsor->city }}</p>
                <label>State/Providence</label>
                <p>{{ $project->sponsor->state }}</p>
                <label>Zip/Postal Code</label>
                <p>{{ $project->sponsor->zip }}</p>
                <label>Country</label>
                <p>{{ country($project->sponsor->country_code) }}</p>
            </div>
        </div>
    </div><!-- end panel -->
@endsection
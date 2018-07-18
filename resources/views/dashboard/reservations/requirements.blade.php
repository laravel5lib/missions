@extends('dashboard.reservations.show')

@section('tab')
    
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="circle-progress" data-percentage="50">
                                <span class="progress-left">
                                    <span class="progress-bar"></span>
                                </span>
                                <span class="progress-right">
                                    <span class="progress-bar"></span>
                                </span>
                                <div class="progress-value">
                                    <div>
                                    1/2
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <h4>Travel Requirements</h4>
                            <p class="text-muted">To make sure you're ready to travel, the following requirements must be completed.</p>
                            <hr class="divider">
                            <p class="small text-muted">
                                <span class="badge badge-error">1</span> Incomplete &nbsp; 
                                <span class="badge badge-muted">0</span> Under Review &nbsp; 
                                <span class="badge badge-success">1</span> Completed &nbsp; 
                                <span class="badge badge-info">0</span> Needs Attention 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12">
            <div class="row" style="display: flex; flex-flow: row wrap">              
                @foreach($reservation->requireables as $requirement)
                <div class="col-lg-6 col-xs-12" style="display: flex;">
                    <div class="panel panel-default" 
                         style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62); width: 100%"
                    >
                        <div class="panel-body">
                            <p>{!! requirementStatusLabel($requirement->pivot->status) !!}</p>
                            <p class="small text-muted"><strong>Due before {{ $requirement->due_at->format('M d, Y') }}</strong></p>
                            <hr class="divider inv">
                            <h5 class="text-uppercase">{{ $requirement->name }}</h5>
                            <p>{{ $requirement->short_desc }}</p>
                            <hr class="divider inv">
                            <p><a href="/dashboard/requirements/{{ $requirement->id }}" class="btn btn-sm btn-primary">
                                {{ $requirement->pivot->status ? 'Make Changes' : 'Get Started' }}
                            </a></p>
                            <hr class="divider inv">
                            <hr class="divider">
                            <p class="small text-muted text-left">Last updated {{ $requirement->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection
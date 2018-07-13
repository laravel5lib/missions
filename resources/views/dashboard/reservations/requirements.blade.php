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
                            <p class="text-muted">To make sure you're ready </p>
                            <hr class="divider">
                            <p class="small text-muted">
                                <span class="badge badge-error">1</span> Incomplete &nbsp; 
                                <span class="badge badge-muted">0</span> Review &nbsp; 
                                <span class="badge badge-success">1</span> Complete &nbsp; 
                                <span class="badge badge-info">0</span> Attention 
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-xs-12">
            <fetch-json url="reservations/{{ $reservation->id }}/requirements" v-cloak>
                <div class="row" slot-scope="{ json:requirements }" style="display: flex; flex-flow: row wrap">              
                    <div class="col-lg-6 col-xs-12" v-for="(requirement, index) in requirements" style="display: flex;">
                        <div class="panel panel-default" 
                             style="border-style: solid; border-width: 4px 0px 0px; border-color: rgb(246, 50, 62); width: 100%"
                        >
                            <div class="panel-body">
                                <p class="badge badge-error">Incomplete</p>
                                <p class="small text-muted"><strong>Due before @{{ requirement.due_at | mFormat('LL') }}</strong></p>
                                <hr class="divider inv">
                                <h5 class="text-uppercase">@{{ requirement.name }}</h5>
                                <p>@{{ requirement.short_desc }}</p>
                                <hr class="divider inv">
                                <p><a :href="`/dashboard/requirements/${requirement.id}`" class="btn btn-sm btn-primary">
                                    Get Started
                                </a></p>
                                <hr class="divider inv">
                                <hr class="divider">
                                <p class="small text-muted text-left">Last updated @{{ requirement.updated_at | moment('', true) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </fetch-json>
        </div>
    </div>

@endsection
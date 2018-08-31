@extends('dashboard.layouts.default')

@section('content')
    @breadcrumbs(['links' => [
        'dashboard' => 'Dashboard',
        'dashboard/records/influencer-applications' => 'Travel Documents',
        'active' => 'Influencer Applications'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Influencer Application</h5>
                    </div>
                    <div class="panel-body">
                        <influencer-questionnaire-create-update 
                            user-id="{{ auth()->user()->id }}" 
                            :is-update="true" 
                            id="{{ $id }}"
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                            ></influencer-questionnaire-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection
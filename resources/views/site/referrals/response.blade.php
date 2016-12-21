@extends('site.layouts.default')

@section('content')
    <hr class="divider inv">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Referral Request from {{ $referral->name }}</h5>
                    </div>
                    <div class="panel-body">
                        <referral-response id="{{ $referral->id }}"></referral-response>
                    </div>
                    <div class="panel-footer">
                        <p class="small text-muted text-center">*This information is kept strictly confidential and will not be shared with the applicant. Your reference is reviewed by Missions.Me staff only.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
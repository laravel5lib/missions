@extends('admin.campaigns.show')

@section('tab')
<div>
    <div class="row">
        <div class="col-sm-8">
            <admin-campaign-details campaign-id="{{ $campaign->id }}"></admin-campaign-details>
        </div>
        <div class="col-sm-4">
            <visibility-controls campaign-id="{{ $campaign->id }}"></visibility-controls>
        </div>
    </div>
</div>
@endsection
@extends('admin.campaigns.show')

@section('tab')
<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Promo Codes and Promotionals</h5>
    </div>
    <div class="panel-body">
        <promotionals type="campaigns" id="{{ $campaign->id}}"></promotionals>
    </div>
</div>
@endsection
@extends('dashboard.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>My Passports</h3>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <passports-list></passports-list>
        </div>
    </div>
</div>
<hr class="divider inv lg">
@endsection
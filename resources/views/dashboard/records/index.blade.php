@extends('dashboard.layouts.default')

@section('content')
<hr class="divider inv lg">

<div class="container">
    <div class="row">
        <div class="col-sm-3">
            @include('dashboard.records.layouts.menu', [
            'links' => config('navigation.dashboard.records')
            ])
        </div>
        <div class="col-sm-9">
            <travel-documents-list 
                url="{{ $tab.'?filter[managed_by]='.auth()->user()->id }}"
                type="{{ $tab }}"
            ></travel-documents-list>
            <hr class="divider inv lg">
        </div>
    </div>
</div>

@endsection
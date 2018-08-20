@extends('layouts.admin')

@section('content')

@breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => ucfirst(request()->route('type')) .' Tags',
    ]])
    @endbreadcrumbs

<hr class="divider inv xlg">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <tag-list tag-type="{{ request()->route('type') }}"></tag-list>
        </div>
    </div>
</div>
<hr class="divider inv xlg">

<alert-success :reload="true" :timer="1000">
    <template slot="title">Nice Work!</template>
    <template slot="message">Tags updated.</template>
</alert-success>

@endsection
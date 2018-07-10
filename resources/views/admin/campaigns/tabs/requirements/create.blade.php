@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/requirements' => $campaign->name,
        'active' => 'New Requirement'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="admin/campaigns/{{ $campaign->id }}/requirements">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new requirement was added.</template>
    <template slot="cancel">Add Another</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">New Travel Requirement</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<ajax-form :horizontal="true" action="/requirements" method="post">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Details</h5>
            <p class="text-muted">Provide a unique name and short description that the end-user will see.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <input-text name="name" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Name</label>
                    </input-text>

                    <input-textarea name="short_desc" :horizontal="true" classes="col-md-8">
                        <label slot="label" class="control-label col-md-4">Short Description</label>
                    </input-textarea>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Rules</h5>
            <p class="text-muted">You can define a set of rules that determine when and why this requirement should be applied to a reservation.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                
                @endslot
            @endcomponent
        </div>
    </div>
</div>

</ajax-form>

@endsection
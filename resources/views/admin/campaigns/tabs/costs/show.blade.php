@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/costs' => 'Costs',
        'active' => $cost->name
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/campaigns/{{ $campaign->id }}/costs">
    <template slot="title">Saved</template>
    <template slot="message">The price was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">{{ $cost->name }} Cost</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Edit Cost</h5>
            <p class="text-muted">USE CAUTION when re-labeling a cost as it could create confusion.</p>
            <p class="text-muted">Because changing a cost type changes it's behavior, this cannot be modified. You'll need to delete the cost and create a new one.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <ajax-form method="put" 
                            action="/campaigns/{{ $campaign->id }}/costs/{{ $cost->id }}" 
                            :initial="{{ $cost }}"
                            :horizontal="true"
                    >
                        <template slot-scope="{ form }">
                            <div class="form-group">
                                <label class="control-label col-sm-3">Type</label>
                                <div class="col-sm-9">
                                    <p class="form-control-static">{{ ucfirst($cost->type) }}</p>
                                </div>
                            </div>

                            <input-text name="name" v-model="form.name" :horizontal="true">
                                <label slot="label" class="control-label col-sm-3">Name</label>
                            </input-text>

                            <input-textarea name="description" v-model="form.description" :horizontal="true">
                                <label slot="label" class="control-label col-sm-3">Description</label>
                            </input-textarea>

                            <div class="form-group text-right">
                                <div class="col-sm-12">
                                    <hr class="divider">
                                    <button type="submit" class="btn btn-md btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </template>
                    </ajax-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>

<hr class="divider inv">

@can('delete', $cost)
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <h5>Delete Cost</h5>
			<p class="text-muted">Delete the cost and remove it from all groups, trips, and reservations where in use.</p>
        </div>
        <div class="col-md-8">
            @component('panel')
                @slot('body')
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                            <div class="col-xs-11">USE CAUTION! Deleting this cost will also remove it from any groups, trips or reservations. This will affect fundraising goals and deadlines.</div>
                        </div>
                    </div>
                    <delete-form url="campaigns/{{ $campaign->id }}/costs/{{ $cost->id }}" 
                                redirect="/admin/campaigns/{{ $campaign->id}}/costs"
                                label="Enter the cost name to delete it"
                                match-value="{{ $cost->name }}">
                    </delete-form>
                @endslot
            @endcomponent
        </div>
    </div>
</div>
@endcan

@endsection
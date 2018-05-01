@extends('admin.layouts.default')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Costs',
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-2">
            @include('admin.partials._toolbar')
        </div>
        <div class="col-xs-12 col-md-7">
            @component('panel')
                @slot('title')
                    <h5>New Cost</h5>
                @endslot
                @slot('body')
                <ajax-form method="post" action="/costs">
                    <template slot-scope="{ form }">
                        <div class="row">
                            <div class="col-md-6">
                                <input-select name="type" v-model="form.type" :options="{ upfront: 'Upfront', incremental: 'Incremental', static: 'Static', optional: 'Optional'}">
                                    <label slot="label">Select a Type</label>
                                </input-select>
                            </div>
                            <div class="col-md-6">
                                <input-text name="name" v-model="form.name">
                                    <label slot="label">Name</label>
                                </input-text>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input-textarea name="description" v-model="form.description">
                                    <label slot="label">Description</label>
                                </input-textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 text-right">
                                <hr class="divider">
                                <button type="submit" class="btn btn-md btn-primary">Create</button>
                            </div>
                        </div>
                    </template>
                </ajax-form>
                @endslot
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>All Costs</h5>
                @endslot
                <fetch-json url="costs">
                    <div class="list-group" slot-scope="{ json: costs, loading, pagination }">
                        <div class="list-group-item" v-if="loading">Loading...</div>
                        <div class="list-group-item" v-for="cost in costs" :key="cost.id" v-else>
                            <h5>@{{ cost.name }} <small>&middot; @{{ cost.type }}</h5>
                            <p class="text-muted">@{{ cost.description }}</p>
                        </div>
                    </div>
                </fetch-json>
            @endcomponent
        </div>
        <div class="col-md-3 small">
        </div>
    </div>
</div>
@endsection
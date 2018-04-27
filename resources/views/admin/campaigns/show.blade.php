@extends('admin.layouts.default')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'active' => $campaign->name.' - '.country($campaign->country_code)
    ]])
    @endbreadcrumbs
@endsection

@section('content')
    <hr class="divider inv lg">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2">
                @sidenav(['links' => $pageLinks])
                @endsidenav
            </div>

            <div class="col-md-7">
                @yield('tab')
            </div>

            <div class="col-md-3 small">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                    </li>
                    <li role="presentation">
                        <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="notes">
                        <notes type="campaigns"
                            id="{{ $campaign->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="campaigns"
                            id="{{ $campaign->id }}"
                            user_id="{{ auth()->user()->id }}">
                        </todos>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
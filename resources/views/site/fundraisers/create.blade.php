@extends('site.layouts.default')

@section('content')
    <hr class="divider inv lg">

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>
    <alert-success :timer="3000" redirect="{{ '/' . request()->segment(1) . '/fundraisers/'}}">
        <template slot="title">Nice Work!</template>
        <template slot="message">Your fundraiser was started.</template>
        <template slot="confirm">Continue</template>
    </alert-success>

    <ajax-form method="post" action="/fundraisers" :hidden-values="{{ $fundraiser }}">

    <template slot-scope="props">

    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Start a Fundraiser</h3>
                    </div>
                    <div class="panel-body">

                        <input-text name="name">
                            <label slot="label">Title</label>
                            <span class="help-block" slot="help-text">Give your fundraiser a name.</span>
                        </input-text>

                        <input-text name="url">
                            <label slot="label">Page Link</label>
                            <span class="input-group-addon" slot="prefix">{{ url('/') }}/</span>
                            <span class="help-block" slot="help-text">Provide a link to share access to your fundraising page.</span>
                        </input-text>

                    </div>
                    <div class="panel-footer text-right">
                        <a href="{{ $cancelUrl }}" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">Start Fundraiser</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </template>
@stop
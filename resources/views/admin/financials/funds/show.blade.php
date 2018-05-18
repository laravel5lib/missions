@extends('layouts.admin')
@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endsection

@section('content')
    @include('admin.partials._nav_donations')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/funds' => 'Funds',
        'active' => $fund->name
    ]])
    @endbreadcrumbs

@if($fund->deleted_at)
<div class="darker-bg-primary">
    <div class="container">
        <div class="col-sm-8 text-center">
            <hr class="divider inv sm">
            <h5>This fund was archived {{ $fund->deleted_at->toDayDateTimeString() }} UTC and is no longer active.</h5>
            <hr class="divider inv sm">
        </div>
        <div class="col-sm-4 text-center">
            @can('update', \App\Models\v1\Fund::class)
                <hr class="divider inv sm hidden-xs">
                <button data-toggle="modal"
                        data-target="#restoreConfirmationModal"
                        class="btn btn-sm btn-white-hollow">
                    <i class="fa fa-undo"></i> Restore
                </button>
                <hr class="divider inv sm">
            @endcan
        </div>
    </div><!-- end container -->
</div><!-- end dark-bg-primary -->
@endif

    <!-- @can('create', \App\Models\v1\Transaction::class)
                            <a type="button"
                               class="btn btn-primary"
                               data-toggle="collapse"
                               data-target="#createTransaction">
                                <i class="fa fa-plus icon-left"></i> Transaction
                           </a>
                        @endcan -->


<hr class="divider inv lg">
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-7 col-md-offset-2">
            <fund-manager id="{{ $fund->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}">
            </fund-manager>
            <restore-fund id="{{ $fund->id }}"></restore-fund>
        </div>
        <div class="col-xs-12 col-md-3">
            <notes type="funds"
                    id="{{ $fund->id }}"
                    user_id="{{ auth()->user()->id }}"
                    :per_page="3">
            </notes>
        </div>
    </div>
</div>
@stop
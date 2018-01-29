@extends('admin.layouts.default')
@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
@endsection
@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>{{ $fund->name }} <small>&middot; Fund</small></h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <div class="btn-group">
                        <a href="{{ url('admin/funds') }}" class="btn btn-primary-darker"><i class="fa fa-chevron-left"></i></a>
                        @can('create', \App\Models\v1\Transaction::class)
                            <a type="button"
                               class="btn btn-primary"
                               data-toggle="collapse"
                               data-target="#createTransaction">
                                <i class="fa fa-plus icon-left"></i> Transaction
                           </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <fund-manager id="{{ $fund->id }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}">
                <notes type="funds"
                       id="{{ $fund->id }}"
                       user_id="{{ auth()->user()->id }}"
                       :per_page="3">
                </notes>
            </fund-manager>
            <restore-fund id="{{ $fund->id }}"></restore-fund>
        </div>
    </div>
@stop
@extends('site.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">Donate</h1>
            </div>
            <div class="col-sm-12">
                <donate></donate>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
@endsection
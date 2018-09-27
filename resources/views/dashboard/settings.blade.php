@extends('dashboard.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active">
                        <a href="{{ url('/dashboard/settings') }}">
                            <i class="fa fa-cog" style="margin-right: 1em"></i> Settings
                        </a>
                    </li>
                    <li>
                        <a href="{{ url($user->slug->url) }}">
                            <i class="fa fa-user-circle" style="margin-right: 1em"></i> View Profile
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-xs-12 col-sm-8 col-md-9">
                
                @component('panel')
                    @slot('body')
                        <user-upload 
                            :user="{{ $user }}" 
                            :avatar="{{ $user->getAvatar() }}" 
                            :banner="{{ $user->getBanner() }}"
                        ></user-upload>
                    @endslot
                @endcomponent

                <user-form :user="{{ $user }}"></user-form>
            </div>
        </div>
    </div>
@endsection
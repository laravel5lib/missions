@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="hidden-xs">Dashboard</h3>
                    <h3 class="visible-xs text-center">Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container" v-tour-guide="">
        <div class="well well-default">
            {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
            <div class="row">
                <div class="col-sm-4">
                    <h6 class="text-uppercase" style="margin-bottom:5px;">Welcome back!</h6>
                    <h3 class="text-primary" style="margin:10px 0;">{{ auth()->user()->name }}</h3>
                    <p class="small">We've made some upgrades! Access the most important information about your account
                        in one place.</p>
                </div><!-- end col -->
                <div class="col-sm-8">
                    <h6 class="text-uppercase">Complete Your Profile</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li style="margin-bottom:5px;">
{{--                                    @if( auth()->user() )--}}
                                    <a href="/dashboard/settings">
                                        @if( empty(auth()->user()->avatar_upload_id) )
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/cam-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Add profile photo
                                    </a>
                                </li>
                                <li style="margin-bottom:5px;">
                                    <a href="/dashboard/settings">
                                        @if( empty(auth()->user()->banner_upload_id) )
                                        <img class="img-xs av-left" style="border:none;" src="../images/onboard/photo-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Add banner photo
                                    </a>
                                </li>
                                <li style="margin-bottom:5px;">
                                    <a href="/dashboard/settings">
                                        @if( strlen(auth()->user()->bio) < 1 )
                                        <img class="img-xs av-left" style="border:none;" src="../images/onboard/bio-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Create a bio
                                    </a>
                                </li>
                            </ul>
                        </div><!-- end col -->
                        <div class="col-sm-6">
                            <ul class="list-unstyled">
                                <li style="margin-bottom:5px;">
                                    <a href="/dashboard/settings">
                                        @if( empty(auth()->user()->links) )
                                        <img class="img-xs av-left" style="border:none;" src="../images/onboard/social-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Add social links
                                    </a>
                                </li>
                                <li style="margin-bottom:5px;">
                                    <a href="{{ url(auth()->user()->slug->url) }}">
                                        @if( empty(auth()->user()->accolades[0]["items"]) )
                                        <img class="img-xs av-left" style="border:none;" src="../images/onboard/globe-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Add countries you've visited
                                    </a>
                                </li>
                                <li style="margin-bottom:5px;">
                                    <a href="{{ url(auth()->user()->slug->url) }}">
                                        @if( empty(auth()->user()->accolades[1]["items"]) )
                                        <img class="img-xs av-left" style="border:none;" src="../images/onboard/trips-icon.png">
                                        @else
                                            <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                                        @endif
                                        Add trips you've been on
                                    </a>
                                </li>
                            </ul>
                        </div><!-- end col -->
                    </div><!-- end row -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div>
        <div class="row">
            <div class="col-sm-6 tour-step-payments">
                @include('dashboard._payments')
            </div>

            <div class="col-sm-6 tour-step-requirements">
                @include('dashboard._requirements')
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6 tour-step-donations">
                @include('dashboard._donations')
            </div>

            <div class="col-sm-6 tour-step-news">
                @include('dashboard._featurednews')
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">

    {{--<tour-guide></tour-guide>--}}
@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'intro',
                title: 'Welcome to the Dashboard',
                text: 'It will only take a few seconds to walk through the features found here. <p>You can stop anytime or take this tour by clicking the <i class="fa fa-question-circle-o fa-2x"></i> icon in the menu.</p> <p><strong>Shall we begin?</strong></p>',
                showCancelLink: false,
                buttons: [
                    {
                        text: 'Not Now',
                        action: 'cancel',
                        classes: 'shepherd-button-secondary'
                    },
                    {
                        text: 'Let\'s Go',
                        action: 'next'
                    }
                ]
            },
            {
                id: 'payments',
                title: 'Upcoming Payments',
                text: 'See which payment deadlines are coming up and their status for reservations you are managing.',
                attachTo: {
                    element: '.tour-step-payments',
                    on: 'top'
                },
            },
            {
                id: 'requirements',
                title: 'Travel Requirements',
                text: 'See which travel requirement deadlines are coming up and their status for reservations you are managing.',
                attachTo: {
                    element: '.tour-step-requirements',
                    on: 'top'
                }
            },
            {
                id: 'donations',
                title: 'Recent Donations',
                text: 'See recent donations recieved. If you are managing a team, their donations will also show here.',
                attachTo: {
                    element: '.tour-step-donations',
                    on: 'top'
                }
            },
            {
                id: 'news',
                title: 'Featured News',
                text: 'Check this section periodically for important announcements from Missions.Me.',
                attachTo: {
                    element: '.tour-step-news',
                    on: 'top'
                }
            }
        ];
    </script>
@endsection
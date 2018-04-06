@extends('site.layouts.default')

@section('styles')
    <script type="text/javascript" src="//platform-api.sharethis.com/js/sharethis.js#property=58ba1e02535b950011d40583&product=custom-share-buttons"></script>
    <style>
        .remind-me-modal .swal-button-container, .remind-me-modal .btn-success{
            width: 100%;
        }
    </style>
@endsection

@section('content')
    
    @unless($fundraiser->isClosed())
    <nav class="navbar navbar-default navbar-fixed-top" id="shareMenu" style="display: none;">
      <div class="container navbar-text text-center">
          <action-trigger type="btn-success" size="mr-2" event="modal-donate:open" text="Donate Now"></action-trigger>
            <ul class="social-network-hollow social-circle-hollow mr-2">
              <li><a data-network="facebook" target="_blank" title="Facebook" class="st-custom-button"><i class="fa fa-facebook"></i></a></li>
              <li><a data-network="twitter" target="_blank" title="Twitter" class="st-custom-button"><i class="fa fa-twitter"></i></a></li>
              <li class="hidden-xs"><a href="mailto:?subject={{ $fundraiser->name }}&body={{ $fundraiser->short_desc }}" target="_blank" title="Email"><i class="fa fa-envelope"></i></a></li>
            </ul>
            {{--  <a onclick="remindMeModal()" class="hidden-xs">
                <i class="fa fa-heart text-primary"></i> Remind Me
            </a>  --}}
      </div>
    </nav>
    @endunless

    @can('update', $fundraiser)
        @component('top-banner', ['class' => 'dark-bg-primary'])
            @slot('message')
                    <h4>
                        @if($fundraiser->public)
                            Need to make changes to this page?
                        @else
                            This page is not visible to the public. Check your settings.
                        @endif
                    </h4>
            @endslot
            @slot('cta')
                @hasanyrole('admin|intern|staff|super_admin')
                    @if($fundraiser->public)
                        <a href="{{ url('/admin/fundraisers/' . $fundraiser->uuid . '/edit') }}" class="btn btn-white-hollow">Edit Page</a>
                    @else
                        <a href="{{ url('/admin/fundraisers/' . $fundraiser->uuid . '/sharing') }}" class="btn btn-white-hollow">Change Settings</a>
                    @endif
                @else
                    @if($fundraiser->public)
                        <a href="{{ url('/dashboard/fundraisers/' . $fundraiser->uuid . '/edit') }}" class="btn btn-white-hollow">Edit Page</a>
                    @else
                        <a href="{{ url('/dashboard/fundraisers/' . $fundraiser->uuid . '/sharing') }}" class="btn btn-white-hollow">Change Settings</a>
                    @endif
                @endrole
            @endslot
        @endcomponent
    @endcan

    <section class="visible-xs">
        <img src="{{ $featured_image }}" class="img-responsive">
    </section>
    <section class="white-bg--bottom-border header-info">
        <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-7 col-md-push-3">
                        <p><i class="fa fa-globe fa-lg"></i> {{ $location }}</p>
                        <h2 style="font-weight:400;">{{ $title }}</h2>
                        <p>{{ $short_description }}</p>
                    </div><!-- end col -->
                    <div class="col-sm-12 col-md-3 col-md-pull-7">
                        <hr class="divider inv visible-sm visible-xs">
                        <div class="col-xs-7 visible-xs">
                            <p style="vertical-align:middle;"><img class="img-circle img-responsive border-red av-left" src="{{ $sponsor_avatar_url }}"> <span>{{ $sponsor_name }}</span></p>
                        </div>
                        <div class="col-md-12 col-xs-7 hidden-xs">
                            <p style="vertical-align:middle;"><img class="img-circle img-responsive border-red" src="{{ $sponsor_avatar_url }}"></p>
                        </div>
                        <div class="col-md-12 col-xs-6 hidden-xs">
                            <span class="text-muted">Organized by</span>
                            <h4 class="pt-0 mt-0">{{ $sponsor_name }}</h4>
                        </div>
                        <div class="col-md-12 col-xs-5">
                            <span class="text-muted hidden-xs">Project Type</span>
                            <p>
                                @if($type === 'Mission Trip')
                                    <i class="fa fa-plane gray-light-bg p-1/2 br-1/2"></i> {{ $type }}
                                @elseif($type === 'Fundraiser')
                                    <i class="fa fa-flag gray-light-bg p-1/2 br-1/2"></i> {{ $type }}
                                @else
                                    <i class="fa fa-tint gray-light-bg p-1/2 br-1/2"></i> {{ $type }}
                                @endif
                            </p>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
        </div><!-- end container -->
    </section>
    <section class="topographic-bg">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default hidden-xs">
                        <img src="{{ $featured_image }}" class="img-responsive img-rounded">
                    </div>
                </div><!-- end col -->
                <div class="col-md-4">
                    <div class="panel panel-default details-panel">
                        <div class="panel-body">
                            <h2 class="text-success p-0 m-0">
                                <listen-text text="{{ $dollars_raised }}" event="Fundraiser::raised"></listen-text>
                            </h2>

                            <p class="text-muted raised">Raised by {{ $donor_count }} people</p>

                            @unless($fundraiser->isClosed())
                                <user-profile-fundraisers-progress :now="{{ $percent_raised }}" goal="{{ $dollars_goal }}"></user-profile-fundraisers-progress>

                                <p style="margin-top:20px;margin-bottom:0px;" class="text-muted ends-in">Fundraiser ends in:</p>
                                <p class="countdown">
                                    <strong>
                                        <fundraiser-countdown date="{{ $deadline }}"></fundraiser-countdown>
                                    </strong>
                                </p>

                                <modal-donate title="{{ $fundraiser->fund->name }}" stripe-key="{{ env('STRIPE_PUBLIC_KEY') }}"
                                              auth="{{ auth()->check() ? 1 : 0 }}" type="{{ $type or '' }}" type-id="{{ $slug or '' }}"
                                              fund-id="{{ $fundraiser->fund->id }}" recipient="{{ $fundraiser->fund->name }}">
                                    {{--<template slot="button" slot-scope="{trigger}">
                                        <button type="button" class="btn btn-success btn-block btn-lg" v-on:click="trigger">Donate Now</button>
                                    </template>--}}
                                </modal-donate>
                            @endunless
                            @unless($fundraiser->isOpen())
                                <p class="text-center small text-muted">
                                    This fundraiser is closed and is no longer accepting donations.
                                </p>
                            @endunless

                            <hr class="divider inv hidden-xs">
                            <div class="row share-row">
                                <div class="col-xs-6 col-sm-6 pad-r-5">
                                    <a style="margin-bottom:5px;" data-network="facebook" class="st-custom-button btn btn-facebook-hollow btn-block"><i class="fa fa-facebook icon-left"></i> Share</a>
                                </div><!-- end col -->
                                <div class="col-xs-6 col-sm-6 pad-l-5">
                                    <a data-network="twitter" class="btn btn-twitter-hollow btn-block st-custom-button"><i class="fa fa-twitter icon-left"></i> Tweet</a>
                                </div><!-- end col -->
                            </div><!-- end row -->
                            {{--  <hr class="divider inv hidden-xs">
                            <p class="remind">
                                
                                @unless($fundraiser->isClosed())
                                    <a onclick="remindMeModal()" class="mr-2">
                                        <i class="fa fa-heart text-primary"></i> Remind Me
                                    </a>
                                    <a href="mailto:?subject={{ $fundraiser->name }}&body={{ $fundraiser->short_desc }}" class="mr-2">
                                        <i class="fa fa-envelope"></i>
                                    </a>
                                @endunless
                            </p>  --}}
                        </div><!-- end panel body -->
                    </div><!-- end panel -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end container -->
    </section>
    <section class="white-bg">
        <div class="container p-2">
            <div class="row">
                <div class="col-sm-8">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#about" data-toggle="tab">About</a></li>
                        <li>
                            <a href="#updates" data-toggle="tab">
                                Updates <sup class="text-primary">{{ $update_count }}<sup>
                            </a>
                        </li>
                        <!-- <li><a href="#faqs" data-toggle="tab">FAQs</a></li> -->
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="about">
                            @if($content)
                                {!! $content !!}
                            @else
                                <div class="text-center text-muted">
                                    <h3>
                                        <i class="fa fa-frown-o fa-5x"></i><br /><br />
                                        No Description<br />
                                        <small>Write a compelling story!</small>
                                    </h3>
                                    <a href="{{ url('/dashboard/fundraisers/' . $fundraiser->uuid . '/edit') }}" class="btn btn-default-hollow">Get Started</a>
                                </div>
                            @endif
                        </div>
                        <div role="tabpanel" class="tab-pane" id="updates">
                            <fundraiser-updates fundraiser-id="{{ $fundraiser->uuid }}"></fundraiser-updates>
                        </div>
                    </div> <!-- end tab-content -->
                </div>
                <div class="col-sm-4">
                @if($fundraiser->show_donors)
                    <ul class="nav nav-tabs">
                        <li>
                            <span style="font-weight: 700; padding:16px 20px; display: inline-block;">Donations</span>
                            <small class="text-muted">{{ $donor_count }} Donors</small>
                        </li>
                    </ul>
                    <user-profile-fundraisers-donors id="{{ $fundraiser->uuid }}"></user-profile-fundraisers-donors>
                    <hr class="divider sm inv">
                @endif
                    <div class="panel panel-default panel-share">
                        <div class="panel-body">
                            <h3 class="text-center">Help spread the word. Sharing is caring!</h3>
                            <div class="row share-row">
                                <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                                    <a style="margin-bottom:5px;" data-network="facebook" class="st-custom-button btn btn-facebook-hollow btn-block"><i class="fa fa-facebook icon-left"></i> Share</a>
                                    <a data-network="twitter" class="btn btn-twitter-hollow btn-block st-custom-button"><i class="fa fa-twitter icon-left"></i> Tweet</a>
                                </div><!-- end col -->
                            </div><!-- end row -->
                        </div><!-- end panel-body -->
                    </div><!-- end panel -->
                </div>
            </div>
        </div>
    </section>
@stop

@section('scripts')
    <script type="text/javascript" src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
    (function($) {
        $(document).ready(function(){
            var target = $(".nav-tabs").offset().top;

            $(window).scroll(function(){
                if ($(this).scrollTop() >= target) {
                    $('#shareMenu').fadeIn(500);
                } else {
                    $('#shareMenu').fadeOut(500);
                }
            });
        });
    })(jQuery);

    // Remind Me Functionality
    function remindMeModal() {
      swal({
        className: 'remind-me-modal',
        title: 'Remind Me',
        text: 'Enter your email address and we\'ll remind you 48 hours before the deadline.',
        content: {
          element: "input",
          attributes: {
            placeholder: "Email Address",
            type: "email",
          },
        },
        buttons: {
          cancel: false,
          confirm: {
            text: 'Remind Me',
            className: 'btn-success'
          },
        },
      }).then(email => {
        if (email) {
          email = email.trim();
          // Email validation
          var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
          if (re.test(email)) {
            console.log(email);
            axios.post('fundraisers/{{$fundraiser->uuid}}/remind', { email: email}).then(response => {
              swal("Reminder Set!", "We'll remind you 48 hours before the deadline.", "success");
            }, error => {
              console.log(error);
            });
          }
        }
      });

    }
    </script>
@append
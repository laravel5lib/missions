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
          <p class="small">We've made some upgrades! Access the most important information about your account in one place.</p>
        </div><!-- end col -->
        <div class="col-sm-8">
          <h6 class="text-uppercase">Complete Your Profile</h6>
          <div class="row">
            <div class="col-sm-6">
              <ul class="list-unstyled">
                <li style="margin-bottom:5px;">
                  <!-- <img class="img-xs av-left" style="border:none;" src="../images/onboard/cam-icon.png"> -->
                  <img class="img-xs av-left" style="border:none;" src="../images/onboard/success-check-icon.png">
                  Add profile photo</li>
                <li style="margin-bottom:5px;"><img class="img-xs av-left" style="border:none;" src="../images/onboard/photo-icon.png">Add banner photo</li>
                <li style="margin-bottom:5px;"><img class="img-xs av-left" style="border:none;" src="../images/onboard/bio-icon.png">Create a bio</li>
              </ul>
            </div><!-- end col -->
            <div class="col-sm-6">
              <ul class="list-unstyled">
                <li style="margin-bottom:5px;"><img class="img-xs av-left" style="border:none;" src="../images/onboard/social-icon.png">Add social links</li>
                <li style="margin-bottom:5px;"><img class="img-xs av-left" style="border:none;" src="../images/onboard/globe-icon.png">Add countries you've visited</li>
                <li style="margin-bottom:5px;"><img class="img-xs av-left" style="border:none;" src="../images/onboard/trips-icon.png">Add trips you've been on</li>
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
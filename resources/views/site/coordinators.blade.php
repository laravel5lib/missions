@extends('site.layouts.default')

@section('title', 'Coordinators - Missions.Me')

@section('scripts')
<script>
$('.launch-modal').on('click', function(e){
    e.preventDefault();
    $( '#' + $(this).data('gform-modal') ).modal();
});
</script>
@endsection

@section('content')

  <div class="content-page-header">
      <img class="img-responsive" src="images/coordinators/coordinators-header.jpg" alt="Coordinators">
      <div class="c-page-header-text">
      	<h1 class="text-uppercase dash-trailing">1N1D 2019 Coordinators</h1>
      </div>
  </div>

  @ContentSection(['class' => 'gray-lighter-bg'])

    @include('partials.coordinators.campaign_materials')

  @endContentSection

  @ContentSection([
    'class' => 'gray-lighter-bg', 
    'style' => "border-top: 1px solid #ddd;background:url('/images/college/map-bg.png') no-repeat bottom fixed;"
  ])
    <div class="row">
      <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 text-center">
        <h6 class="text-uppercase">Welcome to the Team</h6>
        <h2>We're Here To Help You!</h2>
        <hr class="divider red-small xlg">
        <div class="video-outer">
          <div class="video-inner">
            <iframe src="https://player.vimeo.com/video/263068741?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
          </div><!-- end video-inner -->
        </div><!-- end video-outer -->
      </div><!-- end col -->
    </div><!-- end row -->
  @endContentSection
  
  @ContentSection(['class' => 'dark-bg-primary', 'style' => 'padding:30px 0 50px;', 'small' => true])
    <div class="row">
      <div class="col-xs-12 text-center">
        <h5>Complete this Team Launch form</h5>
        <a class="btn btn-white-hollow btn-lg" data-toggle="modal" data-target="#gform-modal">Team Launch Form</a>
      </div>
    </div>
  @endContentSection

  @ContentSection(['class' => 'gray-lighter-bg'])
    @include('partials.coordinators.launch_materials')
  @endContentSection

  @ContentSection(['class' => 'gray-light-bg'])
    @include('partials.coordinators.monthly_calls')
  @endContentSection

  @ContentSection(['class' => 'gray-lighter-bg', 'style' => "background:url('/images/college/map-bg.png') no-repeat bottom fixed;"])
    @include('partials.coordinators.mm_team')
  @endContentSection

  @ContentSection(['class' => 'gray-light-bg'])
    @include('partials.coordinators.contact')
  @endContentSection

@include('partials.coordinators.gform_modal')

@endsection
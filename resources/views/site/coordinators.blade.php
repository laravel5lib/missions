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

  @ContentSection([
    'class' => 'dark-bg-primary', 
    ])
      <div class="row">
        <div class="col-sm-6 col-xs-12 col-xs-offset-0 text-center">
          <hr class="divider inv xlg hidden-xs">
          <hr class="divider inv xlg hidden-xs">
          <h6 class="text-uppercase">Welcome to the Team</h6>
          <h1>1N1D19 Coordinators</h1>
          <h5>We're here to help you!</h5>
          <hr class="divider inv lg">
        </div>
        <div class="col-sm-6 col-xs-12 col-xs-offset-0 text-center">
          <div class="video-outer">
            <div class="video-inner">
              <iframe src="https://player.vimeo.com/video/263068741?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
            </div><!-- end video-inner -->
          </div><!-- end video-outer -->
        </div><!-- end col -->
      </div><!-- end row -->
  @endContentSection
  @ContentSection(['class' => 'darker-bg-primary', 'style' => 'padding:20px 0 30px;', 'small' => true])
    <div class="row">
      <div class="col-xs-12 text-center">
        <h5>Complete this Team Launch form</h5>
        <a class="btn btn-white-hollow btn-md" data-toggle="modal" data-target="#gform-modal">Team Launch Form</a>
      </div>
    </div>
  @endContentSection

  @ContentSection(['class' => 'gray-lighter-bg',
    'style' => "border-bottom: 1px solid #ddd;"])

    @include('partials.coordinators.campaign_materials')

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
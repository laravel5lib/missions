<div class="row">
  <div class="col-sm-10 col-sm-offset-1 col-xs-12 col-xs-offset-0 text-center">
      <h2>Launch Your Team</h2>
      <h5>Everything you need for an incredible team launch!</h5>
      <hr class="divider red-small xlg">
  </div><!-- end col -->
</div><!-- end row -->
<div class="row">
  <div class="col-sm-6 col-sm-offset-3 col-xs-12 col-xs-offset-0">
    <div class="video-outer">
      <div class="video-inner">
        <iframe src="https://player.vimeo.com/video/263405694?title=0&byline=0&portrait=0" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
      </div><!-- end video-inner -->
    </div><!-- end video-outer -->
  </div>
</div>
<hr class="divider inv xlg">
<div class="row">
  <div class="col-sm-12 col-sm-offset-0">

    @php
      $collection = collect(
        json_decode(
          file_get_contents(
            resource_path('assets/js/data/coordinators/downloads.json')
          ), 
          true
        )
      )
    @endphp
    @foreach($collection->chunk(3) as $chunk)
      <div class="row" style="display: flex; flex-wrap: wrap;">
        @foreach($chunk as $download)
          <div class="col-sm-6 col-md-4 col-xs-12" style="display: flex;">
            @ResourceCard
              @slot('icon', $download['icon'])
              @slot('title', $download['title'])
              @slot('description', $download['description'])
              @slot('download', $download['url'])
            @endResourceCard
          </div>
        @endforeach
      </div>
    @endforeach

    </div><!-- end row -->
  </div><!-- end col -->
</div>
<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
    <h2 class="text-center">Campaign Materials</h2>
    <h5 class="text-center">All Creation Waits Vision - September Campaign</h5>
    <hr class="divider red-small xlg">
  </div>
</div>

@php
  $septemberCollection = collect(
    json_decode(
      file_get_contents(
        resource_path('assets/js/data/coordinators/september.json')
      ), 
      true
    )
  )
@endphp

@foreach($septemberCollection->chunk(4) as $chunk)
  <div class="row" style="display: flex; flex-wrap: wrap;">
    @foreach($chunk as $download)
      <div class="col-sm-6 col-md-3 col-xs-12" style="display: flex;">
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

<div class="row">
  <div class="col-xs-12 text-center">
    <hr class="divider inv lg">
    <a href="https://www.dropbox.com/s/t7pavs4fkmhyao9/1N1D19-ACW-Pkg-All.zip?dl=0" 
       class="btn btn-primary" 
       target="_blank"
    >
      Download All Campaign Materials
    </a>
  </div>
</div>
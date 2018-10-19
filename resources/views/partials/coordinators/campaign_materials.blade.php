<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
    <h2 class="text-center">Campaign Materials</h2>
    <hr class="divider red-small xlg">
  </div>
</div>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#october" aria-controls="october" role="tab" data-toggle="tab">October</a></li>
    <li role="presentation"><a href="#september" aria-controls="september" role="tab" data-toggle="tab">September</a></li>
</ul>

<hr class="divider inv">

<div class="tab-content">
  <div role="tabpanel" class="tab-pane active" id="october">
    <h3>Together We Do More</h3>

    @php
      $octoberCollection = collect(
        json_decode(
          file_get_contents(
            resource_path('assets/js/data/coordinators/october.json')
          ), 
          true
        )
      )
    @endphp

    @foreach($octoberCollection->chunk(4) as $chunk)
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
    
  </div>
  <div role="tabpanel" class="tab-pane" id="september">
    <h3>All Creation Waits Vision</h3>

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
        <a href="https://www.dropbox.com/s/t7pavs4fkmhyao9/1N1D19-ACW-Pkg-All.zip?dl=1" 
           class="btn btn-primary" 
           target="_blank"
        >
          Download All Campaign Materials
        </a>
      </div>
    </div>

  </div>
</div>
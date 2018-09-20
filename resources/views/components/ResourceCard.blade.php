<div class="panel panel-default" style="border-style:solid;border-width:4px 0 0;border-color:#f6323e; width: 100%">
  <div class="panel-body text-center">
    <hr class="divider inv">
    
    @isset($icon)
        <img src="{{ $icon }}" width="50px" height="50px">
    @endif
    
    @isset($title)
        <h6 class="text-uppercase">{{ $title }}</h6>
    @endif
    
    @isset($description)
        <p class="small">{{ $description }}</p>
    @endif
    
    @isset($download)
        <a href="{{ starts_with($download, 'http') ? $download : download_file($download) }}" 
           class="btn btn-sm btn-primary-hollow" 
           target="_blank"
        >
            Download
        </a>
    @endif

    <hr class="divider inv">
  </div>
</div>
@inject('markdown', 'League\CommonMark\Converter')

@if( is_array($content) )

    @foreach($content as $element)

        @if($element['type'] == 'header')
            {!! '<h'. $element['level'] .'>'. $element['content'] .'</h'. $element['level'] .'>' !!}
        @endif

        @if($element['type'] == 'text')
            <p>{!! $element['content'] !!}</p>
        @endif

        @if($element['type'] == 'image' && isset($element['content']['source']))
            <figure>
                <img src="{{ $element['content']['source'] }}" class="img-responsive">
                <hr class="sm inv divider">
                <figcaption class="text-muted text-center"><i>{{ $element['caption'] }}</i></figcaption>
            </figure>
            <hr class="divider inv">
        @endif

        @if($element['type'] == 'video' && isset($element['content']['source']))
            <hr class="divider inv">
            <div class="video-outer">
                <div class="video-inner">
                    <video id="{{$element['content']['id']}}" class="video-js vjs-default-skin vjs-big-play-centered" width="460" controls preload="metadata">
                        <p class="vjs-no-js">
                            To view this video please enable JavaScript, and consider upgrading to a web browser that
                            <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                        </p>
                    </video>
                </div><!-- end vid-inner -->
            </div><!-- end vid-outer -->
            <hr class="sm inv divider">
            <p class="text-muted text-center"><i>{{ $element['caption'] }}</i></p>
            <hr class="divider inv">
        @endif

    @endforeach

    @section('scripts')
        <script>
            @foreach($content as $element)
            @if($element['type'] == 'video' && isset($element['content']['source']))
                videojs(document.getElementById("{{$element['content']['id']}}"), { "autoplay": false, "fluid": true, "techOrder": ["{{ $element['content']['meta']['format'] }}"], "sources": [{ "type": "video/{{ $element['content']['meta']['format'] }}", "src": "{{ $element['content']['source'] }}"}]})
            @endif
            @endforeach
          </script>
    @endsection
@else
    {!! $markdown->convertToHtml($content) !!}
@endif
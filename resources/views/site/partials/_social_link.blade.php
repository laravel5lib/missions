@unless(! $link)
<li>
    <a href="{{ $link->url }}">
        @if(in_array($link->name, ['facebook', 'instagram', 'twitter', 'linkedin', 'google', 'vimeo', 'youtube']))
            <i class="fa fa-{{$link->name}} text-muted"></i>
        @elseif($link->name == 'pinterest')
            <i class="fa fa-pinterest-p text-muted"></i>
        @elseif($link->name == 'youtube')
            <i class="fa fa-youtube-play text-muted"></i>
        @elseif($link->name == 'google')
            <i class="fa fa-google-plus text-muted"></i>
        @else
            <i class="fa fa-globe text-muted"></i>
        @endif
    </a>
</li>
@endunless
@unless(! $link)
<li>
    <a href="{{ $link->url }}">
        @if(in_array($link->name, ['facebook', 'instagram', 'twitter', 'linkedin']))
            <i class="fa fa-{{$link->name}} text-muted"></i>
        @elseif($link->name == 'pinterest')
            <i class="fa fa-pinterest-p text-muted"></i>
        @else
            <i class="fa fa-globe text-muted"></i>
        @endif
    </a>
</li>
@endunless
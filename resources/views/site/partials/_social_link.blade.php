@unless(! $link)
<li>
    <a href="{{ $link->url }}">
        @if(in_array($link->name, ['facebook', 'instagram', 'twitter', 'linkedin']))
            <i class="fa fa-{{$link->name}}"></i>
        @elseif($link->name == 'pinterest')
            <i class="fa fa-pinterest-p"></i>
        @else
            <i class="fa fa-link"></i>
        @endif
    </a>
</li>
@endunless
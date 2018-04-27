<ul class="breadcrumb">
@foreach($links as $key => $value)
    @if ($key === 'active')
    <li class="active">{!! $value !!}</li>
    @else
    <li><a href="{{ url($key) }}">{!! $value !!}</a></li>
    @endif
@endforeach
</ul>
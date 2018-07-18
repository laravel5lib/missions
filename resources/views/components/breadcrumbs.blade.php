<ul class="breadcrumb" style="background: #eee; padding: 15px 20px; border-bottom: 2px solid #e6e6e6; border-radius: 0px; position: -webkit-sticky;
    position: sticky; top: 0; z-index: 3;">
@foreach($links as $key => $value)
    @if ($key === 'active')
    <li class="active">{!! $value !!}</li>
    @else
    <li><a href="{{ url($key) }}">{!! $value !!}</a></li>
    @endif
@endforeach
</ul>
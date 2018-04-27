<ul class="nav nav-pills nav-stacked">
    @foreach($links as $url => $label)
    <li class="{{ request()->path() == $url ? 'active' : '' }}">
        <a href="{{ url($url) }}">
            {!! $label !!}
        </a>
    </li>
    @endforeach
</ul>
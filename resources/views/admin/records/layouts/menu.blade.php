<ul class="nav nav-pills nav-stacked">
    @foreach($links as $link)
        @can($link['action'], $link['policy'])
        <li class="{{ $tab == $link['url'] ? 'active' : '' }}">
            <a href="{{ url('admin/records', [$link['url']]) }}">
                {{ $link['label'] }}
            </a>
        </li>
        @endcan
    @endforeach
</ul>
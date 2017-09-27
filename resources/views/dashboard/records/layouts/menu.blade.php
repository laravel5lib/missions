<ul class="nav nav-pills nav-stacked">
    @foreach($links as $link)
        <li class="{{ $tab == $link['url'] ? 'active' : '' }}">
            <a href="{{ url('dashboard/records', [$link['url']]) }}">
                {{ $link['label'] }}
            </a>
        </li>
    @endforeach
</ul>
<hr class="divider lg inv">
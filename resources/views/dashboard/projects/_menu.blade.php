<div class="panel panel-default">
    <ul class="nav nav-pills nav-stacked">
        @foreach($links as $link)
            <li class="{{ $tab == $link['url'] ? 'active' : '' }}">
                <a href="{{ url('dashboard/projects', [$project->id, $link['url']]) }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div><!-- end panel -->
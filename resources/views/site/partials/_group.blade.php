<div class="media">
    <div class="media-left">
        <a href="/groups/{{ $group->url }}">
            <img class="media-object img-rounded img-xs" src="{{ image($group->avatar->source) }}" alt="{{ $group->name }}">
        </a>
    </div>
    <div class="media-body">
        <h5 class="media-heading">{{ $group->name }}</h5>
        @unless(! $group->city and ! $group->state)
            <small>{{ $group->city }} @if($group->city), @endif {{ $group->state }}</small> <br />
        @endunless
        <small>{{ country($group->country_code) }}</small>
    </div><!-- end media-body -->
</div><!-- end media -->
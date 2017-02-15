<div class="media">
    <div class="media-left">
        <a href="/groups/{{ $group->url }}">
            <img class="media-object img-rounded img-xs" src="{{ image($group->getAvatar()->source) }}" alt="{{ $group->name }}">
        </a>
    </div>
    <div class="media-body">
        <h6 class="media-heading text-capitalize" style="margin-bottom:3px;"><a href="{{ $group->slug->url }}">{{ $group->name }}</a></h6>
        @unless(! $group->city and ! $group->state)
            <p class="text-muted" style="line-height:1;font-size:10px;margin-bottom:2px;">{{ $group->city }} @if($group->city), @endif {{ $group->state }}</p>
        @endunless
        <p class="text-muted" style="line-height:1;font-size:10px;margin-bottom:2px;">{{ country($group->country_code) }}</p>
    </div><!-- end media-body -->
</div><!-- end media -->
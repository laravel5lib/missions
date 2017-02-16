<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-{{ $story->id }}">
        <h5>
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse-{{ $story->id }}" aria-expanded="true" aria-controls="collapseOne">
               {{ $story->title }}
            </a>
        </h5>
    </div>
    <div id="collapse-{{ $story->id }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading-{{ $story->id }}">
        <div class="panel-body">
            <h5 class="media-heading"><a href="#">{{ $story->author->name }}</a> <small>published a story {{ $story->updated_at->format('M, d, Y') }}.</small></h5>
            {{ $story->content }}
        </div>
    </div>
</div>
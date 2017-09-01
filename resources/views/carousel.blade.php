<?php $generatedId = str_random() ?>

<div id="{{ $id or $generatedId }}" class="carousel slide {{ $class or '' }}" data-ride="carousel">

    @if(isset($indicators) && $indicators)
        <ol class="carousel-indicators">
            <li data-target="#{{ $id or $generatedId }}" data-slide-to="0" class="active"></li>
            <li data-target="#{{ $id or $generatedId }}"></li>
        </ol>
    @endif

    <div class="carousel-inner" role="listbox">
        {{ $slot }}
    </div>

    @if(isset($controls) && $controls)
        <a class="left carousel-control" href="#{{ $id or $generatedId }}" role="button" data-slide="prev">
            <span class="fa fa-angle-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#{{ $id or $generatedId }}" role="button" data-slide="next">
            <span class="fa fa-angle-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    @endif
</div>
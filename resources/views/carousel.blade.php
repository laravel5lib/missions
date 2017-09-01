<div id="{{ $id or str_random() }}" class="carousel slide {{ $class or '' }}" data-ride="carousel">
    <div class="carousel-inner">
        {{ $slot }}
    </div>
</div>
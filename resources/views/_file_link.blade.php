@inject('upload', 'App\Models\v1\Upload')
@if($upload->find($id))
    @if ($upload->type == 'other')
    <a href="{{ display_file($upload->find($id)->source) }}" target="_blank">
        <i class="fa fa-file-pdf-o"></i> {{ $name }}
    </a>
    @else
    <a href="{{ image($upload->find($id)->source) }}" target="_blank">
        <i class="fa fa-image"></i> {{ $name }}
    </a>
    @endif
@else
    <span class="text-muted"><i class="fa fa-file-o"></i> {{ $name }}</span>
@endif
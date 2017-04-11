@inject('upload', 'App\Models\v1\Upload')
@if($upload->find($id))
    <a href="{{ display_file($upload->find($id)->source) }}" target="_blank">
        <i class="fa fa-file-pdf-o"></i> {{ $name }}
    </a>
@else
    <span class="text-muted"><i class="fa fa-file-pdf-o"></i> {{ $name }}</span>
@endif
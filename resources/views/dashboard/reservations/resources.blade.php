@extends('dashboard.reservations.show')

@section('tab')

<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Links</h5>
    </div>
    <ul class="list-group">
    @forelse($reservation->trip->campaign->links as $link)
        <li class="list-group-item">
            <i class="fa fa-chevron-right text-muted"></i>
            <a href="{{ $link->url }}" target="_blank">{{ $link->name }}</a>
        </li>
    @empty
        <li class="list-group-item">No links available.</li>
    @endforelse
    </ul>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Downloads</h5>
    </div>
        <ul class="list-group">
        @forelse ($reservation->trip->campaign->uploads as $upload)
            <li class="list-group-item">
                @include('_file_link', ['id' => $upload->id, 'name' => $upload->name])
            </li>
        @empty
            <li class="list-group-item">No downloads available.</li>
        @endforelse
        </ul>
</div>

@endsection
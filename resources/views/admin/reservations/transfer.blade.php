@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'active' => 'Transfer'
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<hr class="divider inv lg">

<div class="container">
    <div class="row">
        <div class="col-md-offset-1 col-md-10">
            <transfer-reservation :current="{{ json_encode([
                'id' => $reservation->id,
                'campaign' => $reservation->trip->campaign->name,
                'group' => $reservation->trip->group->name,
                'trip' => $reservation->trip->type,
                'role' => teamRole($reservation->desired_role),
                'room' => $reservation->requested_room,
                'name' => $reservation->name
            ]) }}"></transfer-reservation>
        </div>
    </div>
</div>

@endsection
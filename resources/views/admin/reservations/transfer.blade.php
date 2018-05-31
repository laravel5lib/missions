@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'active' => 'Transfer'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/reservations/{{ $reservation->id }}">
    <template slot="title">Nice Work!</template>
    <template slot="message">The reservation has been successfully transferred.</template>
    <template slot="confirm">Ok</template>
</alert-success>

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
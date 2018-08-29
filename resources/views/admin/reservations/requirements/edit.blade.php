@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'admin/reservations/'.$reservation->id.'/requirements' => 'Requirements',
        'admin/reservations/'.$reservation->id.'/requirements/'.$requirement->id => $requirement->name,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/reservations/{{ $reservation->id }}/requirements/{{ $requirement->id }}">
    <template slot="title">Changes Saved!</template>
    <template slot="message">The requirement was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h3 class="text-primary">Edit Travel Requirement</h3>
        </div>
    </div>
</div>

<hr class="divider inv">

<travel-requirement-form 
    requester-id="{{ $reservation->id }}" 
    requester-type="reservations" 
    :requirement="{{ $requirement }}"
></travel-requirement-form>

@endsection
<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Reservations</h5>
    </div>
    <div class="panel-body">
        <reservations-list trip-id="{{ $trip->id }}" group-id="{{ $groupId }}" :group-only="true" user-id="{{ Auth::user()->id }}" type="active"></reservations-list>
    </div>
</div>

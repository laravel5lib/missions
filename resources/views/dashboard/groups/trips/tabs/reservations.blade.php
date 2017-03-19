<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Reservations</h5>
    </div>
    <div class="panel-body">
        <reservations-list trip-id="{{ $trip->id }}" group-id="{{ $groupId }}" :groupOnly="true"></reservations-list>
    </div>
</div>

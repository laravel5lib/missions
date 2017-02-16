<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h5>Description</h5>
            </div>
            <div class="col-xs-6 text-right">
                <a class="btn btn-sm btn-default" href="{{ url('trips/' . $trip->id) }}" target="_blank">See Public Page</a>
                <action-trigger text="Edit / Preview" event="toggleMode" size="btn-sm"></action-trigger>
            </div>
        </div>
    </div>
    <admin-trip-description id="{{ $trip->id }}"></admin-trip-description>
</div>
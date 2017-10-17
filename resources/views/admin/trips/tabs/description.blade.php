<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-6">
                <h3>Description</h3>
            </div>
            <div class="col-xs-6 text-right">
                <hr class="divider inv sm">
                <a class="btn btn-sm btn-default" href="{{ url('trips/' . $trip->id) }}" target="_blank">See Public Page</a>
                @can('update', $trip)
                    <action-trigger text="Edit / Preview" event="toggleMode" size="btn-sm"></action-trigger>
                @endcan
            </div>
        </div>
    </div>
    <admin-trip-description id="{{ $trip->id }}"></admin-trip-description>
    <markdown-example-modal></markdown-example-modal>
</div>
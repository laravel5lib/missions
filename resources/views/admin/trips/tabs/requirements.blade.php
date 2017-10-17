<div class="alert alert-warning">
    <strong>Important:</strong> Making changes to requirements will affect existing reservation requirements.
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8">
                <h3>Requirements</h3>
            </div>
            <div class="col-xs-4 text-right">
                <hr class="divider inv sm">
                <action-trigger text="Add New Requirement" icon="fa fa-plus" event="NewRequirement" size="btn-sm"></action-trigger>
            </div>
        </div>

    </div>
    <admin-trip-requirements id="{{ $trip->id }}" requester="trips"></admin-trip-requirements>
</div>
<div class="alert alert-warning">
    <strong>Important:</strong> Adding or removing tasks will overwrite <em>incomplete</em> tasks on reservations.
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8">
                <h5>Todos</h5>
            </div>
            <div class="col-xs-4 text-right">
                <action-trigger text="New Todo" icon="fa fa-plus" event="NewTodo" size="btn-sm"></action-trigger>
            </div>
        </div>

    </div>
    <admin-trip-todos id="{{ $trip->id }}"></admin-trip-todos>
</div>
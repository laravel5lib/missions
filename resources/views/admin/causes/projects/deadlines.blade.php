<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row">
            <div class="col-xs-8">
                <h5>Important Dates</h5>
            </div>
            <div class="col-xs-4 text-right">
                <action-trigger text="New Date" icon="fa fa-plus" event="NewDeadline" size="btn-sm"></action-trigger>
            </div>
        </div>
    </div>
    <deadlines-manager id="{{ $project->id }}" assignment="projects"></deadlines-manager>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h5 class="panel-header">Outstanding Requirements</h5>
    </div>
    <table class="table table-hover" style="min-height:220px;">
        <tbody>
        @foreach(auth()->user()->outstandingRequirements() as $requirement)
            <tr>
                <td>{{ $requirement->name }}</td>
                <td class="text-right text-muted">{{ $requirement->due_at->format('M j') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="panel-footer text-center">
        <a class="btn btn-default-hollow btn-xs">See All</a>
    </div>
</div>
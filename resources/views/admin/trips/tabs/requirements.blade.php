<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Requirements</h5>
    </div>
    <div class="panel-body">
        @foreach($trip->requirements as $requirement)
            <div class="row">
                <div class="col-xs-8">
                    <h5><a href="#">{{ $requirement->name }} {{ $requirement->document_type }}</a></h5>
                    <h6><small>Enforced: {{ $requirement->enforced ? 'Yes' : 'No' }}</small></h6>
                </div>
                <div class="col-xs-4 text-right">
                    <h5><i class="fa fa-calendar"></i> {{ date('F d, Y', strtotime($requirement->due_at)) }}</h5>
                    <h6><small>Grace Period: {{ $requirement->grace_period }} {{ $requirement->grace_period > 1 ? 'days' : 'day' }}</small></h6>
                </div>
            </div><!-- end row -->
            <hr class="divider">
        @endforeach
    </div>
</div>
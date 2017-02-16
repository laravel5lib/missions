<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Other Deadlines</h5>
    </div>
    <div class="panel-body">
        @foreach($trip->deadlines as $deadline)
            <div class="row">
                <div class="col-xs-8">
                    <h5><a href="#">{{ $deadline->name }}</a></h5>
                    <h6><small>Enforced: {{ $deadline->enforced ? 'Yes' : 'No' }}</small></h6>
                </div>
                <div class="col-xs-4 text-right">
                    <h5><i class="fa fa-calendar"></i> {{ $deadline->grace_period }} {{ $deadline->grace_period > 1 ? 'days' : 'day' }}</h5>
                    <h6><small>Grace Period: {{ $deadline->grace_period }} {{ $deadline->grace_period > 1 ? 'days' : 'day' }}</small></h6>
                </div>
            </div><!-- end row -->
            <hr class="divider">
        @endforeach
    </div>
</div>
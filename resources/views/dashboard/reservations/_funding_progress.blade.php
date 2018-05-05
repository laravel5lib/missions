<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="circle-progress" data-percentage="50">
                    <span class="progress-left">
                        <span class="progress-bar"></span>
                    </span>
                    <span class="progress-right">
                        <span class="progress-bar"></span>
                    </span>
                    <div class="progress-value">
                        <div>
                        ${{ $reservation->totalRaisedInDollars() }}<br>
                        <span>Raised</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-4">
                        <h4 class="text-primary">${{ $reservation->totalCostInDollars() }}</h4>
                        <p class="small text-muted">Fundraising Goal</p>
                    </div>
                    <div class="col-sm-8">
                        <h4>Super Early Registration</h4>
                        <p class="small text-muted">Current Rate</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <h4>50%</h4>
                        <p class="small text-muted">Amount Due</p>
                    </div>
                    <div class="col-sm-8">
                        <h4>Jul 1, 11:59 pm</h4>
                        <p class="small text-muted">Due Date</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
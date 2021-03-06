<div class="panel panel-default">
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="circle-progress" data-percentage="{{ roundUpToAny($reservation->getPercentRaised()) }}">
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
                        <h4>{{ $reservation->getCurrentRate() ? $reservation->getCurrentRate()->cost->name : 'N/A' }}</h4>
                        <p class="small text-muted">Current Registration</p>
                    </div>
                </div>
                <hr class="divider">
                <div class="row">
                    <div class="col-sm-4">
                        <h4>{{ $reservation->getUpcomingPayment() ? $reservation->getUpcomingPayment()->percentage_due.'%' : 'N/A' }}</h4>
                        <p class="small text-muted">Amount Due</p>
                    </div>
                    <div class="col-sm-8">
                        <h4>{{ $reservation->getUpcomingDeadline() ? $reservation->getUpcomingDeadline()->format('M j, h:i a') : 'N/A' }}</h4>
                        <p class="small text-muted">Due Date</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
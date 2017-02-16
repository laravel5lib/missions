<div class="container">
    <div class="row">
        <div class="col-md-offset-2 col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h5>Details</h5>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Type</label>
                                    <p>{{ ucwords($referral->type) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Applicant Name</label>
                                    <p>{{ $referral->applicant_name }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Attention to</label>
                                    <p>{{ $referral->attention_to }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label>Recipient Email</label>
                                    <p>{{ $referral->recipient_email }}</p>
                                </div>
                            </div>
                            <hr class="divider">
                            <div class="row">
                                @foreach($referral->referrer as $key => $value)
                                <div class="col-sm-6">
                                    <label>{{ ucwords($key) }}</label>
                                    <p>{{ $value }}</p>
                                </div>
                                @endforeach
                            </div>
                            <hr class="divider">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Status</label>
                                    <p>{{ ucfirst($referral->status) }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <label>Sent At</label>
                                    <p>{{ $referral->sent_at ? $referral->sent_at->toDateTimeString() : 'n/a' }}</p>
                                </div>
                                <div class="col-sm-4">
                                    <label>Received At</label>
                                    <p>{{ $referral->responded_at ? $referral->responded_at->toDateTimeString() : 'n/a' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="panel panel-default panel-body text-center">
                                <label>Managing User</label>
                                <p>{{ $referral->user->name }}</p>
                                <label>Email</label>
                                <p>{{ $referral->user->email }}</p>
                                <label>Phone</label>
                                <p>{{ $referral->user->phone_one ? $referral->user->phone_one : $referral->user->phone_two }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
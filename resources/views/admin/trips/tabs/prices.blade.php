<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new cost was added.</template>
</alert-success>

@component('panel')
    @slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">${{ number_format($trip->starting_cost, 2, '.', ',') }}</h4>
            <p class="small text-muted">Current Starting Cost</p>
        </div>
        <div class="col-sm-4">
            <h4>N/A</h4>
            <p class="small text-muted">Current Rate</p>
        </div>
        <div class="col-sm-5">
            <h4>N/A</h4>
            <p class="small text-muted">Upcoming Deadline</p>
        </div>
    </div>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Add New Cost</h5>
    @endslot
    @slot('body')
    
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#campaign" aria-controls="home" role="tab" data-toggle="tab">Campaign Pricing</a></li>
            <li role="presentation"><a href="#custom" aria-controls="profile" role="tab" data-toggle="tab">Custom Pricing</a></li>
        </ul>

        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-warning"><i class="fa fa-info-circle"></i> Trip prices will be applied to it's reservations automatically at registration.</div>
            </div>
        </div>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="campaign">

                <ajax-form method="post" action="trips/{{ $trip->id }}/prices">
                    <template slot-scope="{ form }">
                        <div class="col-md-6">
                            <select-price name="price_id" url="campaigns/{{ $trip->campaign_id }}/prices" v-model="form.price_id">
                                <label slot="label">Select a Cost</label>
                            </select-price>
                        </div>
                        <div class="col-md-6">
                            <hr class="divider inv sm">
                            <hr class="divider inv">
                            <button type="submit" class="btn btn-md btn-primary">Add</button>
                        </div>
                    </template>
                </ajax-form>
            </div>
            
            <div role="tabpanel" class="tab-pane" id="custom">
                <price-add-new priceable-type="trips" priceable-id="{{ $trip->id }}"></price-add-new>
            </div>
        </div>
    @endslot
@endcomponent

<price-list priceable-type="trips" priceable-id="{{ $trip->id }}"></price-list>
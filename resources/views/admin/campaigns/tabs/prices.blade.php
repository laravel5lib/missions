@extends('admin.campaigns.show')

@section('tab')

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new cost was added.</template>
</alert-success>

@component('panel')
    @slot('title')
        <h5>Campaign Pricing</h5>
        <p class="text-small text-muted"><i class="fa fa-info-circle"></i> Campaign rates will be set as the default pricing for it's trips. Making changes to these rates will effect any trips using them. You can always override these rates for each trip.</p>
    @endslot
    <div class="list-group">
        <data-list url="campaigns/{{ $campaign->id }}/costs">
            <template slot="empty">
                <hr class="divider inv">
                <p class="text-center text-muted lead">No costs found.</p>
                <p class="text-center">
                    <button class="btn btn-default-hollow btn-sm" @click="$emit('open:add-cost-modal')">Add Cost</button>
                </p>
            </template>
            <template slot-scope="props" slot="item">
                @verbatim
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2 col-xs-12">
                                <h4 class="text-primary">{{ currency(props.item.amount) }}</h4>
                            </div>
                            <div class="col-sm-6 col-xs-6">
                                <h5>{{ props.item.name|capitalize }}</h5>
                            </div>
                            <div class="col-sm-4 col-xs-6 text-right">
                                <div style="padding: 0;">
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-sm btn-link dropdown-toggle" data-toggle="dropdown">
                                            <i class="fa fa-ellipsis-h"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <template v-if="props.item.type == 'incremental'">
                                                <li><a>Add New Payment</a></li>
                                                <li class="divider"></li>
                                            </template>
                                            <li><a>Edit</a></li>
                                            <li><a>Delete</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <p class="text-muted small">{{ props.item.type|capitalize }}</p>
                            </div>
                            <div class="col-sm-10">
                                <p class="small"><i class="fa fa-check-circle text-success"></i> Active</p>
                                <!-- <p class="small"><i class="fa fa-calendar-o"></i> Effective Date: <em class="text-primary">{{ props.item.active_at|mFormat('ll') }}</em></p> -->
                            </div>
                        </div>

                        <div class="list-group small" v-if="props.item.type == 'incremental'">
                            <div class="list-group-item" v-for="payment in props.item.payments">
                                <div class="row">
                                    <div class="col-xs-2">
                                        <strong>{{ payment.percent_owed.toFixed(2) }}%</strong> Due
                                    </div>
                                    <div class="col-xs-4">
                                        {{ payment.due_at|mFormat('ll') }}
                                    </div>
                                    <div class="col-xs-4">
                                        <strong>{{ payment.grace_period }} day</strong> grace period
                                    </div>
                                    <div class="col-xs-2 text-right">
                                        <a href="#"><i class="fa fa-pencil"></i></a>
                                        <a href="#"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <p class="small">{{ props.item.description }}</p>
                            </div>
                        </div>

                    </div>
                @endverbatim
            </template>
        </data-list>

    </div>
    @slot('footer')
        <div class="text-right">
            <button class="btn btn-primary btn-sm" @click="$emit('open:add-cost-modal')">Add Cost</button>
        </div>
    @endslot
@endcomponent

<mm-modal>
    <template slot="title">Add New Cost</template>
    <ajax-form method="post" action="/campaigns/{{ $campaign->id }}/costs">

    <template slot-scope="props">

        <div class="row">
            <div class="col-md-6">

                <input-select name="type" value="incremental" :options="{
                    'incremental': 'Registration', 'optional': 'Rooming', 'static': 'Other'
                }">
                    <label slot="label">Cost Type</label>
                </input-select>

            </div>
            <div class="col-md-6">

                <input-text name="name">
                    <label slot="label">Cost Name</label>
                </input-text>

            </div>
        </div>
        <div class="row">
            <div class="col-md-6">

                <input-currency name="amount" :value="0">
                    <label slot="label">Amount</label>
                    <span class="help-block" slot="help-text">@{{ props.form }}</span>
                </input-currency>

            </div>
            <div class="col-md-6">

                <input-date name="active_at">
                    <label slot="label">Effective Date</label>
                </input-date>

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">   
                <input-textarea name="short_desc">
                    <label slot="label">Short Description</label>
                    <span class="help-block" slot="help-text">This short description will be shown to users.</span>
                </input-textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label>Payments</label>
                <div class="row">
                    <div class="col-xs-4 col-md-2">
                        <input-number name="payments.percent" placeholder="50%">
                            <span class="help-block" slot="help-text">Percentage due</span>
                        </input-number>
                    </div>
                    <div class="col-xs-8 col-md-4">
                        <input-date name="due_at">
                            <span class="help-block" slot="help-text">Due Date</span>
                        </input-date>
                    <div class="col-xs-4">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="text-right">
                    <a class="btn btn-link" @click="$emit('open:add-cost-modal')">Cancel</a>
                    <button type="submit" class="btn btn-md btn-primary">Add</button>
                </div>
            </div>
        </div>
    </template>

    </ajax-form>
</mm-modal>
@endsection
@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/interests' => 'Interests',
        'active' => 'Edit'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
            <ajax-form method="put" action="interests/{{ $interest->id }}" :horizontal="true" :initial="{{ $interest }}">
                <div class="panel panel-default" slot-scope="{ form }">
                    <div class="panel-heading">
                        <h5>Edit Interest</h5>
                    </div>
                    <div class="panel-body">
                        <input-select classes="col-sm-4" 
                                      v-model="form.status" 
                                      name="status" 
                                      :horizontal="true"
                                      :options="{
                                          undecided: 'Undecided',
                                          converted: 'Converted',
                                          declined: 'Declined'
                                      }"
                        >
                            <label slot="label" class="control-label col-sm-4">Status</label>
                        </input-select>
                        <input-text classes="col-sm-8" name="name" v-model="form.name" :horizontal="true">
                            <label class="control-label col-sm-4" slot="label">
                                Name
                            </label>
                        </input-text>
                        <input-text classes="col-sm-8" name="email" v-model="form.email" :horizontal="true">
                            <label class="control-label col-sm-4" slot="label">
                                Email
                            </label>
                        </input-text>
                        <input-phone classes="col-sm-4" name="phone" v-model="form.phone" :horizontal="true">
                            <label class="control-label col-sm-4" slot="label">
                                Phone
                            </label>
                        </input-phone>
                    </div>
                    <div class="panel-footer text-right">
                        <a href="" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>  
                </div>
            </ajax-form>
            </div>
        </div>
    </div>

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success :timer="3000" redirect="/admin/campaigns/{{ $campaign->id }}/reservations/interests">
        <template slot="title">Nice Work!</template>
        <template slot="message">Changes have been saved.</template>
        <template slot="cancel">Keep Working</template>
        <template slot="confirm">Done</template>
    </alert-success>
@endsection
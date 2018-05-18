@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
@endsection

@section('content')
<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/reservations/">
    <template slot="title">Good job!</template>
    <template slot="message">All changes saved.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<hr class="divider inv lg">
<ajax-form method="put" action="/reservations/{{ $reservation->id }}" :initial="{{ $reservation }}" :horizontal="true">
    <template slot-scope="{ form }">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Settings</h5>
                    <p class="text-muted">Some configurable options.</p>
                </div>
                <div class="col-md-8">
                    @component('panel')
                        @slot('body')
                            <select-user name="user_id" v-model="form.user_id" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Managing User</label>
                                <span slot="help-text" class="help-block">The user account that has access to this reservation</span>
                            </select-user>

                            <input-select name="desired_role" 
                                          v-model="form.desired_role" 
                                          :horizontal="true" 
                                          classes="col-sm-8" 
                                          :options="{{ json_encode(App\Utilities\v1\TeamRole::all()) }}"
                            >
                                <label slot="label" class="control-label col-sm-4">Team Role</label>
                            </input-select>

                            <select-rep name="rep_id" v-model="form.rep_id" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Trip Rep</label>
                                <span slot="help-text" class="help-block">
                                    Override the default trip rep.
                                </span>
                            </select-rep>
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Personal Information</h5>
                    <p class="text-muted">Basic information about the individual.</p>
                </div>
                <div class="col-md-8">
                    @component('panel')
                        @slot('body')
                            <input-text name="given_names" v-model="form.given_names" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Given Names</label>
                                <span slot="help-text" class="help-block">First and middle names as they appear on passport or ID</span>
                            </input-text>

                            <input-text name="surname" v-model="form.surname" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Surname</label>
                                <span slot="help-text" class="help-block">Last name as it appears on passport or ID</span>
                            </input-text>

                            <input-date name="birthday" v-model="form.birthday" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Date of Birth</label>
                            </input-date>

                            <div class="form-group">
                                <label class="control-label col-sm-4">Gender</label>
                                <div class="col-sm-8">
                                    <label class="radio-inline">
                                        <input type="radio" v-model="form.gender" name="gender" value="male"> Male
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" v-model="form.gender" name="gender" value="female"> Female
                                    </label>
                                </div>
                            </div>

                            <input-select name="status" 
                                          v-model="form.status" 
                                          :horizontal="true" 
                                          classes="col-sm-8" 
                                          :options="{'single': 'Single', 'engaged': 'Engaged', 'married': 'Married', 'divorced': 'Divorced', 'widowed': 'Widowed'}"
                            >
                                <label slot="label" class="control-label col-sm-4">Marital Status</label>
                                <span slot="help-text" class="help-block">Required for travel and accommodation purposes</span>
                            </input-select>

                            <input-select name="shirt_size" 
                                          v-model="form.shirt_size" 
                                          :horizontal="true" 
                                          classes="col-sm-8" 
                                          :options="{{ json_encode(App\Utilities\v1\ShirtSize::all()) }}"
                            >
                                <label slot="label" class="control-label col-sm-4">Shirt Size</label>
                                <span slot="help-text" class="help-block">Required for free team t-shirt</span>
                            </input-select>
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <h5>Contact Information</h5>
                    <p class="text-muted">Please provide an accurate mailing address and contact information for this traveler.</p><p class="text-muted">This information is very important for correspondence and for travel arrangements.</p>
                </div>
                <div class="col-md-8">
                    @component('panel')
                        @slot('body')
                            <input-text name="email" v-model="form.email" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Email Address</label>
                            </input-text>

                            <input-phone name="phone_one" v-model="form.phone_one" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Primary Phone</label>
                            </input-phone>

                            <input-phone name="phone_two" v-model="form.phone_two" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Secondary Phone</label>
                            </input-phone>

                            <select-country name="country_code" v-model="form.country_code" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Country</label>
                            </select-country>

                            <input-text name="address" v-model="form.address" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">Mailing Address</label>
                            </input-text>

                            <input-text name="city" v-model="form.city" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">City</label>
                            </input-text>

                            <input-text name="state" v-model="form.state" :horizontal="true" classes="col-sm-8">
                                <label slot="label" class="control-label col-sm-4">State or Providence</label>
                            </input-text>

                            <input-text name="zip" v-model="form.zip" :horizontal="true" classes="col-sm-4">
                                <label slot="label" class="control-label col-sm-4">Zip or Postal Code</label>
                            </input-text>
                        @endslot
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 text-right">
                    <a href="{{ url('/admin/reservations/'.$reservation->id) }}" class="btn btn-link">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </template>
</ajax-form>
@endsection
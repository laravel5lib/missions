@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
@breadcrumbs([ 'links' => [
    'admin' => 'Dashboard', 
    'admin/organizations' => 'Organizations', 
    'admin/organizations/'.$group->id => $group->name,
    'active' => 'Edit'
]])
@endbreadcrumbs

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success redirect="/admin/organizations/{{ $group->id }}">
    <template slot="title">Saved</template>
    <template slot="message">The organization was updated.</template>
    <template slot="cancel">Keep Working</template>
    <template slot="confirm">Done</template>
</alert-success>

<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Update Logo</h5>
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
                
                <ajax-form method="put" action="groups/{{ $group->id }}" :initial="{{ json_encode($group) }}" :horizontal="true">
                    <template slot-scope="{ form }">
                	<div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Edit Organization Details</h5>
                        </div>
                		<div class="panel-body">
                            <input-text name="name" v-model="form.name" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Organization Name</label>
                            </input-text>
                            
                            <input-select 
                                name="type" 
                                v-model="form.type" 
                                :horizontal="true" 
                                classes="col-md-8" 
                                :options="{
                                    'church': 'Church', 
                                    'business': 'Business',
                                    'independent': 'Independent', 
                                    'nonprofit': 'Non-profit',
                                    'school': 'School',
                                    'youth': 'Youth',
                                    'other': 'Other'
                                }"
                            >
                                <label slot="label" class="control-label col-md-4">Organization Type</label>
                            </input-select>

                            <input-text name="email" v-model="form.email" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Email</label>
                            </input-text>

                            <input-phone name="phone_one" v-model="form.phone_one" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Primary Phone</label>
                            </input-phone>

                            <input-phone name="phone_two" v-model="form.phone_two" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Secondary Phone</label>
                            </input-phone>

                            <input-text name="address_one" v-model="form.address_one" :horizontal="true" classes="col-md-8" placeholder="Address line 1">
                                <label slot="label" class="control-label col-md-4">Mailing Address</label>
                            </input-text>
                            <input-text name="address_two" v-model="form.address_two" :horizontal="true" classes="col-md-8" placeholder="Address line 2">
                                <label slot="label" class="control-label col-md-4"></label>
                            </input-text>

                            <input-text name="city" v-model="form.city" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">City</label>
                            </input-text>

                            <input-text name="state" v-model="form.state" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">State/Providence</label>
                            </input-text>

                            <input-text name="zip" v-model="form.zip" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Zip/Postal</label>
                            </input-text>

                            <select-country name="country_code" v-model="form.country_code" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Country</label>
                            </select-country>    

                            <select-timezone name="timezone" v-model="form.timezone" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Timezone</label>
                            </select-timezone> 
    		            </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Update Public Profile</h5>
                        </div>
                        <div class="panel-body">             
                            <input-radio-group 
                                name="public" 
                                v-model="form.public" 
                                :horizontal="true" 
                                classes="col-md-8" 
                                :options="[{value: true, label: 'Public'}, {value: false, label: 'Private'}]">
                                <label slot="label" class="control-label col-md-4">Visibility</label>
                            </input-radio-group>

                            <input-textarea name="description" v-model="form.description" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Profile Description</label>
                            </input-textarea>

                            <input-text name="url" v-model="form.url" :horizontal="true" classes="col-md-8">
                                <span slot="prefix" class="input-group-addon">missions.me/</span>
                                <label slot="label" class="control-label col-md-4">Profile Link</label>
                            </input-text>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <a href="/admin/organizations/{{ $group->id }}" class="btn btn-link">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                    </template>
                </ajax-form>

            </div>
        </div>
    </div>
<hr class="divider inv xlg">    
@endsection
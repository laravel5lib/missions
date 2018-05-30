@extends('site.layouts.default')

@section('content')

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Please check the form for errors and try again.</template>
    </alert-error>

    <alert-success redirect="/teams">
        <template slot="title">Thank you!</template>
        <template slot="message">A Missions.Me representative will contact you soon.</template>
        <template slot="confirm">Okay</template>
    </alert-success>

    <div class="content-page-header">
        <img class="img-responsive" src="/images/groups/groups-header.jpg" alt="">
        <div class="c-page-header-text">
            <h1 class="text-uppercase dash-trailing">Take Your Church or Organization</h1>
        </div>
    </div>
    <div class="white-bg">
        <div class="container">
            <div class="content-section">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
                        <h2 class="text-primary">Team trips are what we do!</h2>
                        <p>Missions.Me specializes in taking teams form around the world on life-changing missions experiences.  If you are interested in partnering with one of our missions trips, please fill out the form.  Missions.Me can provide your group with its own profile, URL and customized  trips created especially for your church or organization.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="divider inv xlg">

    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1">
            <ajax-form method="post" action="/leads" :horizontal="true" :initial="{ 
                'category_id': 1, 
                'content': { 'spoke_with_rep': false} 
            }">
                <template slot-scope="{ form }">

                    <div class="panel panel-default panel-body">
                        <input-select :horizontal="true" 
                                    classes="col-sm-8" 
                                    name="content.campaign_of_interest" 
                                    v-model="form.content.campaign_of_interest"
                                    :options="{{ json_encode($trips) }}"
                        >
                            <label slot="label" class="control-label col-sm-4">Which trip are you interested in?</label>
                        </input-select>
                        
                        <div class="col-sm-offset-4" style="padding-left: 1em">
                            <input-checkbox name="content.spoke_with_rep" 
                                            v-model="form.content.spoke_with_rep"
                            >
                                <label slot="label" class="control-label">
                                    I have spoken with a Missions.Me Representative
                                </label>
                            </input-checkbox>
                        </div>
                    </div>
                    
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Organization Information</h5>
                        </div>
                        <div class="panel-body">

                            <input-text :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.organization"
                                        v-model="form.content.organization"
                            >
                                <label slot="label" class="control-label col-sm-4">Organization Name</label>
                            </input-text>

                            <input-select :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.type" 
                                        v-model="form.content.type"
                                        :options="{
                                            'business': 'Business',
                                            'church': 'Church',
                                            'independent': 'Independent',
                                            'nonprofit': 'Nonprofit',
                                            'other': 'Other',
                                            'school': 'School',
                                            'youth': 'Youth Group'
                                        }"
                            >
                                <label slot="label" class="control-label col-sm-4">Type of Organization</label>
                            </input-select>

                            <select-country :horizontal="true" 
                                            classes="col-sm-8" 
                                            v-model="form.content.country"
                                            name="content.country"
                            >
                                <label slot="label" class="control-label col-sm-4">Country</label>
                            </select-country>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.address_one"
                                        v-model="form.content.address_one"
                            >
                                <label slot="label" class="control-label col-sm-4">Address Line One</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.address_two"
                                        v-model="form.content.address_two"
                            >
                                <label slot="label" class="control-label col-sm-4">Address Line Two</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8 col-md-4" 
                                        name="content.city"
                                        v-model="form.content.city"
                            >
                                <label slot="label" class="control-label col-sm-4">City</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8 col-md-4" 
                                        name="content.state"
                                        v-model="form.content.state"
                            >
                                <label slot="label" class="control-label col-sm-4">State/Providence</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-4 col-md-2" 
                                        name="content.zip"
                                        v-model="form.content.zip"
                            >
                                <label slot="label" class="control-label col-sm-4">Zip/Postal Code</label>
                            </input-text>

                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Contact Information</h5>
                        </div>
                        <div class="panel-body">

                            <input-text :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.contact"
                                        v-model="form.content.contact"
                            >
                                <label slot="label" class="control-label col-sm-4">Name of Contact</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8" 
                                        name="content.position"
                                        v-model="form.content.position"
                            >
                                <label slot="label" class="control-label col-sm-4">Position in the Organization</label>
                            </input-text>

                            <input-text :horizontal="true" 
                                        classes="col-sm-8 col-md-4" 
                                        name="content.email"
                                        v-model="form.content.email"
                            >
                                <label slot="label" class="control-label col-sm-4">Email Address</label>
                            </input-text>

                            <input-phone :horizontal="true" 
                                        classes="col-sm-8 col-md-4" 
                                        name="content.phone_one"
                                        v-model="form.content.phone_one"
                            >
                                <label slot="label" class="control-label col-sm-4">Primary Phone</label>
                            </input-phone>

                            <input-phone :horizontal="true" 
                                        classes="col-sm-8 col-md-4" 
                                        name="content.phone_two"
                                        v-model="form.content.phone_two"
                            >
                                <label slot="label" class="control-label col-sm-4">Secondary Phone</label>
                            </input-phone>

                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary pull-right">Submit</button>

                </template>
            </ajax-form>
        </div>
    </div>

    <hr class="divider inv xlg">
@endsection
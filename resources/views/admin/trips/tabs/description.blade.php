@component('panel')
    @slot('title')
        <h5>Public Page &amp; Registration</h5>
    @endslot
    @slot('body')
        <label>Public Page</label>
        @if(optional($trip->published_at)->isPast())
            <pre>{{ url('/trips/'.$trip->id) }}</pre>  
        @else
            <pre>{{ url('/trips/'.$trip->id) .'(unpublished)' }}</s></pre>  
        @endif
        @if($trip->public)
            <label>Registration Form</label>
            <pre>{{ url('/trips/'.$trip->id.'/register') }}</pre>
            <span class="help-block">End-user will be prompted to log in.</span>
        @endif
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Manage Public Page</h5>
    @endslot
    @slot('body')
        <ajax-form method="put" action="/trips/{{ $trip->id }}" :initial="{{ json_encode([
            'published_at' => $trip->published_at ? $trip->published_at->toDateTimeString() : null,
            'public' => $trip->public,
            'description' => $trip->description
        ])}}">
            <template slot-scope="{ form }">
                <div class="row">
                    <div class="col-sm-6">
                        <input-datetime name="published_at" v-model="form.published_at">
                            <label slot="label">Publish (optional)</label>
                            <span class="help-block" slot="help-text">
                                Set the date and time this page should be available to the public. Leave blank to keep private.
                            </span>
                        </input-datetime>
                    </div>
                    <div class="col-sm-6">
                        <label>Registration Form</label>
                        <input-checkbox name="public" v-model="form.public">
                            <span slot="label">Allow public access</span>
                        </input-checkbox>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <input-markdown name="description" 
                                        placeholder="Enter page content here..." 
                                        v-model="form.description">
                            <label slot="label">Page Content</label>
                            <span class="help-block" slot="help-text">
                                Use "Markdown" to format text.
                                <a type="button" data-toggle="modal" data-target="#markdownExamplesModal">
                                    [ <i class="fa fa-info-circle"></i> Markdown Tutorial ]
                                </a>
                            </span>
                        </input-markdown>
                        <markdown-example-modal></markdown-example-modal>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-right">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </template>
        </ajax-form>
    @endslot
@endcomponent

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :reload="true" :timer="3000">
    <template slot="title">Good job!</template>
    <template slot="message">All changes saved.</template>
</alert-success>
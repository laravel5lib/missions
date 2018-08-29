@component('list-group', [
    'data' => (new \App\RequirementListGroup($requirement))->toHtml()
])
@endcomponent
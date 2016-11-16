<notes type="trips"
       id="{{ $trip->id }}"
       user_id="{{ auth()->user()->id }}"
       :per_page="10"
       :can-modify="{{ auth()->user()->can('modify-notes') }}">
</notes>
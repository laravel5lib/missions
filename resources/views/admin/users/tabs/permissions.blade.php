@extends('admin.users.show')

@section('tab')

@hasanyrole('super_admin|admin')
<user-permissions user_id="{{ $user->id }}"
                  user-roles="{{ $user->roles->pluck('name') }}">
</user-permissions>
@endhasanyrole

@endsection
@extends('admin.users.show')

@section('tab')

@component('panel')
    @slot('body')
        <user-upload 
            :user="{{ $user }}" 
            :avatar="{{ $user->getAvatar() }}" 
            :banner="{{ $user->getBanner() }}"
        ></user-upload>
    @endslot
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>User Details</h5>
            </div>
            <div class="col-xs-4 text-right">
                 @can('update', $user)
                    <a class="btn btn-primary btn-sm" href="{{ url('/admin/users/'.$user->id.'/edit') }}">
                       Edit
                    </a>
                @endcan
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'First Name' => $user->first_name,
        'Last Name' => $user->last_name,
        'Gender' => $user->gender,
        'Marital Status' => $user->status,
        'Birthday' => optional($user->birthday)->toFormattedDateString(),
        'Email' => $user->email,
        'Alternate Email' => $user->alt_email,
        'Primary Phone' => $user->phone_one,
        'Secondary Phone' => $user->phone_two,
        'Address' => $user->address.'<br />'.$user->city.', '.$user->state.' '.$user->zip,
        'Country' => country($user->country_code),
        'Timezone' => $user->timezone,
        'Profile' => ($user->public ? 'Public' : 'Private'),
        'URL' => $user->slug ? '<strong><a href="'.url($user->slug->url).'" target="_blank">'.url($user->slug->url).'</a></strong>' : null
    ]])
    @endcomponent
    @slot('footer')
    <div class="text-right">
        <send-email label="Resend Welcome Email"
                        icon="fa fa-envelope icon-left"
                        classes="btn btn-default btn-sm"
                        command="email:send-welcome"
                        :parameters="{id: '{{ $user->id }}', email: '{{ $user->email }}'}">
        </send-email>
    </div>
    @endslot
@endcomponent

@can('delete', $user)
    @component('panel')
        @slot('title')
            <h5>Delete User</h5>
        @endslot
        @slot('body')
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                    <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will permanently delete the user and any reservations they are managing.</div>
                </div>
            </div>
            <delete-form url="users/{{ $user->id }}" 
                redirect="/admin/users"
                label="Enter the user's full name to delete them"
                match-value="{{ $user->name }}"
            ></delete-form>
        @endslot
    @endcomponent
@endcan

@endsection
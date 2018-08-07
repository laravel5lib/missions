@extends('layouts.admin')

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
    <group-edit :group="{{ json_encode($group) }}"></group-edit>
<hr class="divider inv xlg">    
@endsection
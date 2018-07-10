@extends('admin.campaigns.groups.show')

@section('tab')
<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new requirement was added.</template>
</alert-success>

@endsection
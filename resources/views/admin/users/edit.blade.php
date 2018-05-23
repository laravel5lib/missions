@extends('admin.layouts.default')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'admin/users' => 'Users',
    'admin/users/'.$user->id => $user->name,
    'active' => 'Edit'
]])
@endbreadcrumbs
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <admin-user-edit user-id="{{ $user->id }}"></admin-user-edit>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
@endsection
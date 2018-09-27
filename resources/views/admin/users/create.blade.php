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
    'active' => 'New'
]])
@endbreadcrumbs
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                
                <h1>Create New User</h1>
                <user-form></user-form>            
    
            </div>
        </div>
    </div>
@endsection
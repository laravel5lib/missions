@extends('admin.layouts.default')

@section('content')
  <div class="container">
    <h1>Users</h1>
    <table class="table table-striped">
    @foreach($users as $user)
    <tr>
      <td>{{ $user->name }}</td>
    </tr>
    @endforeach
    </table>
  </div>
@endsection
@extends('layouts.admin')

@section('content')
    <h1>Users</h1>

    <a href="{{ route('admin.users.create') }}">Create User</a>

    <table border="1" cellpadding="5">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Branches</th>
        </tr>

        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td>
                    {{ $user->branches->pluck('name')->join(', ') }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection
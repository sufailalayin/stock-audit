@extends('layouts.admin')

@section('content')
    <h1>Create User</h1>

    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div>
            <label>Name</label><br>
            <input type="text" name="name" required>
        </div>

        <div>
            <label>Email</label><br>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Password</label><br>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Role</label><br>
            <select name="role" required>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select>
        </div>

        <div>
            <label>Branches</label><br>
            @foreach($branches as $branch)
                <label>
                    <input type="checkbox" name="branches[]" value="{{ $branch->id }}">
                    {{ $branch->branch_name }}
                </label><br>
            @endforeach
        </div>

        <br>
        <button type="submit">Create User</button>
    </form>
@endsection
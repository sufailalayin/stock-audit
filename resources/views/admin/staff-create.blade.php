<h2>Add Staff</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form method="POST">
    @csrf

    <input type="text" name="name" placeholder="Staff Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <select name="branch_id" required>
        <option value="">Select Branch</option>
        @foreach($branches as $branch)
            <option value="{{ $branch->id }}">
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select><br><br>

    <button type="submit">Add Staff</button>
</form>

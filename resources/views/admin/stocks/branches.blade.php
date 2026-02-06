@extends('layouts.admin')

@section('content')
@if(session('success'))
    <div style="
        background:#e6fffa;
        border:1px solid #38b2ac;
        color:#065f46;
        padding:12px;
        margin-bottom:15px;
        border-radius:6px;
    ">
        ✅ {{ session('success') }}
    </div>
@endif
<div class="container-fluid">
    <h4 class="mb-4">Branches</h4>

    <div class="card">
        <div class="card-body">
            @if($branches->count() == 0)
                <p>No branches found.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Branch Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($branches as $branch)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $branch->branch_name }}</td>
                            <td>
                                <a href="{{ route('admin.branches.files', $branch->id) }}"
                                class="btn btn-sm btn-primary">
                                 View Files
                                 </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <h4 class="mb-4">
        Stock Files — {{ $branch->branch_name }}
    </h4>

    <div class="card shadow border-0">
        <div class="card-body">

            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>File Name</th>
                        <th>Total Items</th>
                        <th>Mismatches</th>
                        <th>Uploaded Date</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($files as $file)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $file->file_name }}</td>
                        <td>{{ $file->total_items }}</td>
                        <td class="text-danger fw-bold">
                            {{ $file->mismatch_items ?? 0 }}
                        </td>
                        <td>{{ $file->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.stocks.show', $file->id) }}"
                               class="btn btn-sm btn-primary">
                                Open
                            </a>

                            <form action="{{ route('admin.audit-files.destroy', $file->id) }}"
                                  method="POST"
                                  style="display:inline"
                                  onsubmit="return confirm('Delete this audit file? This cannot be undone!')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No files found
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>

        </div>
    </div>

</div>
@endsection
@section('content')
    <h2>Stock Audit – Branches</h2>

    <table border="1" cellpadding="10" width="100%">
        <thead>
            <tr>
                <th>Branch Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($branches as $branch)
                <tr>
                    <td>{{ $branch->branch_name }}</td>
                    <td>
                        <a href="#">
                            View Files
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
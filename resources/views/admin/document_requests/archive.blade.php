@extends('layouts.app')

@section('title', 'Archived Records')

@section('content')
    <div class="card">
        <div class="grid lg:grid-cols-[1fr_auto] gap-4 mb-4">
            <div>
                <h2 class="mb-2">Archived Records</h2>
                <p class="text-sm text-muted">Search and review archived document requests.</p>
            </div>
            <form action="{{ route('admin.documents.archived') }}" method="get" class="flex gap-2 flex-wrap">
                <input type="search" name="search" placeholder="Search by name, ID, document" value="{{ request('search') }}">
                <button type="submit" class="button button-secondary button-small">Search</button>
            </form>
        </div>

        @if ($requests->isEmpty())
            <p class="text-sm text-muted">No archived requests found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Document</th>
                        <th>Copies</th>
                        <th>Status</th>
                        <th>Released</th>
                        <th>Archived</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->student_name }}<br><span class="text-sm text-muted">{{ $request->student_id }}</span></td>
                            <td>{{ $request->document_label }}</td>
                            <td>{{ $request->copies }}</td>
                            <td><span class="badge {{ \App\Models\DocumentRequest::statusBadgeClass($request->status) }}">{{ $request->status }}</span></td>
                            <td class="text-sm text-muted">{{ optional($request->released_at)->format('M d, Y') ?? 'N/A' }}</td>
                            <td class="text-sm text-muted">{{ $request->updated_at->format('M d, Y') }}</td>
                            <td class="flex gap-2">
                        <a href="{{ route('admin.documents.show', $request) }}" class="button button-secondary button-small">View</a>
                        <form action="{{ route('admin.documents.restore', $request) }}" method="post" class="m-0">
                            @csrf
                            <button type="submit" class="button button-primary button-small">Restore</button>
                        </form>
                    </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection

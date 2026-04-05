@extends('layouts.app')

@section('title', 'Admin Document Requests')

@section('content')
    <div class="card">
        <div class="grid lg:grid-cols-[1fr_auto] gap-4 mb-4">
            <div>
                <h2 class="mb-2">Document Requests</h2>
                <p class="text-sm text-muted">Review student requests and update their status.</p>
            </div>
            <form action="{{ route('admin.documents.index') }}" method="get" class="flex gap-2 flex-wrap">
                <input type="search" name="search" placeholder="Search by name, ID, document" value="{{ request('search') }}">
                <select name="status">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
                <button type="submit" class="button button-secondary button-small">Filter</button>
            </form>
        </div>

        @if ($requests->isEmpty())
            <p class="text-sm text-muted">No matching requests found.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Document</th>
                        <th>Copies</th>
                        <th>Course / Year</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->student_name }}<br><span class="text-sm text-muted">{{ $request->student_id }}</span></td>
                            <td>{{ $request->document_label }}</td>
                            <td>{{ $request->copies }}</td>
                            <td>{{ $request->course_year }}</td>
                            <td><span class="badge {{ \App\Models\DocumentRequest::statusBadgeClass($request->status) }}">{{ $request->status }}</span></td>
                            <td class="text-sm text-muted">{{ $request->created_at->format('M d, Y') }}</td>
                            <td><a href="{{ route('admin.documents.show', $request) }}" class="button button-primary button-small">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-4">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection

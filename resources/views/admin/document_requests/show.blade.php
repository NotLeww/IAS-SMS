@extends('layouts.app')

@section('title', 'Request Details')

@section('content')
    <div class="card">
        <div class="grid lg:grid-cols-[1fr_auto] gap-4 mb-4">
            <div>
                <h2>Request Details</h2>
                <p class="text-sm text-muted">Manage the request lifecycle and track release history.</p>
            </div>
            <div class="text-right">
                <a href="{{ route('admin.documents.preview', $documentRequest) }}" class="button button-secondary button-small">Preview</a>
                <a href="{{ route('admin.documents.download', $documentRequest) }}" class="button button-secondary button-small">Download</a>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-[1.1fr_0.9fr]">
            <div>
                <div class="mb-4">
                    <strong>Student</strong>
                    <p>{{ $documentRequest->student_name }} ({{ $documentRequest->student_id }})</p>
                </div>
                <div class="mb-4">
                    <strong>Document</strong>
                    <p>{{ $documentRequest->document_label }}</p>
                </div>
                <div class="mb-4">
                    <strong>Copies</strong>
                    <p>{{ $documentRequest->copies }}</p>
                </div>
                <div class="mb-4">
                    <strong>Course / Year</strong>
                    <p>{{ $documentRequest->course_year }}</p>
                </div>
                <div class="mb-4">
                    <strong>Purpose</strong>
                    <p>{{ $documentRequest->purpose }}</p>
                </div>
                <div class="mb-4">
                    <strong>Status</strong>
                    <div class="mt-2"><span class="badge {{ \App\Models\DocumentRequest::statusBadgeClass($documentRequest->status) }}">{{ $documentRequest->status }}</span></div>
                </div>
                @if ($documentRequest->released_at)
                    <div class="mb-4">
                        <strong>Release Date</strong>
                        <p>{{ $documentRequest->released_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-4">
                        <strong>Claimed By</strong>
                        <p>{{ $documentRequest->released_to }}</p>
                    </div>
                @endif
            </div>

            <div>
                <div class="card mb-4">
                    <h3 class="mb-2">Admin Actions</h3>
                    <form action="{{ route('admin.documents.process', $documentRequest) }}" method="post" class="mb-3">
                        @csrf
                        <button type="submit" class="button button-secondary button-small">Mark as Processing</button>
                    </form>
                    <form action="{{ route('admin.documents.approve', $documentRequest) }}" method="post" class="mb-3">
                        @csrf
                        <button type="submit" class="button button-primary button-small">Approve</button>
                    </form>
                    <form action="{{ route('admin.documents.reject', $documentRequest) }}" method="post" class="mb-3">
                        @csrf
                        <label for="admin_notes">Reject Notes</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3"></textarea>
                        <button type="submit" class="button button-danger button-small mt-2">Reject</button>
                    </form>
                    <form action="{{ route('admin.documents.release', $documentRequest) }}" method="post" class="mb-3">
                        @csrf
                        <label for="released_to">Claiming Info</label>
                        <input id="released_to" name="released_to" type="text" value="{{ old('released_to') }}" placeholder="Name of claimant" required>
                        <label for="release_notes" class="mt-2">Release Notes</label>
                        <textarea id="release_notes" name="release_notes" rows="3"></textarea>
                        <button type="submit" class="button button-warning button-small mt-2">Mark Released</button>
                    </form>
                    <form action="{{ route('admin.documents.archive', $documentRequest) }}" method="post">
                        @csrf
                        <button type="submit" class="button button-secondary button-small">Archive Record</button>
                    </form>
                </div>

                <div class="card">
                    <h3 class="mb-2">Release Log</h3>
                    @if ($documentRequest->releaseLogs->isEmpty())
                        <p class="text-sm text-muted">No log entries yet.</p>
                    @else
                        <ul class="text-sm">
                            @foreach ($documentRequest->releaseLogs as $log)
                                <li class="mb-3">
                                    <strong>{{ $log->action }}</strong><br>
                                    <span class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</span>
                                    <p class="mt-1">{{ $log->details }}</p>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

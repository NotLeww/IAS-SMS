@extends('layouts.app')

@section('title', 'Document Preview')

@section('content')
    <div class="card">
        <div class="grid lg:grid-cols-[1fr_auto] gap-4 mb-6">
            <div>
                <h2>Document Preview</h2>
                <p class="text-sm text-muted">This preview fills the student information into the selected document template.</p>
            </div>
            @unless($download)
                <div>
                    <button onclick="window.print();" class="button button-secondary button-small">Print / Export as PDF</button>
                    <a href="{{ route('admin.documents.download', $documentRequest) }}" class="button button-primary button-small">Download</a>
                </div>
            @endunless
        </div>

        <div class="card" style="background:#f1f5f9;">
            <h3 style="margin-top:0;">{{ $documentRequest->document_label }}</h3>
            <p class="text-sm text-muted">Generated for {{ $documentRequest->student_name }} ({{ $documentRequest->student_id }})</p>
            <hr class="mt-4 mb-4" />
            <div class="mb-4">
                <strong>Copies:</strong> {{ $documentRequest->copies }}
            </div>
            <div class="mb-4">
                <strong>Course / Year:</strong> {{ $documentRequest->course_year }}
            </div>
            <div class="mb-4">
                <strong>Purpose:</strong>
                <p>{{ $documentRequest->purpose }}</p>
            </div>
            <div class="mb-4">
                <strong>Status:</strong> <span class="badge {{ \App\Models\DocumentRequest::statusBadgeClass($documentRequest->status) }}">{{ $documentRequest->status }}</span>
            </div>
            <div class="mb-4">
                <strong>Generated on:</strong> {{ now()->format('M d, Y') }}
            </div>
            <div style="padding:18px;background:#fff;border-radius:12px;border:1px solid #e2e8f0;">
                <h4>Document Body</h4>
                <p>This is to certify that <strong>{{ $documentRequest->student_name }}</strong>, student ID <strong>{{ $documentRequest->student_id }}</strong>, currently enrolled in <strong>{{ $documentRequest->course_year }}</strong>, has requested a <strong>{{ $documentRequest->document_label }}</strong>.</p>
                <p>The requested document is intended for: <em>{{ $documentRequest->purpose }}</em>.</p>
                <p>Approved by the document processing office and ready for release.</p>
            </div>
            <div class="mt-4 text-sm text-muted">
                <p>Use browser print or download to generate a printable version of this document.</p>
            </div>
        </div>
    </div>
@endsection

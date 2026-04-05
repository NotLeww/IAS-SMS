<?php

namespace App\Http\Controllers;

use App\Models\DocumentRequest;
use App\Models\StudentSummary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class DocumentRequestController extends Controller
{
    public function studentForm()
    {
        $requests = DocumentRequest::latest()->take(10)->get();
        $students = StudentSummary::orderBy('full_name')->get(['id_number', 'full_name', 'program', 'yearLevel']);

        return view('document_request.form', [
            'documentTypes' => DocumentRequest::DOCUMENT_TYPES,
            'requests' => $requests,
            'students' => $students,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_name' => 'required|string|max:255',
            'student_id' => 'required|string|max:255',
            'course_year' => 'nullable|string|max:255',
            'document_types' => 'required|array|min:1',
            'document_types.*' => 'required|in:' . implode(',', array_keys(DocumentRequest::DOCUMENT_TYPES)),
            'copies' => 'required|array',
            'copies.*' => 'required|integer|min:1',
            'purpose' => 'required|string|max:1000',
        ]);

        // Check if student exists
        $student = StudentSummary::where('id_number', $request->student_id)->first();
        if (!$student) {
            return Redirect::back()->withInput()->withErrors(['student_id' => 'Student ID not found in the system. Please contact the registrar if you believe this is an error.']);
        }

        // Check if student name matches
        if (strtolower($student->full_name) !== strtolower($request->student_name)) {
            return Redirect::back()->withInput()->withErrors(['student_name' => 'Student name does not match the registered name for this Student ID.']);
        }

        // Use course_year from student record if available, otherwise use the submitted one
        $courseYear = $student->course_year ?: $request->course_year;

        foreach ($request->document_types as $documentType) {
            $copies = max(1, (int) ($request->copies[$documentType] ?? 1));

            $documentRequest = DocumentRequest::create([
                'student_name' => $request->student_name,
                'student_id' => $request->student_id,
                'course_year' => $courseYear,
                'document_type' => $documentType,
                'copies' => $copies,
                'purpose' => $request->purpose,
                'status' => DocumentRequest::STATUS_PENDING,
            ]);

            $documentRequest->releaseLogs()->create([
                'action' => 'Submitted',
                'details' => "Request created for {$copies} copy(ies) of {$documentType} and marked as Pending.",
            ]);
        }

        return Redirect::route('documents.request.form')->with('success', 'Your document request has been submitted and is pending review.');
    }

    public function adminIndex(Request $request)
    {
        $query = DocumentRequest::query()
            ->where('archived', false)
            ->search($request->search)
            ->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('admin.document_requests.index', [
            'requests' => $requests,
            'statuses' => [
                DocumentRequest::STATUS_PENDING,
                DocumentRequest::STATUS_PROCESSING,
                DocumentRequest::STATUS_APPROVED,
                DocumentRequest::STATUS_READY_FOR_RELEASE,
                DocumentRequest::STATUS_RELEASED,
                DocumentRequest::STATUS_REJECTED,
            ],
        ]);
    }

    public function show(DocumentRequest $documentRequest)
    {
        return view('admin.document_requests.show', [
            'documentRequest' => $documentRequest->load('releaseLogs'),
        ]);
    }

    public function process(DocumentRequest $documentRequest)
    {
        if ($documentRequest->status !== DocumentRequest::STATUS_PENDING) {
            return Redirect::back()->with('warning', 'Only pending requests can be marked as processing.');
        }

        $documentRequest->update(['status' => DocumentRequest::STATUS_PROCESSING]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Processing',
            'details' => 'Request moved from Pending to Processing.',
        ]);

        return Redirect::back()->with('success', 'Request is now being processed.');
    }

    public function approve(DocumentRequest $documentRequest)
    {
        if ($documentRequest->status === DocumentRequest::STATUS_REJECTED) {
            return Redirect::back()->with('warning', 'Rejected requests cannot be approved.');
        }

        $documentRequest->update([
            'status' => DocumentRequest::STATUS_READY_FOR_RELEASE,
            'approved_at' => now(),
        ]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Approved',
            'details' => 'Request approved and moved to Ready for Release.',
        ]);

        return Redirect::back()->with('success', 'Request approved and ready for release.');
    }

    public function reject(Request $request, DocumentRequest $documentRequest)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $documentRequest->update(['status' => DocumentRequest::STATUS_REJECTED]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Rejected',
            'details' => 'Request rejected. ' . ($request->admin_notes ?: ''),
        ]);

        return Redirect::back()->with('success', 'Request rejected and the student will be notified.');
    }

    public function preview(DocumentRequest $documentRequest)
    {
        return view('admin.document_requests.preview', [
            'documentRequest' => $documentRequest,
            'download' => false,
        ]);
    }

    public function download(DocumentRequest $documentRequest)
    {
        $fileName = sprintf('document-request-%s.html', $documentRequest->id);
        $content = view('admin.document_requests.preview', [
            'documentRequest' => $documentRequest,
            'download' => true,
        ])->render();

        return Response::make($content, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    public function release(Request $request, DocumentRequest $documentRequest)
    {
        $request->validate([
            'released_to' => 'required|string|max:255',
            'release_notes' => 'nullable|string|max:1000',
        ]);

        if (! in_array($documentRequest->status, [
            DocumentRequest::STATUS_APPROVED,
            DocumentRequest::STATUS_READY_FOR_RELEASE,
        ], true)) {
            return Redirect::back()->with('warning', 'Only approved requests can be released.');
        }

        $documentRequest->update([
            'status' => DocumentRequest::STATUS_RELEASED,
            'released_at' => now(),
            'released_to' => $request->released_to,
        ]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Released',
            'details' => 'Document released to ' . $request->released_to . '. ' . ($request->release_notes ?: ''),
        ]);

        return Redirect::back()->with('success', 'Document marked as released.');
    }

    public function archive(DocumentRequest $documentRequest)
    {
        $documentRequest->update(['archived' => true]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Archived',
            'details' => 'Request moved to archived records.',
        ]);

        return Redirect::back()->with('success', 'Request moved to archived records.');
    }

    public function restore(DocumentRequest $documentRequest)
    {
        $documentRequest->update(['archived' => false]);
        $documentRequest->releaseLogs()->create([
            'action' => 'Restored',
            'details' => 'Archived request restored to active workflow.',
        ]);

        return Redirect::back()->with('success', 'Request restored from archived records.');
    }

    public function archiveIndex(Request $request)
    {
        $requests = DocumentRequest::query()
            ->where('archived', true)
            ->search($request->search)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.document_requests.archive', [
            'requests' => $requests,
        ]);
    }
}

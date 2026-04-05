@extends('layouts.app')

@section('title', 'Student Document Request')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[1.3fr_1fr]">
        <div class="card">
            <h2 class="mb-4">Submit a Document Request</h2>
            <form action="{{ route('documents.request.store') }}" method="post">
                @csrf

                <label for="student_id">Student ID</label>
                <select id="student_id" name="student_id" required>
                    <option value="">Choose student ID</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id_number }}" data-name="{{ $student->full_name }}" data-course="{{ $student->course_year }}" {{ old('student_id') === $student->id_number ? 'selected' : '' }}>
                            {{ $student->id_number }} — {{ $student->full_name }}
                        </option>
                    @endforeach
                </select>
                <small class="text-sm text-muted">Choose your Student ID and the form will fill your name and course automatically.</small>

                <label for="student_name" class="mt-4">Name</label>
                <input id="student_name" type="text" name="student_name" value="{{ old('student_name') }}" readonly required>
                <small class="text-sm text-muted">Auto-filled from the selected Student ID.</small>

                <label for="course_year" class="mt-4">Course / Year</label>
                <input id="course_year" type="text" name="course_year" value="{{ old('course_year') }}" readonly>
                <small class="text-sm text-muted">Auto-filled from your student record.</small>

                <label class="mt-4">Document Type(s)</label>
                <div class="grid gap-3">
                    @foreach ($documentTypes as $key => $label)
                        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
                            <label style="display:flex;align-items:center;gap:8px;">
                                <input type="checkbox" name="document_types[]" value="{{ $key }}" {{ is_array(old('document_types')) && in_array($key, old('document_types')) ? 'checked' : '' }}>
                                <span>{{ $label }}</span>
                            </label>
                            <div style="display:flex;gap:8px;align-items:center;">
                                <label for="copies_{{ Illuminate\Support\Str::slug($key) }}" style="font-size:.9rem;color:#94a3b8;">Copies</label>
                                <input id="copies_{{ Illuminate\Support\Str::slug($key) }}" type="number" name="copies[{{ $key }}]" min="1" value="{{ old('copies.' . $key, 1) }}" style="width:90px;">
                            </div>
                        </div>
                    @endforeach
                </div>

                <label for="purpose" class="mt-4">Purpose</label>
                <textarea id="purpose" name="purpose" rows="4" required>{{ old('purpose') }}</textarea>

                <div class="mt-4">
                    <button type="submit" class="button button-primary">Submit Request</button>
                </div>
            </form>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const studentIdSelect = document.getElementById('student_id');
                const studentNameInput = document.getElementById('student_name');
                const courseYearInput = document.getElementById('course_year');

                function updateStudentFields() {
                    const selectedOption = studentIdSelect.options[studentIdSelect.selectedIndex];
                    const name = selectedOption.dataset.name || '';
                    const course = selectedOption.dataset.course || '';

                    studentNameInput.value = name;
                    courseYearInput.value = course;
                }

                studentIdSelect.addEventListener('change', updateStudentFields);

                if (studentIdSelect.value) {
                    updateStudentFields();
                }
            });
        </script>

        <div class="card">
            <h2 class="mb-4">Recent Requests</h2>
            @if ($requests->isEmpty())
                <p class="text-sm text-muted">No requests yet. Submit one using the form.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Copies</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $request)
                            <tr>
                                <td>{{ $request->document_label }}</td>
                                <td>{{ $request->copies }}</td>
                                <td>
                                    <span class="badge {{ \App\Models\DocumentRequest::statusBadgeClass($request->status) }}">{{ $request->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DocumentRequest extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PROCESSING = 'Processing';
    public const STATUS_APPROVED = 'Approved';
    public const STATUS_REJECTED = 'Rejected';
    public const STATUS_READY_FOR_RELEASE = 'Ready for Release';
    public const STATUS_RELEASED = 'Released';

    public const DOCUMENT_TYPES = [
        'TOR' => 'Transcript of Records',
        'Good Moral' => 'Good Moral Certificate',
        'Registration Form' => 'Registration Form',
    ];

    protected $fillable = [
        'student_name',
        'student_id',
        'course_year',
        'document_type',
        'copies',
        'purpose',
        'status',
        'released_to',
        'archived',
    ];

    protected $casts = [
        'archived' => 'boolean',
        'approved_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public static function statusBadgeClass(string $status): string
    {
        return match ($status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_PROCESSING => 'bg-blue-100 text-blue-800',
            self::STATUS_APPROVED => 'bg-sky-100 text-sky-800',
            self::STATUS_READY_FOR_RELEASE => 'bg-indigo-100 text-indigo-800',
            self::STATUS_RELEASED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function documentTypeLabel(string $key): string
    {
        return self::DOCUMENT_TYPES[$key] ?? $key;
    }

    public function releaseLogs()
    {
        return $this->hasMany(DocumentReleaseLog::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status;
    }

    public function getDocumentLabelAttribute(): string
    {
        return self::documentTypeLabel($this->document_type);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($subquery) use ($term) {
            $subquery->where('student_name', 'like', "%{$term}%")
                ->orWhere('student_id', 'like', "%{$term}%")
                ->orWhere('document_type', 'like', "%{$term}%");
        });
    }
}

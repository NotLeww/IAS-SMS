<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentSummary extends Model
{
    use HasFactory;

    protected $table = 'StudentSummary';

    protected $fillable = [
        'id_number',
        'full_name',
        'program',
        'yearLevel',
        'email',
    ];

    public function getCourseYearAttribute(): string
    {
        $program = $this->program ? strtoupper($this->program) : null;
        $year = $this->yearLevel ? 'Year ' . $this->yearLevel : null;

        return trim(sprintf('%s %s', $program, $year));
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($subquery) use ($term) {
            $subquery->where('full_name', 'like', "%{$term}%")
                ->orWhere('id_number', 'like', "%{$term}%");
        });
    }
}

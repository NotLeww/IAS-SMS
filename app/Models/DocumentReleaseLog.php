<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentReleaseLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_request_id',
        'action',
        'details',
    ];

    public function documentRequest()
    {
        return $this->belongsTo(DocumentRequest::class);
    }
}
